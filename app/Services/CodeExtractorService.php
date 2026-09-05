<?php

namespace App\Services;

use App\Services\AI\LLMService;
use App\Services\AI\DeepSeekDriver;
use App\Services\AI\GeminiDriver;
use App\Services\AI\OpenAIDriver;
use App\Services\AI\ClaudeDriver;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

/**
 * CodeExtractorService  (refactored coordinator)
 *
 * This class no longer contains prompts, ZIP logic, or raw HTTP calls.
 * It coordinates three focused collaborators:
 *   - PromptFactory   → builds prompts
 *   - LLMService      → calls the AI provider
 *   - ProjectPackager → generates ZIP archives
 *
 * AI Provider Priority (DeepSeek is now the default):
 *   1. DeepSeek V4 / V3 (deepseek-chat + deepseek-reasoner) - new primary, cost-effective & strong at code
 *   2. Anthropic Claude  (excellent fallback)
 *   3. Google Gemini     (2-step chained pipeline)
 *   4. OpenAI            (final fallback)
 */
class CodeExtractorService
{
    private PromptFactory  $prompts;
    private ProjectPackager $packager;

    /** Ordered list of drivers to try */
    private array $drivers;

    public function __construct()
    {
        $this->prompts  = new PromptFactory();
        $this->packager = new ProjectPackager();

        // Priority order (DeepSeek V4 first as default, with automatic fallback)
        $this->drivers = [
            new DeepSeekDriver(),   // DeepSeek V3/V4 models (deepseek-chat + reasoner) - new default
            new ClaudeDriver(),
            new GeminiDriver(),
            new OpenAIDriver(),
        ];
    }

    // ------------------------------------------------------------------
    // Public API
    // ------------------------------------------------------------------

    /**
     * Main entry point. Returns structured extraction data.
     */
    public function extractCodeFromTranscript(string $title, string $transcript, string $videoId): array
    {
        foreach ($this->drivers as $driver) {
            /** @var LLMService $driver */
            if (!$driver->isAvailable()) {
                continue;
            }

            Log::info("Attempting extraction with {$driver->name()} for [{$videoId}]");

            $result = $this->runChainedPipeline($driver, $title, $transcript, $videoId);

            if ($result !== null && !empty($result['files'])) {
                Log::info("Extraction succeeded with {$driver->name()} for [{$videoId}]");
                return $result;
            }

            Log::warning("{$driver->name()} failed or returned no files. Trying next driver…");
        }

        Log::warning("All AI drivers failed or returned no files for [{$videoId}]. Using fallback.");
        return $this->generateFallbackProject($title);
    }

    /**
     * Delegate ZIP generation to the packager.
     */
    public function generateZipFile(string $videoId, array $codeData): ?string
    {
        return $this->packager->generateZip($videoId, $codeData);
    }

    /**
     * Ask questions to the AI about the video context, explanation, and code.
     */
    public function chatAboutVideo(Video $video, string $question): string
    {
        foreach ($this->drivers as $driver) {
            /** @var LLMService $driver */
            if (!$driver->isAvailable()) {
                continue;
            }

            $contextSnippet = "";
            if (!empty($video->code_snippets)) {
                foreach ($video->code_snippets as $snip) {
                    $contextSnippet .= "File: " . ($snip['path'] ?? $snip['filename'] ?? 'unknown') . "\n";
                    $contextSnippet .= "Language: " . ($snip['language'] ?? 'text') . "\n";
                    $contextSnippet .= "Code:\n" . ($snip['code'] ?? '') . "\n\n";
                }
            }

            $prompt = "You are YouExtractor Copilot, an AI programming assistant.
The user is studying a YouTube programming tutorial video.
Video Title: \"{$video->title}\"
Overview/Summary: {$video->summary}
Detailed Tutorial Concepts: {$video->explanation}

Here is the source code extracted from the video:
{$contextSnippet}

User is asking a question about this tutorial and code.
User Question: \"{$question}\"

Provide a professional, concise, and educational response. Use markdown formatting for code snippets if you need to provide code. Explain clearly how the concepts relate to the tutorial code.";

            $response = $driver->generate($prompt);
            if ($response !== null) {
                return $response;
            }
        }

        // Return a mock/fallback explanation if no driver is configured, using local rules or general knowledge.
        return "AI chat is currently unavailable (no active API key was found in .env, or the AI service failed). Please verify your ANTHROPIC_API_KEY, GEMINI_API_KEY, or OPENAI_API_KEY.";
    }

