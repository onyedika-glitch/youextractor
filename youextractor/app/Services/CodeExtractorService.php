<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CodeExtractorService
{
    /**
     * Programming language extensions mapping
     */
    private array $languageExtensions = [
        'javascript' => 'js',
        'typescript' => 'ts',
        'python' => 'py',
        'php' => 'php',
        'java' => 'java',
        'csharp' => 'cs',
        'c#' => 'cs',
        'cpp' => 'cpp',
        'c++' => 'cpp',
        'c' => 'c',
        'ruby' => 'rb',
        'go' => 'go',
        'rust' => 'rs',
        'swift' => 'swift',
        'kotlin' => 'kt',
        'html' => 'html',
        'css' => 'css',
        'scss' => 'scss',
        'sql' => 'sql',
        'bash' => 'sh',
        'shell' => 'sh',
        'json' => 'json',
        'xml' => 'xml',
        'yaml' => 'yml',
        'markdown' => 'md',
        'jsx' => 'jsx',
        'tsx' => 'tsx',
        'vue' => 'vue',
        'svelte' => 'svelte',
    ];

    /**
     * Stack/framework detection patterns
     */
    private array $stackPatterns = [
        'react' => ['react', 'useState', 'useEffect', 'jsx', 'component'],
        'vue' => ['vue', 'v-if', 'v-for', 'v-model', 'computed'],
        'angular' => ['angular', '@Component', 'ngOnInit', '@Injectable'],
        'nextjs' => ['next', 'getServerSideProps', 'getStaticProps', 'next/'],
        'express' => ['express', 'app.get', 'app.post', 'req, res', 'router'],
        'django' => ['django', 'views.py', 'models.py', 'urls.py'],
        'flask' => ['flask', '@app.route', 'render_template'],
        'laravel' => ['laravel', 'artisan', 'eloquent', 'blade'],
        'nodejs' => ['require(', 'module.exports', 'npm', 'node'],
        'spring' => ['@SpringBootApplication', '@RestController', '@Autowired'],
        'dotnet' => ['using System', 'namespace', 'public class', 'async Task'],
        'tailwind' => ['tailwind', 'className=', 'bg-', 'text-', 'flex'],
        'bootstrap' => ['bootstrap', 'btn-primary', 'container', 'row'],
    ];

    /**
     * Extract code from video transcript using AI
     */
    public function extractCodeFromTranscript(string $title, string $transcript): array
    {
        $openaiKey = env('OPENAI_API_KEY');
        
        if (empty($openaiKey) || strlen($openaiKey) < 20) {
            return $this->extractCodeWithPatterns($transcript);
        }

        try {
            $response = Http::timeout(120)
                ->withHeaders([
                    'Authorization' => "Bearer {$openaiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt(),
                        ],
                        [
                            'role' => 'user',
                            'content' => "Video Title: {$title}\n\nTranscript (if available):\n{$transcript}\n\nIMPORTANT: Generate a COMPLETE project structure with all necessary files based on the video title, even if transcript is limited.",
                        ],
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 8000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseAIResponse($content);
            }

            Log::warning('OpenAI code extraction failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Code extraction error: ' . $e->getMessage());
        }

        return $this->extractCodeWithPatterns($transcript);
    }

    /**
     * Get system prompt for AI code extraction
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
You are an expert code extractor for programming tutorial videos. Your task is to generate a COMPLETE, WORKING project structure based on the video title and any available transcript.

IMPORTANT: Even if the transcript is limited or unavailable, you MUST generate a complete project structure based on the video title. Analyze the title to understand:
1. The main technology stack (React, Spring Boot, Django, etc.)
2. The type of application (e-commerce, blog, management system, etc.)
3. The architecture pattern (microservices, monolithic, etc.)
4. The deployment target (AWS, Docker, Kubernetes, etc.)

Generate ALL essential files for a production-ready project including:
- Main application files
- Controllers/Routes
- Models/Entities
- Services/Business Logic
- Configuration files
- Database schemas/migrations
- Docker/deployment files
- README with setup instructions

Respond ONLY with valid JSON in this exact format:
{
    "stack": {
        "primary": "java",
        "languages": ["java", "yaml", "sql"],
        "frameworks": ["spring boot", "spring cloud", "docker"],
        "description": "Java Spring Boot microservices with AWS deployment"
    },
    "files": [
        {
            "filename": "Application.java",
            "language": "java",
            "path": "src/main/java/com/example/Application.java",
            "description": "Main Spring Boot application entry point",
            "code": "package com.example;..."
        }
    ],
    "setup_instructions": "mvn clean install\\njava -jar target/app.jar",
    "dependencies": {
        "npm": [],
        "pip": [],
        "maven": ["spring-boot-starter-web", "spring-boot-starter-data-jpa"]
    }
}

Generate at least 8-15 files for a complete project. Include realistic, working code that follows best practices.
PROMPT;
    }

    /**
     * Parse AI response into structured data
     */
    private function parseAIResponse(string $content): array
    {
        // Try to extract JSON from the response
        $content = trim($content);
        
        // Remove markdown code blocks if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)\s*```/', $content, $matches)) {
            $content = $matches[1];
        }

        try {
            $data = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                return [
                    'stack' => $data['stack'] ?? null,
                    'files' => $data['files'] ?? [],
                    'setup_instructions' => $data['setup_instructions'] ?? '',
                    'dependencies' => $data['dependencies'] ?? [],
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse AI response: ' . $e->getMessage());
        }

        return ['stack' => null, 'files' => [], 'setup_instructions' => '', 'dependencies' => []];
    }

    /**
     * Extract code using pattern matching (fallback)
     */
    private function extractCodeWithPatterns(string $text): array
    {
        $files = [];
        $detectedLanguages = [];
        
        // Pattern for code blocks
        $patterns = [
            // Markdown code blocks
            '/```(\w+)?\s*\n([\s\S]*?)\n```/' => 'markdown',
            // Function definitions
            '/(?:function|def|public|private|const)\s+\w+\s*\([^)]*\)\s*\{[^}]+\}/' => 'function',
            // Import statements
            '/(?:import|from|require|using)\s+[\'"][^"\']+[\'"]/' => 'import',
        ];

        foreach ($patterns as $pattern => $type) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[0] as $i => $match) {
                    $language = $matches[1][$i] ?? $this->detectLanguage($match);
                    $code = $matches[2][$i] ?? $match;
                    
                    if (strlen(trim($code)) > 10) {
                        $files[] = [
                            'filename' => 'snippet_' . (count($files) + 1) . '.' . $this->getExtension($language),
                            'language' => $language,
                            'path' => 'snippets/snippet_' . (count($files) + 1) . '.' . $this->getExtension($language),
                            'description' => 'Code snippet extracted from video',
                            'code' => trim($code),
                        ];
                        $detectedLanguages[] = $language;
                    }
                }
            }
        }

        $stack = null;
        if (!empty($detectedLanguages)) {
            $stack = [
                'primary' => $detectedLanguages[0],
                'languages' => array_unique($detectedLanguages),
                'frameworks' => $this->detectFrameworks($text),
                'description' => 'Detected from video content',
            ];
        }

        return [
            'stack' => $stack,
            'files' => $files,
            'setup_instructions' => '',
            'dependencies' => [],
        ];
    }

    /**
     * Detect programming language from code
     */
    private function detectLanguage(string $code): string
    {
        $indicators = [
            'javascript' => ['const ', 'let ', 'var ', 'function', '=>', 'console.log'],
            'typescript' => ['interface ', ': string', ': number', ': boolean', '<T>'],
            'python' => ['def ', 'import ', 'from ', 'print(', 'if __name__'],
            'php' => ['<?php', '<?=', '$_', 'echo ', 'function '],
            'java' => ['public class', 'public static', 'System.out'],
            'csharp' => ['using System', 'namespace ', 'public class'],
            'html' => ['<html', '<div', '<span', '<!DOCTYPE'],
            'css' => ['{', '}', 'color:', 'background:', 'margin:'],
            'sql' => ['SELECT', 'FROM', 'WHERE', 'INSERT', 'UPDATE'],
        ];

        foreach ($indicators as $lang => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($code, $keyword) !== false) {
                    return $lang;
                }
            }
        }

        return 'text';
    }

    /**
     * Detect frameworks from text
     */
    private function detectFrameworks(string $text): array
    {
        $detected = [];
        $textLower = strtolower($text);

        foreach ($this->stackPatterns as $framework => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($textLower, $keyword) !== false) {
                    $detected[] = $framework;
                    break;
                }
            }
        }

        return array_unique($detected);
    }

    /**
     * Get file extension for language
     */
    private function getExtension(string $language): string
    {
        return $this->languageExtensions[strtolower($language)] ?? 'txt';
    }

    /**
     * Generate downloadable ZIP file with code
     */
    public function generateZipFile(string $videoId, array $codeData): ?string
    {
        $zipPath = storage_path("app/downloads/{$videoId}.zip");
        $zipDir = dirname($zipPath);

        if (!is_dir($zipDir)) {
            mkdir($zipDir, 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            Log::error("Cannot create ZIP file: {$zipPath}");
            return null;
        }

        // Add README
        $readme = $this->generateReadme($codeData);
        $zip->addFromString('README.md', $readme);

        // Add code files
        foreach ($codeData['files'] ?? [] as $file) {
            $path = $file['path'] ?? $file['filename'];
            $code = $file['code'] ?? '';
            
            // Add comment header
            $header = $this->generateFileHeader($file);
            $zip->addFromString($path, $header . $code);
        }

        // Add setup script if available
        if (!empty($codeData['setup_instructions'])) {
            $zip->addFromString('SETUP.md', "# Setup Instructions\n\n```bash\n{$codeData['setup_instructions']}\n```");
        }

        // Add package.json or requirements.txt if dependencies exist
        $this->addDependencyFiles($zip, $codeData['dependencies'] ?? []);

        $zip->close();

        return $zipPath;
    }

    /**
     * Generate README content
     */
    private function generateReadme(array $codeData): string
    {
        $stack = $codeData['stack'] ?? null;
        $files = $codeData['files'] ?? [];

        $readme = "# Code Extracted from YouTube Video\n\n";
        $readme .= "Generated by YouTube Code Extractor\n\n";

        if ($stack) {
            $readme .= "## Tech Stack\n\n";
            $readme .= "- **Primary**: {$stack['primary']}\n";
            if (!empty($stack['languages'])) {
                $readme .= "- **Languages**: " . implode(', ', $stack['languages']) . "\n";
            }
            if (!empty($stack['frameworks'])) {
                $readme .= "- **Frameworks**: " . implode(', ', $stack['frameworks']) . "\n";
            }
            if (!empty($stack['description'])) {
                $readme .= "- **Description**: {$stack['description']}\n";
            }
            $readme .= "\n";
        }

        if (!empty($files)) {
            $readme .= "## Files\n\n";
            foreach ($files as $file) {
                $readme .= "### `{$file['path']}`\n";
                $readme .= "{$file['description']}\n\n";
            }
        }

        if (!empty($codeData['setup_instructions'])) {
            $readme .= "## Setup\n\n";
            $readme .= "```bash\n{$codeData['setup_instructions']}\n```\n\n";
        }

        $readme .= "---\n";
        $readme .= "Extracted on: " . date('Y-m-d H:i:s') . "\n";

        return $readme;
    }

    /**
     * Generate file header comment
     */
    private function generateFileHeader(array $file): string
    {
        $lang = strtolower($file['language'] ?? 'text');
        $desc = $file['description'] ?? '';
        
        $commentStyles = [
            'javascript' => "/**\n * {$desc}\n * Extracted from YouTube tutorial\n */\n\n",
            'typescript' => "/**\n * {$desc}\n * Extracted from YouTube tutorial\n */\n\n",
            'python' => "\"\"\"\n{$desc}\nExtracted from YouTube tutorial\n\"\"\"\n\n",
            'php' => "<?php\n/**\n * {$desc}\n * Extracted from YouTube tutorial\n */\n\n",
            'html' => "<!-- {$desc} - Extracted from YouTube tutorial -->\n\n",
            'css' => "/* {$desc} - Extracted from YouTube tutorial */\n\n",
        ];

        return $commentStyles[$lang] ?? "// {$desc}\n// Extracted from YouTube tutorial\n\n";
    }

    /**
     * Add dependency files to ZIP
     */
    private function addDependencyFiles(ZipArchive $zip, array $dependencies): void
    {
        // NPM dependencies
        if (!empty($dependencies['npm'])) {
            $packageJson = json_encode([
                'name' => 'youtube-extracted-code',
                'version' => '1.0.0',
                'dependencies' => array_fill_keys($dependencies['npm'], '*'),
            ], JSON_PRETTY_PRINT);
            $zip->addFromString('package.json', $packageJson);
        }

        // Python dependencies
        if (!empty($dependencies['pip'])) {
            $requirements = implode("\n", $dependencies['pip']);
            $zip->addFromString('requirements.txt', $requirements);
        }

        // Composer dependencies
        if (!empty($dependencies['composer'])) {
            $composerJson = json_encode([
                'name' => 'youtube/extracted-code',
                'require' => array_fill_keys($dependencies['composer'], '*'),
            ], JSON_PRETTY_PRINT);
            $zip->addFromString('composer.json', $composerJson);
        }
    }
}
