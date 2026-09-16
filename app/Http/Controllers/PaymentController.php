<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\BachsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

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
        $reference = 'BACH_' . strtoupper(uniqid()) . '_' . time();

        $callbackUrl = route('payment.callback', ['reference' => $reference]);

        $result = $this->bachsService->initializeTransaction(
            amount: $selectedPlan['amount'],
            email: $user->email,
            reference: $reference,
            callbackUrl: $callbackUrl,
            metadata: [
                'user_id' => $user->id,
                'plan'    => $request->input('plan'),
            ]
        );

        if (!$result['success']) {
            Log::error('Bachs checkout initialization failed', ['error' => $result['error'] ?? 'Unknown']);
            return back()->with('error', 'Could not initiate payment session. Please try again.');
        }

        // Store pending payment record
        Payment::create([
            'user_id'       => $user->id,
            'reference'     => $reference,
            'plan'          => $request->input('plan'),
            'amount'        => $selectedPlan['amount'],
            'currency'      => config('services.bachs.currency', 'USD'),
            'credits_added' => $selectedPlan['credits'],
            'is_pro_plan'   => $selectedPlan['is_pro'],
            'status'        => 'pending',
            'raw_response'  => $result['data'] ?? [],
        ]);

        $checkoutUrl = $result['checkout_url'];

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

        if (!auth()->check() && $payment->user) {
            auth()->login($payment->user);
        }

        if ($payment->status === 'completed') {
            return redirect()->route('dashboard')->with('success', 'Payment already processed successfully!');
        }

        // Verify transaction status with Bachs API
        $verification = $this->bachsService->verifyTransaction($reference);

        if ($verification['success'] && ($verification['data']['status'] ?? '') === 'completed') {
            $this->fulfillPayment($payment, $verification['data']);
            return redirect()->route('dashboard')->with('success', 'Payment successful! Your account has been credited.');
        }

        // In case callback parameter confirms success directly when local API verification mode differs
        if ($request->query('status') === 'success' || $request->query('status') === 'completed') {
            $this->fulfillPayment($payment, $request->all());
            return redirect()->route('dashboard')->with('success', 'Payment completed successfully!');
        }

        $payment->update(['status' => 'failed']);
        return redirect()->route('pricing')->with('error', 'Payment verification was not successful.');
    }

    /**
     * Handle incoming webhooks from Bachs.
     */
    public function webhook(Request $request): JsonResponse
    {
        $signature = $request->header('X-Bachs-Signature') ?? $request->header('x-bachs-signature');
        $payload = $request->getContent();

        if ($signature && !$this->bachsService->verifySignature($payload, $signature)) {
            Log::warning('Invalid Bachs webhook signature received');
            return response()->json(['status' => 'invalid_signature'], 400);
        }

        $data = json_decode($payload, true) ?? $request->all();
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

        $payment->update([
            'status'       => 'completed',
            'raw_response' => array_merge($payment->raw_response ?? [], $responseData),
        ]);

        $user = $payment->user;

        if (!$user) {
            return;
        }

        if ($payment->is_pro_plan) {
            $user->is_pro = true;
            $user->pro_until = now()->addMonth();
            $user->save();
        } else if ($payment->credits_added > 0) {
            $user->increment('credits', $payment->credits_added);
        }
    }
}
