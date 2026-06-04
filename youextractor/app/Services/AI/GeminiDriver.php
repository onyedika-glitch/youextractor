<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiDriver implements LLMService
{
    /** Models tried in order of preference */
    private array $models = ['gemini-2.5-flash', 'gemini-2.5-pro', 'gemini-1.5-pro', 'gemini-1.5-flash'];

    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = (string) (config('services.gemini.key') ?? env('GEMINI_API_KEY') ?? '');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey) && strlen($this->apiKey) > 20;
    }

    public function name(): string
    {
        return 'Google Gemini';
    }

    /**
     * Generate a response, trying each model in sequence with built-in retries.
     */
    public function generate(string $prompt): ?string
    {
        foreach ($this->models as $model) {
            $result = $this->callWithRetry($model, $prompt);
            if ($result !== null) {
                return $result;
            }
        }
        return null;
    }

    /**
     * Call Gemini with automatic retry on transient failures (rate limits, 5xx).
     */
    private function callWithRetry(string $model, string $prompt, int $maxAttempts = 3): ?string
    {
        $attempt = 0;
        while ($attempt < $maxAttempts) {
            try {
                $response = Http::timeout(180)
                    ->retry(2, 2000, fn ($exception) => true, throw: false)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post(
                        "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->apiKey}",
                        [
                            'contents'        => [['parts' => [['text' => $prompt]]]],
                            'generationConfig' => [
                                'temperature'     => 0.4,
                                'maxOutputTokens' => 12000,
                            ],
                        ]
                    );

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                }

                // 429 = quota exceeded – wait and retry
                if ($response->status() === 429) {
                    Log::warning("[Gemini:{$model}] Rate limited. Waiting before retry…");
                    sleep(5 * ($attempt + 1));
                    $attempt++;
                    continue;
                }

                Log::warning("[Gemini:{$model}] HTTP {$response->status()}: {$response->body()}");
                break; // non-retryable HTTP error, try next model
            } catch (\Exception $e) {
                Log::error("[Gemini:{$model}] Exception: " . $e->getMessage());
                $attempt++;
            }
        }

        return null;
    }
}
