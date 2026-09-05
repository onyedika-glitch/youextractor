<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIDriver implements LLMService
{
    private string $apiKey;
    private string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = (string) (config('services.openai.key') ?? env('OPENAI_API_KEY') ?? '');
    }

    public function isAvailable(): bool
    {
        return !empty($this->apiKey) && strlen($this->apiKey) > 20;
    }

    public function name(): string
    {
        return 'OpenAI';
    }

    public function generate(string $prompt): ?string
    {
        try {
            $response = Http::timeout(180)
                ->withoutVerifying()
                ->retry(2, 3000, fn ($exception) => true, throw: false)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type'  => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'system', 'content' => 'You are an expert developer. Respond with JSON ONLY.'],
                        ['role' => 'user',   'content' => $prompt],
                    ],
                    'temperature' => 0.5,
                    'max_tokens'  => 16000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::warning("[OpenAI] HTTP {$response->status()}: {$response->body()}");
        } catch (\Exception $e) {
            Log::error('[OpenAI] Exception: ' . $e->getMessage());
        }

        return null;
    }
}
