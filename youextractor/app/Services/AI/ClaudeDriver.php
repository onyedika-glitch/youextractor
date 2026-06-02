<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Anthropic Claude driver.
 *
 * Claude 3.5 Sonnet is the highest-quality model for coding tasks as of 2025.
 * Add ANTHROPIC_API_KEY to your .env to enable this driver.
 */
class ClaudeDriver implements LLMService
{
    private string $apiKey;
    private string $model = 'claude-sonnet-4-5';
    private string $apiVersion = '2023-06-01';

    public function __construct()
    {
        $this->apiKey = (string) (config('services.anthropic.key') ?? env('ANTHROPIC_API_KEY') ?? '');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey) && strlen($this->apiKey) > 20;
    }

    public function name(): string
    {
        return 'Anthropic Claude';
    }

    public function generate(string $prompt): ?string
    {
        try {
            $response = Http::timeout(180)
                ->retry(2, 3000, fn ($exception) => true, throw: false)
                ->withHeaders([
                    'x-api-key'         => $this->apiKey,
                    'anthropic-version' => $this->apiVersion,
                    'Content-Type'      => 'application/json',
                ])
                ->post('https://api.anthropic.com/v1/messages', [
                    'model'      => $this->model,
                    'max_tokens' => 16000,
                    'system'     => 'You are an expert developer. Respond with JSON ONLY. No markdown, no extra text.',
                    'messages'   => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'][0]['text'] ?? null;
            }

            Log::warning("[Claude] HTTP {$response->status()}: {$response->body()}");
        } catch (\Exception $e) {
            Log::error('[Claude] Exception: ' . $e->getMessage());
        }

        return null;
    }
}
