<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekDriver implements LLMService
{
    private string $apiKey;

    /** DeepSeek V3/V4 models. We prefer the chat model first, then reasoner for harder tasks. */
    private array $models = [
        'deepseek-chat',      // Main strong model (DeepSeek-V3 / latest chat)
        'deepseek-reasoner',  // Reasoning model, great for complex code extraction
    ];

    public function __construct()
    {
        $this->apiKey = (string) (config('services.deepseek.key') ?? env('DEEPSEEK_API_KEY') ?? '');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey) && strlen($this->apiKey) > 20;
    }

    public function name(): string
    {
        return 'DeepSeek';
    }

    public function generate(string $prompt): ?string
    {
        foreach ($this->models as $model) {
            $result = $this->callModel($model, $prompt);
            if ($result !== null) {
                return $result;
            }
            Log::warning("[DeepSeek:{$model}] Failed, trying next model if available...");
        }

        return null;
    }

    private function callModel(string $model, string $prompt): ?string
    {
        try {
            $response = Http::timeout(180)
                ->withoutVerifying()
                ->retry(2, 3000, fn ($exception) => true, throw: false)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->post('https://api.deepseek.com/chat/completions', [
                    'model'    => $model,
                    'messages' => [
                        [
                            'role'    => 'system',
                            'content' => 'You are an expert developer. Respond with VALID JSON ONLY. No markdown, no explanations outside the JSON.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens'  => 16000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;

                if ($content) {
                    Log::info("[DeepSeek:{$model}] Success");
                    return $content;
                }
            }

            Log::warning("[DeepSeek:{$model}] HTTP {$response->status()}: " . substr($response->body(), 0, 300));
        } catch (\Exception $e) {
            Log::error("[DeepSeek:{$model}] Exception: " . $e->getMessage());
        }

        return null;
    }
}