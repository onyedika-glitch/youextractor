<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\BachsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private BachsService $bachsService)
    {
    }

    /**
     * Show the pricing page.
     */
    public function index(): View
    {
        $user = auth()->user();
        return view('pricing', [
            'user' => $user,
        ]);
    }

    /**
     * Initiate checkout with Bachs.
     */
    public function checkout(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'plan' => 'required|string|in:starter,pro_pack,pro_monthly',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('signin');
        }

        $plans = [
            'starter' => [
                'name'         => 'Starter Credit Pack',
                'amount'       => 2.00,
                'credits'      => 5,
                'is_pro'       => false,
            ],
            'pro_pack' => [
                'name'         => 'Pro Credit Pack',
                'amount'       => 5.00,
                'credits'      => 20,
                'is_pro'       => false,
            ],
            'pro_monthly' => [
                'name'         => 'Unlimited Pro Monthly',
                'amount'       => 12.00,
                'credits'      => 0,
                'is_pro'       => true,
            ],
        ];

        $selectedPlan = $plans[$request->input('plan')];
        $reference = 'BACH_' . strtoupper(Str::random(32));

        $callbackUrl = route('payment.callback', ['reference' => $reference]);

        $result = $this->bachsService->initializeTransaction([
            'amount'       => $selectedPlan['amount'],
            'email'        => $user->email,
            'name'         => $user->name,
            'reference'    => $reference,
            'callback_url' => $callbackUrl,
            'currency'     => config('services.bachs.currency', 'USD'),
            'metadata'     => [
                'user_id' => $user->id,
                'plan'    => $request->input('plan'),
            ],
        ]);

        $checkoutUrl = is_array($result) ? ($result['data']['authorization_url'] ?? null) : null;
        if (! $checkoutUrl) {
            Log::error('Bachs checkout initialization failed');
            return back()->with('error', 'Could not initiate payment session. Please try again.');
        }

        // Store pending payment record using the columns that actually exist.
        Payment::create([
            'user_id'       => $user->id,
            'reference'     => $reference,
            'checkout_id'   => $result['data']['session_id'] ?? null,
            'amount'        => $selectedPlan['amount'],
            'currency'      => config('services.bachs.currency', 'USD'),
            'plan_type'     => $request->input('plan'),
            'credits_added' => $selectedPlan['credits'],
            'status'        => 'pending',
            'metadata'      => [
                'is_pro' => $selectedPlan['is_pro'],
                'plan'   => $request->input('plan'),
            ],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success'      => true,
                'checkout_url' => $checkoutUrl,
            ]);
        }

        return redirect()->away($checkoutUrl);
    }

    /**
     * Handle user redirect back from Bachs.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?? $request->query('trxref');

        if (!$reference) {
            return redirect()->route('pricing')->with('error', 'Payment reference missing.');
        }

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('pricing')->with('error', 'Payment record not found.');
        }

        // Never log a visitor in from a payment reference: send them to sign in instead.
        $destination = auth()->check() ? 'dashboard' : 'signin';

        if ($payment->status === 'completed') {
            return redirect()->route($destination)->with('success', 'Payment already processed successfully!');
        }

        // Only Bachs' own verification API can confirm a payment; query parameters are ignored.
        $verification = $this->bachsService->verifyTransaction($payment->checkout_id ?: $reference);
        $verifiedStatus = strtolower((string) (is_array($verification) ? ($verification['data']['status'] ?? '') : ''));
        $verifiedReference = is_array($verification) ? ($verification['data']['reference'] ?? null) : null;
        $verified = is_array($verification)
            && (bool) ($verification['status'] ?? false)
            && in_array($verifiedStatus, ['success', 'completed', 'succeeded', 'paid'], true)
            && ($verifiedReference === null || $verifiedReference === $payment->reference);

        if ($verified) {
            $this->fulfillPayment($payment, $verification['data'] ?? []);
            return redirect()->route($destination)->with('success', 'Payment successful! Your account has been credited.');
        }

        // A session that is still open may complete later via webhook, so leave it pending.
        if ($verifiedStatus !== 'pending') {
            $payment->update(['status' => 'failed']);
        }

        return redirect()->route('pricing')->with('error', 'Payment verification was not successful.');
    }

    /**
     * Handle incoming webhooks from Bachs.
     */
    public function webhook(Request $request): JsonResponse
    {
        $signature = $request->header('X-Bachs-Signature') ?? $request->header('x-bachs-signature');
        $timestamp = $request->header('X-Bachs-Timestamp') ?? $request->header('x-bachs-timestamp');
        $payload = $request->getContent();
        $secret = config('services.bachs.webhook_secret') ?: config('services.bachs.secret_key');

        // Reject every webhook that is unsigned or signed with the wrong secret.
        if (! $secret || ! BachsService::verifySignature($payload, $timestamp, $signature, $secret)) {
            Log::warning('Invalid or missing Bachs webhook signature received');
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        $data = json_decode($payload, true);
        if (! is_array($data)) {
            return response()->json(['status' => 'invalid_payload'], 400);
        }
        $reference = $data['reference'] ?? $data['data']['reference'] ?? null;

        if ($reference) {
            $payment = Payment::where('reference', $reference)->first();
            if ($payment && $payment->status !== 'completed') {
                $status = strtolower($data['status'] ?? $data['data']['status'] ?? 'completed');
                if (in_array($status, ['completed', 'success', 'paid'])) {
                    $this->fulfillPayment($payment, $data);
                } else if (in_array($status, ['failed', 'cancelled'])) {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Credit the user account upon verified payment.
     */
    private function fulfillPayment(Payment $payment, array $responseData): void
    {
        if ($payment->status === 'completed') {
            return;
        }

        $metadata = $payment->metadata ?? [];
        $metadata['verification'] = $responseData;

        $payment->update([
            'status'      => 'completed',
            'metadata'    => $metadata,
            'checkout_id' => $payment->checkout_id ?: ($responseData['checkout_id'] ?? null),
        ]);

        $user = $payment->user;

        if (!$user) {
            return;
        }

        $isPro = ! empty($metadata['is_pro']) || $payment->plan_type === 'pro_monthly';

        if ($isPro) {
            $user->is_pro = true;
            $base = ($user->pro_until && $user->pro_until->isFuture()) ? $user->pro_until : now();
            $user->pro_until = $base->copy()->addMonth();
            $user->save();
        } else if ($payment->credits_added > 0) {
            $user->increment('credits', $payment->credits_added);
        }
    }
}