    // ------------------------------------------------------------------
    // Pipeline logic
    // ------------------------------------------------------------------

    /**
     * Two-step pipeline:
     *   Step 1 → Generate project plan + tutorial guide
     *   Step 2 → Generate full code for each planned file
     */
    private function runChainedPipeline(
        LLMService $driver,
        string $title,
        string $transcript,
        string $videoId
    ): ?array {
        try {
            // ---- Step 1: Plan -------------------------------------------
            $planPrompt = $this->prompts->planPrompt($title, $videoId, $transcript);
            $planRaw    = $driver->generate($planPrompt);

            if ($planRaw === null) {
                return null;
            }

            $planData = $this->parseWithSelfCorrection($planRaw, $driver, true);
            Log::info("Step 1 planData keys: " . implode(', ', array_keys($planData)) . " files count: " . count($planData['files'] ?? []));
            Log::debug("Step 1 planData raw files: " . json_encode($planData['files'] ?? []));

            if (empty($planData['tutorial_guide'])) {
                Log::warning("[{$driver->name()}] Step 1 returned no tutorial_guide.");
                return null;
            }

            // ---- Step 2: Code generation ---------------------------------
            $fileList  = array_slice($planData['files'] ?? [], 0, 15);
            $codePrompt = $this->prompts->codePrompt($title, $fileList, $transcript, $planData['stack'] ?? null);
            $codeRaw    = $driver->generate($codePrompt);

            if ($codeRaw !== null) {
                $codeFiles = $this->parseWithSelfCorrection($codeRaw, $driver, false);
                Log::info("Step 2 codeFiles type: " . gettype($codeFiles) . " count: " . count($codeFiles));
                Log::debug("Step 2 codeFiles content: " . json_encode($codeFiles));

                // The code step may return a top-level array or {"files": [...]}
                if (is_array($codeFiles)) {
                    $planData['files'] = isset($codeFiles['files'])
                        ? $codeFiles['files']
                        : (array_is_list($codeFiles) ? $codeFiles : ($planData['files'] ?? []));
                }
                Log::info("After step 2, planData files count: " . count($planData['files'] ?? []));
            }

            return $planData;
        } catch (\Exception $e) {
            Log::error("[{$driver->name()}] Pipeline exception: " . $e->getMessage());
            return null;
        }
    }

    // ------------------------------------------------------------------
    // JSON parsing with self-correction (Improvement #3)
    // ------------------------------------------------------------------

    /**
     * Parse an AI response into a PHP array.
     * If JSON is malformed, attempt self-correction by sending it back to the model.
     */
    private function parseWithSelfCorrection(string $raw, LLMService $driver, bool $isPlanStep = false): array
    {
        // Strip markdown fences and extract JSON substring
        $clean = $this->stripMarkdownFences($raw);
        $clean = $this->extractJsonSubString($clean);

        $data = json_decode($clean, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            return $isPlanStep ? $this->normaliseKeys($data) : $data;
        }

        // Self-correction: ask the same driver to fix the JSON
        Log::warning("[{$driver->name()}] JSON parse error — attempting self-correction…");
        $fixPrompt = $this->prompts->jsonFixPrompt($clean);
        $fixed     = $driver->generate($fixPrompt);

        if ($fixed !== null) {
            $fixedClean = $this->stripMarkdownFences($fixed);
            $fixedClean = $this->extractJsonSubString($fixedClean);
            $data       = json_decode($fixedClean, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                Log::info("[{$driver->name()}] Self-correction succeeded.");
                return $isPlanStep ? $this->normaliseKeys($data) : $data;
            }
        }

        Log::error("[{$driver->name()}] Self-correction failed. Returning empty array.");
        return [];
    }

