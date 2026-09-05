<?php

namespace App\Services\AI;

/**
 * Contract that every AI driver must implement.
 *
 * A "driver" wraps one specific LLM provider (Gemini, OpenAI, Claude…)
 * and exposes a single, uniform generate() method so the rest of the
 * application never has to know which provider is being used.
 */
interface LLMService
{
    /**
     * Send a prompt and return the raw text response.
     *
     * @param  string  $prompt  The full prompt to send.
     * @return string|null      The model's text output, or null on failure.
     */
    public function generate(string $prompt): ?string;

    /**
     * Whether this driver has a valid API key configured.
     */
    public function isAvailable(): bool;

    /**
     * Human-readable name of the provider (used in logs/errors).
     */
    public function name(): string;
}
