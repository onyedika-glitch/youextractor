<?php

namespace App\Services;

/**
 * PromptFactory
 *
 * Single source of truth for every prompt sent to an LLM.
 * Keeping prompts here means they can be tweaked without touching service logic.
 */
class PromptFactory
{
    /**
     * Build the 2-step PLAN prompt (Step 1 of the chained pipeline).
     * The model returns structured JSON describing the project plan + tutorial.
     */
    public function planPrompt(string $title, string $videoId, string $transcript): string
    {
        $transcriptSnippet = substr($transcript, 0, 18000);

        $schema = json_encode([
            'stack'          => ['primary' => 'string', 'languages' => [], 'frameworks' => [], 'description' => 'string'],
            'tutorial_guide' => [
                'overview'         => 'string — 5-8 paragraphs',
                'key_concepts'     => [['concept' => 'string', 'explanation' => 'string']],
                'learning_outcomes' => ['string'],
            ],
            'files'               => [['filename' => 'string', 'description' => 'string']],
            'setup_instructions'  => 'string',
            'ide_recommendations' => [
                'primary'      => ['name' => 'string', 'reason' => 'string', 'download_url' => 'string', 'extensions' => []],
                'alternatives' => [['name' => 'string', 'reason' => 'string', 'download_url' => 'string', 'extensions' => []]],
            ],
            'prerequisites' => [
                'software'  => [['name' => 'string', 'download_url' => 'string', 'purpose' => 'string']],
                'knowledge' => ['string'],
                'accounts'  => [],
            ],
            'setup_guide' => ['steps' => [['step' => 1, 'title' => 'string', 'commands' => [], 'explanation' => 'string']]],
            'run_guide'   => [
                'development' => ['commands' => [], 'explanation' => 'string', 'access_url' => 'string'],
                'production'  => ['commands' => [], 'explanation' => 'string'],
                'docker'      => ['commands' => [], 'explanation' => 'string'],
            ],
            'dependencies' => ['npm' => [], 'pip' => [], 'maven' => [], 'composer' => []],
        ], JSON_PRETTY_PRINT);

        return <<<PROMPT
ACT AS A LEAD SYSTEMS ENGINEER AND EXPERT PROGRAMMING TUTOR.

Video Title: {$title}
YouTube Video ID: {$videoId}

Transcript:
{$transcriptSnippet}

YOUR MISSION:
1. Analyse the video content and produce a comprehensive project plan.
2. Generate a blog-post-quality tutorial guide (tutorial_guide.overview must be at least 5 paragraphs).
3. List every file needed for a COMPLETE, runnable project (10–20 files).
4. Extract EVERY piece of code mentioned, shown, or implied in the video.

Respond with VALID JSON ONLY matching this schema — no markdown, no commentary:
{$schema}
PROMPT;
    }

    /**
     * Build the CODE GENERATION prompt (Step 2 of the chained pipeline).
     * Takes the file list from Step 1 and asks the model to fill in full code.
     */
    public function codePrompt(string $title, array $fileList): string
    {
        $files = implode(', ', array_map(fn ($f) => $f['filename'], $fileList));

        return <<<PROMPT
Based on the project plan for "{$title}", generate COMPLETE, WORKING code for these files: {$files}.

Rules:
- NO placeholders, NO "// TODO", NO "// ...rest of code here"
- Every file must be complete and runnable
- Include all imports, configuration, and boilerplate
- Follow best practices and industry standards

Respond with a JSON ARRAY ONLY where each element has:
{"filename": "string", "language": "string", "path": "string", "description": "string", "code": "string"}
PROMPT;
    }

    /**
     * Self-correction prompt.
     * If json_decode() fails, we send the broken output here to get it fixed.
     */
    public function jsonFixPrompt(string $brokenJson): string
    {
        return <<<PROMPT
The following text is supposed to be valid JSON but contains syntax errors.
Fix ALL syntax errors so it becomes perfectly valid JSON.
Return ONLY the corrected JSON — no explanation, no markdown fences.

BROKEN JSON:
{$brokenJson}
PROMPT;
    }
}