    /** Extract the substring between the first '[' or '{' and the last ']' or '}'. */
    private function extractJsonSubString(string $content): string
    {
        $firstBracket = strpos($content, '[');
        $firstBrace = strpos($content, '{');
        
        $startPos = false;
        $endChar = '';
        
        if ($firstBracket !== false && $firstBrace !== false) {
            if ($firstBracket < $firstBrace) {
                $startPos = $firstBracket;
                $endChar = ']';
            } else {
                $startPos = $firstBrace;
                $endChar = '}';
            }
        } elseif ($firstBracket !== false) {
            $startPos = $firstBracket;
            $endChar = ']';
        } elseif ($firstBrace !== false) {
            $startPos = $firstBrace;
            $endChar = '}';
        }
        
        if ($startPos !== false) {
            $endPos = strrpos($content, $endChar);
            if ($endPos !== false && $endPos > $startPos) {
                return substr($content, $startPos, $endPos - $startPos + 1);
            }
        }
        
        return $content;
    }

    /** Strip ```json … ``` or ``` … ``` fences. */
    private function stripMarkdownFences(string $content): string
    {
        $content = trim($content);
        if (str_starts_with($content, '```')) {
            $firstLineBreak = strpos($content, "\n");
            if ($firstLineBreak !== false) {
                if (str_ends_with($content, '```')) {
                    $content = substr($content, $firstLineBreak + 1);
                    $content = substr($content, 0, -3);
                }
            }
        }
        return trim($content);
    }


    /** Normalise the keys we expect so downstream code never breaks. */
    private function normaliseKeys(array $data): array
    {
        return [
            'stack'               => $data['stack']               ?? null,
            'files'               => $data['files']               ?? [],
            'setup_instructions'  => $data['setup_instructions']  ?? '',
            'dependencies'        => $data['dependencies']        ?? [],
            'tutorial_guide'      => $data['tutorial_guide']      ?? null,
            'ide_recommendations' => $data['ide_recommendations'] ?? null,
            'prerequisites'       => $data['prerequisites']       ?? null,
            'setup_guide'         => $data['setup_guide']         ?? null,
            'run_guide'           => $data['run_guide']           ?? null,
        ];
    }

    // ------------------------------------------------------------------
    // Fallback project generator (no AI available)
    // ------------------------------------------------------------------

    private function generateFallbackProject(string $title): array
    {
        $stack = $this->detectStackFromTitle($title);

        return [
            'stack'               => $stack,
            'files'               => $this->generateFallbackFiles($stack, $title),
            'setup_instructions'  => $this->getSetupInstructions($stack),
            'dependencies'        => $this->getDependencies($stack),
            'tutorial_guide'      => $this->buildFallbackGuide($title, $stack),
            'ide_recommendations' => $this->getIdeRecommendations($stack),
            'prerequisites'       => $this->getPrerequisites($stack),
            'setup_guide'         => $this->getSetupGuide($stack),
            'run_guide'           => $this->getRunGuide($stack),
        ];
    }

