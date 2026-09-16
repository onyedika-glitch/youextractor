<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BachsService
{
    protected ?string $secretKey = null;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.bachs.secret_key');
        $this->baseUrl = str_starts_with($this->secretKey ?? '', 'sk_sandbox_') 
            ? 'https://sandbox-api.bachs.io' 
            : 'https://api.bachs.io';
    }

    /**
     * Check if Bachs service is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->secretKey);
    }

    /**
     * Initialize a payment transaction / checkout session.
     *
     * @param array $params  ['email' => string, 'amount' => float/int (USD), 'reference' => string, 'metadata' => array, 'name' => string, 'callback_url' => string]
     * @return array|null
     */
    public function initializeTransaction(array $params): ?array
    {
        if (! $this->isConfigured()) {
            Log::error('Bachs initialization skipped: secret key missing');
            return null;
        }

        $amountDecimal = number_format((float) ($params['amount'] ?? 0), 2, '.', '');

        $payload = [
            'customer' => [
                'name' => $params['name'] ?? explode('@', $params['email'])[0],
                'email' => $params['email'],
            ],
            'pricing' => [
                'amount' => $amountDecimal,
                'currency' => strtoupper($params['currency'] ?? config('services.bachs.currency', 'USD')),
            ],
            'reference' => $params['reference'],
            'success_url' => $params['callback_url'] ?? url('/payment/callback'),
            'metadata' => $params['metadata'] ?? [],
        ];

        $response = Http::withToken($this->secretKey)
            ->timeout(30)
            ->post($this->baseUrl . '/v1/checkout-sessions', $payload);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'status' => true,
                'data' => [
                    'authorization_url' => $data['checkout_url'] ?? null,
                    'session_id' => $data['checkout_id'] ?? null,
                ]
            ];
        }

        Log::error('Bachs initialization failed', [
            'status' => $response->status(),
            'response' => $response->body(),
            'payload' => $payload,
        ]);

        return null;
    }

    /**
     * Verify a checkout session by its checkout ID.
     */
    public function verifyTransaction(string $checkoutId): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $endpoint = $this->baseUrl . '/v1/checkout-sessions/' . urlencode($checkoutId);

        $response = Http::withToken($this->secretKey)
            ->timeout(30)
            ->get($endpoint);

        if ($response->successful()) {
            $data = $response->json();
            $sessionStatus = strtolower((string) ($data['status'] ?? ''));
            $paymentStatus = strtolower((string) ($data['payment_status'] ?? ''));
            $paid = in_array($sessionStatus, ['completed', 'complete', 'succeeded'], true)
                || $paymentStatus === 'succeeded';

            return [
                'status' => true,
                'data' => [
                    'status' => $paid ? 'success' : ($sessionStatus === 'open' ? 'pending' : $sessionStatus),
                    'reference' => $data['reference'] ?? null,
                    'metadata' => $data['metadata'] ?? [],
                    'amount' => $data['amount'] ?? 0,
                    'checkout_id' => $checkoutId,
                ]
            ];
        }

        Log::error('Bachs verification failed', [
            'checkout_id' => $checkoutId,
            'response' => $response->body(),
        ]);

        return null;
    }

    /**
     * Verify Bachs Webhook Signature.
     */
    public static function verifySignature(string $rawBody, ?string $timestamp, ?string $signature, string $secret): bool
    {
        if (empty($signature)) {
            return false;
        }

        if (! empty($timestamp)) {
            $expected1 = hash_hmac('sha256', $timestamp . '.' . $rawBody, $secret);
            if (hash_equals($expected1, $signature)) {
                return true;
            }

            $expected3 = hash_hmac('sha256', $timestamp . $rawBody, $secret);
            if (hash_equals($expected3, $signature)) {
                return true;
            }
        }

        $expected2 = hash_hmac('sha256', $rawBody, $secret);
        return hash_equals($expected2, $signature);
    }

    /**
     * Generate unique reference string for payment transactions.
     */
    public static function generateReference(string $prefix = 'YOUEXT'): string
    {
        return $prefix . '-' . strtoupper(uniqid());
    }
}
