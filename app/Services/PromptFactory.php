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
        $transcriptSnippet = substr($transcript, 0, 150000);

        $schema = json_encode([
            'stack'          => ['primary' => 'string', 'languages' => [], 'frameworks' => [], 'description' => 'string'],
            'tutorial_guide' => [
                'overview'         => 'string — 5-8 paragraphs detailed overview of the video tutorial',
                'key_concepts'     => [['concept' => 'string', 'explanation' => 'string']],
                'learning_outcomes' => ['string'],
            ],
            'files'               => [['filename' => 'string', 'description' => 'string describing exactly what functions, classes, and code from the video must go in this file']],
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
3. List every file needed for a COMPLETE, runnable project (typically 5–15 files).
4. For each file, write a highly detailed description of all the functions, UI elements, API endpoints, and logic discussed in the transcript that should be implemented in it.

Respond with VALID JSON ONLY matching this schema — no markdown, no commentary:
{$schema}
PROMPT;
    }

    /**
     * Build the CODE GENERATION prompt (Step 2 of the chained pipeline).
     * Takes the file list from Step 1 and asks the model to fill in full code.
     */
    public function codePrompt(string $title, array $fileList, string $transcript, ?array $stack = null): string
    {
        $transcriptSnippet = substr($transcript, 0, 150000);
        $filesFormatted = json_encode($fileList, JSON_PRETTY_PRINT);
        $stackFormatted = $stack ? json_encode($stack) : 'N/A';

        return <<<PROMPT
ACT AS A LEAD SOFTWARE ARCHITECT.

You are generating the actual source code files for the project: "{$title}".
Technology Stack: {$stackFormatted}

Here is the transcript of the video tutorial describing the code and implementation:
--- TRANSCRIPT ---
{$transcriptSnippet}
--- END TRANSCRIPT ---

We have planned the following files to be created:
{$filesFormatted}

YOUR TASK:
For each file listed above, write the COMPLETE, FULL-FEATURED, PRODUCTION-READY source code.
You MUST extract the exact logic, components, functions, styles, endpoints, and variables shown or described in the transcript.
Do NOT write small, minimal, or generic "hello world" code unless that is literally all the video built.
Do NOT use placeholders like "// TODO" or "// rest of code here". Write out every single line of code so the project is fully functional.

Respond with a VALID JSON ARRAY ONLY (no markdown fences, no extra text) where each element contains:
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