    private function detectStackFromTitle(string $title): array
    {
        $t = strtolower($title);

        $stacks = [
            'react'      => ['react', 'reactjs', 'next.js', 'nextjs'],
            'vue'        => ['vue', 'vuejs', 'nuxt'],
            'angular'    => ['angular'],
            'node'       => ['node', 'nodejs', 'express'],
            'python'     => ['python', 'django', 'flask', 'fastapi'],
            'java'       => ['java', 'spring', 'springboot', 'spring boot'],
            'php'        => ['php', 'laravel', 'symfony'],
            'typescript' => ['typescript', ' ts '],
            'go'         => ['golang', 'go '],
            'rust'       => ['rust'],
            'csharp'     => ['c#', 'csharp', '.net', 'dotnet', 'asp.net'],
        ];

        foreach ($stacks as $primary => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($t, $kw)) {
                    return [
                        'primary'     => $primary,
                        'languages'   => [$primary],
                        'frameworks'  => [],
                        'description' => "Detected from video title: {$title}",
                    ];
                }
            }
        }

        return [
            'primary'     => 'javascript',
            'languages'   => ['javascript'],
            'frameworks'  => [],
            'description' => 'Default stack — JavaScript',
        ];
    }

    private function generateFallbackFiles(array $stack, string $title): array
    {
        $templates = [
            'java'       => [
                ['filename' => 'Application.java', 'language' => 'java',     'path' => 'src/main/java/com/example/Application.java', 'description' => 'Spring Boot entry point', 'code' => "package com.example;\n\nimport org.springframework.boot.SpringApplication;\nimport org.springframework.boot.autoconfigure.SpringBootApplication;\n\n@SpringBootApplication\npublic class Application {\n    public static void main(String[] args) {\n        SpringApplication.run(Application.class, args);\n    }\n}"],
                ['filename' => 'pom.xml',          'language' => 'xml',      'path' => 'pom.xml',                                    'description' => 'Maven configuration',      'code' => "<?xml version=\"1.0\"?>\n<project>\n    <modelVersion>4.0.0</modelVersion>\n    <groupId>com.example</groupId>\n    <artifactId>app</artifactId>\n    <version>1.0.0</version>\n</project>"],
                ['filename' => 'README.md',         'language' => 'markdown', 'path' => 'README.md',                                   'description' => 'Project docs',             'code' => "# {$title}\n\n```bash\nmvn spring-boot:run\n```"],
            ],
            'python'     => [
                ['filename' => 'main.py',           'language' => 'python',   'path' => 'main.py',           'description' => 'Flask app',          'code' => "from flask import Flask\napp = Flask(__name__)\n\n@app.route('/')\ndef index():\n    return {'status': 'ok'}\n\nif __name__ == '__main__':\n    app.run(debug=True)"],
                ['filename' => 'requirements.txt',  'language' => 'text',     'path' => 'requirements.txt',  'description' => 'Python deps',        'code' => "flask>=2.0.0\nrequests>=2.25.0"],
                ['filename' => 'README.md',          'language' => 'markdown', 'path' => 'README.md',          'description' => 'Project docs',       'code' => "# {$title}\n\n```bash\npip install -r requirements.txt\npython main.py\n```"],
            ],
            'react'      => [
                ['filename' => 'App.jsx',            'language' => 'jsx',      'path' => 'src/App.jsx',        'description' => 'Root component',     'code' => "import React, { useState } from 'react';\n\nexport default function App() {\n  const [count, setCount] = useState(0);\n  return (\n    <div>\n      <h1>Hello React!</h1>\n      <button onClick={() => setCount(c => c + 1)}>Count: {count}</button>\n    </div>\n  );\n}"],
                ['filename' => 'package.json',       'language' => 'json',     'path' => 'package.json',       'description' => 'npm config',         'code' => "{\n  \"name\": \"react-app\",\n  \"version\": \"1.0.0\",\n  \"scripts\": { \"dev\": \"vite\", \"build\": \"vite build\" },\n  \"dependencies\": { \"react\": \"^18.2.0\", \"react-dom\": \"^18.2.0\" },\n  \"devDependencies\": { \"vite\": \"^5.0.0\", \"@vitejs/plugin-react\": \"^4.0.0\" }\n}"],
                ['filename' => 'README.md',           'language' => 'markdown', 'path' => 'README.md',           'description' => 'Project docs',       'code' => "# {$title}\n\n```bash\nnpm install && npm run dev\n```"],
            ],
            'node'       => [
                ['filename' => 'index.js',           'language' => 'javascript','path' => 'src/index.js',      'description' => 'Express server',     'code' => "const express = require('express');\nconst app = express();\napp.use(express.json());\napp.get('/api/health', (req, res) => res.json({ status: 'ok' }));\napp.listen(3000, () => console.log('Server on :3000'));"],
                ['filename' => 'package.json',       'language' => 'json',     'path' => 'package.json',       'description' => 'npm config',         'code' => "{\n  \"name\": \"node-app\",\n  \"version\": \"1.0.0\",\n  \"scripts\": { \"start\": \"node src/index.js\", \"dev\": \"nodemon src/index.js\" },\n  \"dependencies\": { \"express\": \"^4.18.0\" }\n}"],
                ['filename' => 'README.md',           'language' => 'markdown', 'path' => 'README.md',           'description' => 'Project docs',       'code' => "# {$title}\n\n```bash\nnpm install && npm run dev\n```"],
            ],
            'php'        => [
                ['filename' => 'index.php',          'language' => 'php',      'path' => 'public/index.php',   'description' => 'Entry point',        'code' => "<?php\necho json_encode(['status' => 'ok']);"],
                ['filename' => 'composer.json',      'language' => 'json',     'path' => 'composer.json',      'description' => 'Composer config',    'code' => "{\n  \"name\": \"my/app\",\n  \"autoload\": { \"psr-4\": { \"App\\\\\": \"src/\" } }\n}"],
                ['filename' => 'README.md',           'language' => 'markdown', 'path' => 'README.md',           'description' => 'Project docs',       'code' => "# {$title}\n\n```bash\ncomposer install && php -S localhost:8000 -t public\n```"],
            ],
        ];

        return $templates[$stack['primary']] ?? [
            ['filename' => 'main.js', 'language' => 'javascript', 'path' => 'src/main.js', 'description' => 'Main file', 'code' => "// {$title}\nconsole.log('Hello World');"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Project docs', 'code' => "# {$title}"],
        ];
    }

    private function getSetupInstructions(array $stack): string
    {
        return match ($stack['primary']) {
            'java'   => "mvn clean install\nmvn spring-boot:run",
            'python' => "pip install -r requirements.txt\npython main.py",
            'node'   => "npm install\nnpm run dev",
            'react'  => "npm install\nnpm run dev",
            'php'    => "composer install\nphp -S localhost:8000 -t public",
            default  => "See README.md for setup instructions",
        };
    }

    private function getDependencies(array $stack): array
    {
        return [
            'npm'      => in_array($stack['primary'], ['node', 'javascript', 'react']) ? ['express'] : [],
            'pip'      => $stack['primary'] === 'python'  ? ['flask', 'requests'] : [],
            'maven'    => $stack['primary'] === 'java'    ? ['spring-boot-starter-web'] : [],
            'composer' => $stack['primary'] === 'php'     ? [] : [],
        ];
    }

    private function buildFallbackGuide(string $title, array $stack): array
    {
        return [
            'overview' => "This project was extracted from the YouTube tutorial \"{$title}\".\n\nThe detected technology stack is {$stack['primary']}. This guide provides basic scaffolding so you can follow along with the tutorial.\n\nFor richer, AI-generated explanations, please configure a valid API key (ANTHROPIC_API_KEY, GEMINI_API_KEY, or OPENAI_API_KEY) in your .env file.",
            'key_concepts' => [
                ['concept' => ucfirst($stack['primary']) . ' Fundamentals', 'explanation' => "Core {$stack['primary']} concepts covered in this tutorial."],
                ['concept' => 'Project Structure', 'explanation' => 'Standard project layout for the detected tech stack.'],
            ],
            'learning_outcomes' => [
                "Understand {$stack['primary']} project setup",
                'Run and modify the project locally',
            ],
        ];
    }

    private function getIdeRecommendations(array $stack): array
    {
        $map = [
            'java'   => ['primary' => ['name' => 'IntelliJ IDEA',  'reason' => 'Best Java IDE', 'download_url' => 'https://www.jetbrains.com/idea/',    'extensions' => ['Spring Boot']]],
            'python' => ['primary' => ['name' => 'PyCharm',         'reason' => 'Best Python IDE','download_url' => 'https://www.jetbrains.com/pycharm/', 'extensions' => []]],
            'php'    => ['primary' => ['name' => 'PhpStorm',        'reason' => 'Best PHP IDE',  'download_url' => 'https://www.jetbrains.com/phpstorm/','extensions' => ['Laravel Plugin']]],
        ];

        return $map[$stack['primary']] ?? [
            'primary' => ['name' => 'VS Code', 'reason' => 'Universal editor', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['ESLint', 'Prettier']],
        ];
    }

    private function getPrerequisites(array $stack): array
    {
        $map = [
            'java'   => ['software' => [['name' => 'Java JDK 17+', 'download_url' => 'https://adoptium.net/', 'purpose' => 'Java runtime'], ['name' => 'Maven', 'download_url' => 'https://maven.apache.org/', 'purpose' => 'Build tool']], 'knowledge' => ['Basic Java']],
            'python' => ['software' => [['name' => 'Python 3.10+', 'download_url' => 'https://www.python.org/', 'purpose' => 'Python interpreter']], 'knowledge' => ['Basic Python']],
            'node'   => ['software' => [['name' => 'Node.js 18+',  'download_url' => 'https://nodejs.org/', 'purpose' => 'JS runtime']], 'knowledge' => ['JavaScript ES6+']],
            'react'  => ['software' => [['name' => 'Node.js 18+',  'download_url' => 'https://nodejs.org/', 'purpose' => 'JS runtime']], 'knowledge' => ['React basics', 'JSX']],
            'php'    => ['software' => [['name' => 'PHP 8.2+', 'download_url' => 'https://www.php.net/', 'purpose' => 'PHP runtime'], ['name' => 'Composer', 'download_url' => 'https://getcomposer.org/', 'purpose' => 'Package manager']], 'knowledge' => ['Basic PHP']],
        ];

        return $map[$stack['primary']] ?? ['software' => [], 'knowledge' => ['Basic programming']];
    }

    private function getSetupGuide(array $stack): array
    {
        $map = [
            'java'   => ['steps' => [['step' => 1, 'title' => 'Build',           'commands' => ['mvn clean install'],         'explanation' => 'Download deps and compile'],        ['step' => 2, 'title' => 'Run', 'commands' => ['mvn spring-boot:run'], 'explanation' => 'Start the server']]],
            'python' => ['steps' => [['step' => 1, 'title' => 'Create venv',     'commands' => ['python -m venv venv'],        'explanation' => 'Isolated environment'],             ['step' => 2, 'title' => 'Install', 'commands' => ['pip install -r requirements.txt'], 'explanation' => 'Install packages']]],
            'node'   => ['steps' => [['step' => 1, 'title' => 'Install',         'commands' => ['npm install'],               'explanation' => 'Install npm packages']]],
            'react'  => ['steps' => [['step' => 1, 'title' => 'Install',         'commands' => ['npm install'],               'explanation' => 'Install React and deps']]],
            'php'    => ['steps' => [['step' => 1, 'title' => 'Install Composer', 'commands' => ['composer install'],          'explanation' => 'Install PHP packages']]],
        ];

        return $map[$stack['primary']] ?? ['steps' => []];
    }

    private function getRunGuide(array $stack): array
    {
        $map = [
            'java'   => ['development' => ['commands' => ['mvn spring-boot:run'],         'explanation' => 'Start Spring Boot dev server',   'access_url' => 'http://localhost:8080']],
            'python' => ['development' => ['commands' => ['python main.py'],              'explanation' => 'Start Flask development server',  'access_url' => 'http://localhost:5000']],
            'node'   => ['development' => ['commands' => ['npm run dev'],                'explanation' => 'Start with nodemon auto-reload',   'access_url' => 'http://localhost:3000']],
            'react'  => ['development' => ['commands' => ['npm run dev'],                'explanation' => 'Start Vite development server',    'access_url' => 'http://localhost:5173']],
            'php'    => ['development' => ['commands' => ['php -S localhost:8000 -t public'], 'explanation' => 'PHP built-in web server',     'access_url' => 'http://localhost:8000']],
        ];

        return $map[$stack['primary']] ?? ['development' => ['commands' => ['See README.md'], 'explanation' => 'Check the README for instructions']];
    }
}
