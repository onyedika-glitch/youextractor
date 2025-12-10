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
        // Try Gemini first (preferred)
        $geminiKey = env('GEMINI_API_KEY');
        $openaiKey = env('OPENAI_API_KEY');
        $aiProvider = env('AI_PROVIDER', 'gemini');
        
        // Use Gemini if key exists and provider is gemini
        if (!empty($geminiKey) && strlen($geminiKey) > 20) {
            Log::info('Using Gemini AI for extraction');
            $result = $this->extractWithGemini($title, $transcript, $geminiKey);
            if ($result !== null) {
                return $result;
            }
        }
        
        // Fallback to OpenAI
        if (!empty($openaiKey) && strlen($openaiKey) > 20) {
            Log::info('Using OpenAI for extraction');
            $result = $this->extractWithOpenAI($title, $transcript, $openaiKey);
            if ($result !== null) {
                return $result;
            }
        }
        
        Log::info('No AI API available - using fallback extraction');
        return $this->generateFallbackProject($title);
    }

    /**
     * Extract using Google Gemini AI
     */
    private function extractWithGemini(string $title, string $transcript, string $apiKey): ?array
    {
        try {
            $prompt = "Video Title: {$title}\n\nTranscript (if available):\n{$transcript}\n\nCRITICAL INSTRUCTIONS:\n1. Generate an EXTREMELY DETAILED tutorial_guide with a 5-8 paragraph overview\n2. Include 6-10 key_concepts with comprehensive explanations (3-5 sentences each)\n3. Create 10-20 complete code files with FULL working code\n4. Provide detailed setup_guide with 6-10 steps\n5. Make everything beginner-friendly and educational\n\nEven if transcript is limited, use the title to understand the project and generate comprehensive content.";
            
            // Try multiple Gemini models - gemini-2.5-flash works with Pro for Students
            $models = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash-latest'];
            
            foreach ($models as $model) {
                $response = Http::timeout(240)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $this->getSystemPrompt() . "\n\n" . $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.5,
                            'maxOutputTokens' => 16000,
                        ],
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                    if (!empty($content)) {
                        Log::info("Gemini extraction successful with model: {$model}");
                        return $this->parseAIResponse($content);
                    }
                }

                $errorBody = $response->body();
                
                // If quota exhausted, try next model
                if (str_contains($errorBody, 'RESOURCE_EXHAUSTED') || str_contains($errorBody, 'quota')) {
                    Log::warning("Gemini {$model} quota exhausted, trying next model...");
                    continue;
                }
                
                // Other error, log and try next model
                Log::warning("Gemini {$model} request failed: " . substr($errorBody, 0, 200));
            }
            
        } catch (\Exception $e) {
            Log::error('Gemini extraction error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Extract using OpenAI
     */
    private function extractWithOpenAI(string $title, string $transcript, string $apiKey): ?array
    {
        try {
            $userPrompt = "Video Title: {$title}\n\nTranscript (if available):\n{$transcript}\n\nCRITICAL INSTRUCTIONS:\n1. Generate an EXTREMELY DETAILED tutorial_guide with a 5-8 paragraph overview\n2. Include 6-10 key_concepts with comprehensive explanations (3-5 sentences each)\n3. Create 10-20 complete code files with FULL working code\n4. Provide detailed setup_guide with 6-10 steps\n5. Make everything beginner-friendly and educational\n\nEven if transcript is limited, use the title to understand the project and generate comprehensive content.";
            
            $response = Http::timeout(240)
                ->withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
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
                            'content' => $userPrompt,
                        ],
                    ],
                    'temperature' => 0.5,
                    'max_tokens' => 16000,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                if (!empty($content)) {
                    Log::info('OpenAI extraction successful');
                    return $this->parseAIResponse($content);
                }
            }

            $errorBody = $response->body();
            if (str_contains($errorBody, 'insufficient_quota')) {
                Log::warning('OpenAI quota exceeded');
                return null;
            }

            Log::warning('OpenAI request failed: ' . $errorBody);
        } catch (\Exception $e) {
            Log::error('OpenAI extraction error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get system prompt for AI code extraction
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
You are an EXPERT programming tutor creating the ULTIMATE learning resource from a YouTube tutorial video. Your goal is to create content so comprehensive that a complete beginner could follow along and understand EVERYTHING.

## YOUR MISSION:
Transform this video into a complete, self-contained learning package with:
1. EXTREMELY DETAILED tutorial guide (like a blog post/course lesson)
2. COMPLETE working project with 10-20 files
3. Step-by-step explanations for EVERY concept
4. Professional setup and deployment guides

## CRITICAL REQUIREMENTS:

### TUTORIAL GUIDE (MOST IMPORTANT):
The tutorial_guide MUST be like a full blog post or course lesson:
- overview: Write 5-8 paragraphs explaining:
  * What problem this project solves
  * Why someone would build this
  * The technologies used and WHY each was chosen
  * The architecture/design patterns being implemented
  * What makes this approach industry-standard
  * Real-world applications of these concepts

- key_concepts: Provide 6-10 concepts, each with 3-5 sentence explanations:
  * Explain what the concept is
  * Why it's important
  * How it's used in this project
  * Common mistakes to avoid

- learning_outcomes: List 8-12 specific skills the learner will gain

### CODE FILES (COMPLETE PROJECT):
Generate 10-20 files that form a COMPLETE, RUNNABLE project:
- Every file must have FULL, WORKING code (no placeholders)
- Include configuration files (package.json, pom.xml, requirements.txt, etc.)
- Include environment examples (.env.example)
- Include Docker files if relevant
- Include database schemas/migrations
- Include tests if appropriate
- Each file's description should explain:
  * What this file does
  * Why it's needed
  * Key parts of the code

### SETUP GUIDE:
Provide 6-10 detailed steps where each step includes:
- Clear title
- Multiple commands if needed
- 3-5 sentence explanation of what's happening and why

### RUN GUIDE:
Include development, production, and docker options where applicable, each with detailed explanations.

## RESPONSE FORMAT (JSON ONLY):
{
    "stack": {
        "primary": "javascript",
        "languages": ["javascript", "typescript", "html", "css", "sql"],
        "frameworks": ["react", "nodejs", "express", "postgresql"],
        "description": "Full-stack React application with Node.js/Express backend and PostgreSQL database"
    },
    "tutorial_guide": {
        "overview": "WRITE 5-8 DETAILED PARAGRAPHS HERE. Start with: 'In this comprehensive tutorial, we will build...' Explain the problem, solution, architecture, technologies, and real-world relevance. Make it engaging and educational. Each paragraph should be 3-5 sentences.",
        "key_concepts": [
            {
                "concept": "Component-Based Architecture",
                "explanation": "Component-based architecture is a design pattern where the UI is broken down into independent, reusable pieces called components. Each component manages its own state and logic, making the codebase more maintainable and testable. In React, components can be functional or class-based, with functional components being the modern standard. This project uses functional components with hooks to manage state and side effects. Understanding component composition is crucial for building scalable React applications."
            },
            {
                "concept": "RESTful API Design",
                "explanation": "REST (Representational State Transfer) is an architectural style for designing networked applications. RESTful APIs use HTTP methods (GET, POST, PUT, DELETE) to perform CRUD operations on resources. In this project, we follow REST conventions to create predictable, stateless endpoints. Each endpoint returns JSON data and uses appropriate status codes. This approach makes our API easy to understand and consume by any client application."
            }
        ],
        "learning_outcomes": [
            "Build a complete full-stack application from scratch",
            "Implement user authentication with JWT tokens",
            "Design and consume RESTful APIs",
            "Manage application state with React hooks",
            "Connect to and query a PostgreSQL database",
            "Handle errors gracefully on both frontend and backend",
            "Deploy applications to production environments",
            "Write clean, maintainable code following best practices"
        ]
    },
    "ide_recommendations": {
        "primary": {
            "name": "Visual Studio Code",
            "reason": "VS Code offers excellent JavaScript/TypeScript support with IntelliSense, debugging, and integrated terminal. Its vast extension marketplace provides tools for React, Node.js, and database management all in one place.",
            "download_url": "https://code.visualstudio.com/",
            "extensions": ["ES7+ React/Redux/React-Native snippets", "Prettier - Code formatter", "ESLint", "Thunder Client", "PostgreSQL"]
        },
        "alternatives": [
            {
                "name": "WebStorm",
                "reason": "JetBrains WebStorm provides superior code intelligence and refactoring tools out of the box, ideal for larger projects",
                "download_url": "https://www.jetbrains.com/webstorm/",
                "extensions": []
            }
        ]
    },
    "prerequisites": {
        "software": [
            {"name": "Node.js 18+", "download_url": "https://nodejs.org/", "purpose": "JavaScript runtime for running the backend server and build tools"},
            {"name": "PostgreSQL 14+", "download_url": "https://www.postgresql.org/download/", "purpose": "Relational database for storing application data"},
            {"name": "Git", "download_url": "https://git-scm.com/", "purpose": "Version control for tracking code changes"}
        ],
        "knowledge": [
            "Basic JavaScript/ES6+ syntax (variables, functions, async/await)",
            "Understanding of HTML and CSS",
            "Familiarity with command line/terminal",
            "Basic understanding of databases and SQL"
        ],
        "accounts": []
    },
    "setup_guide": {
        "steps": [
            {
                "step": 1,
                "title": "Verify Prerequisites",
                "commands": ["node --version", "npm --version", "psql --version"],
                "explanation": "Before starting, ensure you have Node.js, npm, and PostgreSQL installed. Node.js should be version 18 or higher. If any command fails, download and install the missing software from the prerequisites links above."
            },
            {
                "step": 2,
                "title": "Extract and Navigate to Project",
                "commands": ["unzip youtube-code-extractor.zip", "cd project-name"],
                "explanation": "After downloading, extract the ZIP file to your preferred location. Open your terminal and navigate to the project directory. All subsequent commands should be run from this directory."
            },
            {
                "step": 3,
                "title": "Install Dependencies",
                "commands": ["npm install"],
                "explanation": "This command reads the package.json file and downloads all required packages from npm. This includes React, Express, database drivers, and development tools. The process may take a few minutes depending on your internet connection."
            },
            {
                "step": 4,
                "title": "Configure Environment Variables",
                "commands": ["cp .env.example .env"],
                "explanation": "Copy the example environment file to create your local configuration. Open the .env file and update the database connection string, API keys, and other settings for your local environment. Never commit this file to version control."
            },
            {
                "step": 5,
                "title": "Setup Database",
                "commands": ["createdb myapp_db", "npm run migrate"],
                "explanation": "Create a new PostgreSQL database and run the migrations to set up your tables. The migration files define the database schema and will create all necessary tables, indexes, and relationships."
            }
        ]
    },
    "run_guide": {
        "development": {
            "commands": ["npm run dev"],
            "explanation": "This starts both the frontend and backend in development mode with hot-reloading enabled. Any changes you make to the code will automatically refresh in the browser. The backend API runs on port 5000 and the React frontend on port 3000.",
            "access_url": "http://localhost:3000"
        },
        "production": {
            "commands": ["npm run build", "npm start"],
            "explanation": "First, build the optimized production bundle which minifies and bundles all assets. Then start the production server which serves the static files and handles API requests efficiently."
        },
        "docker": {
            "commands": ["docker-compose up --build"],
            "explanation": "Docker Compose builds the application image and starts all services (app, database) in isolated containers. This ensures consistent environments across development, testing, and production."
        }
    },
    "files": [
        {
            "filename": "package.json",
            "language": "json",
            "path": "package.json",
            "description": "The package.json file is the heart of any Node.js project. It defines the project metadata, lists all dependencies (both production and development), and contains scripts for running, building, and testing the application. This file is used by npm to manage packages.",
            "code": "FULL JSON CONTENT HERE"
        }
    ],
    "setup_instructions": "npm install && npm run dev",
    "dependencies": {
        "npm": ["react", "express", "pg"],
        "pip": [],
        "maven": []
    }
}

## IMPORTANT RULES:
1. Generate COMPLETE, WORKING code - no "// TODO" or placeholders
2. Make the tutorial_guide.overview at least 5 full paragraphs
3. Include at least 6 key_concepts with detailed explanations
4. Create 10-20 files for a complete project
5. Every explanation should be educational and beginner-friendly
6. Include ALL configuration files needed to run the project
7. Respond with VALID JSON ONLY - no markdown, no explanation outside JSON
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
                    'tutorial_guide' => $data['tutorial_guide'] ?? null,
                    'ide_recommendations' => $data['ide_recommendations'] ?? null,
                    'prerequisites' => $data['prerequisites'] ?? null,
                    'setup_guide' => $data['setup_guide'] ?? null,
                    'run_guide' => $data['run_guide'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Failed to parse AI response: ' . $e->getMessage());
        }

        return ['stack' => null, 'files' => [], 'setup_instructions' => '', 'dependencies' => [], 'tutorial_guide' => null, 'ide_recommendations' => null, 'prerequisites' => null, 'setup_guide' => null, 'run_guide' => null];
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

    /**
     * Generate a fallback project when AI is unavailable
     */
    private function generateFallbackProject(string $title): array
    {
        $stack = $this->detectStackFromTitle($title);
        $files = $this->generateBasicFiles($stack, $title);
        
        return [
            'stack' => $stack,
            'files' => $files,
            'setup_instructions' => $this->getBasicSetupInstructions($stack),
            'dependencies' => $this->getBasicDependencies($stack),
            'tutorial_guide' => $this->getBasicTutorialGuide($title, $stack),
            'ide_recommendations' => $this->getBasicIDERecommendations($stack),
            'prerequisites' => $this->getBasicPrerequisites($stack),
            'setup_guide' => $this->getBasicSetupGuide($stack),
            'run_guide' => $this->getBasicRunGuide($stack),
        ];
    }

    /**
     * Detect stack from video title
     */
    private function detectStackFromTitle(string $title): array
    {
        $titleLower = strtolower($title);
        
        $stacks = [
            'react' => ['react', 'reactjs', 'next.js', 'nextjs'],
            'vue' => ['vue', 'vuejs', 'nuxt'],
            'angular' => ['angular', 'angularjs'],
            'node' => ['node', 'nodejs', 'express', 'expressjs'],
            'python' => ['python', 'django', 'flask', 'fastapi'],
            'java' => ['java', 'spring', 'springboot', 'spring boot'],
            'php' => ['php', 'laravel', 'symfony'],
            'typescript' => ['typescript', 'ts'],
            'go' => ['golang', 'go '],
            'rust' => ['rust'],
            'csharp' => ['c#', 'csharp', '.net', 'dotnet', 'asp.net'],
        ];

        foreach ($stacks as $primary => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($titleLower, $keyword)) {
                    return [
                        'primary' => $primary,
                        'languages' => [$primary],
                        'frameworks' => $this->detectFrameworksFromTitle($titleLower),
                        'description' => "Detected from video title: {$title}",
                    ];
                }
            }
        }

        return [
            'primary' => 'javascript',
            'languages' => ['javascript'],
            'frameworks' => [],
            'description' => 'Default stack - JavaScript',
        ];
    }

    /**
     * Detect frameworks from title
     */
    private function detectFrameworksFromTitle(string $title): array
    {
        $frameworks = [];
        $mapping = [
            'spring boot' => 'Spring Boot',
            'spring' => 'Spring',
            'react' => 'React',
            'next.js' => 'Next.js',
            'nextjs' => 'Next.js',
            'vue' => 'Vue.js',
            'nuxt' => 'Nuxt.js',
            'angular' => 'Angular',
            'express' => 'Express.js',
            'django' => 'Django',
            'flask' => 'Flask',
            'fastapi' => 'FastAPI',
            'laravel' => 'Laravel',
            'docker' => 'Docker',
            'kubernetes' => 'Kubernetes',
            'aws' => 'AWS',
            'microservices' => 'Microservices',
        ];

        foreach ($mapping as $keyword => $framework) {
            if (str_contains($title, $keyword)) {
                $frameworks[] = $framework;
            }
        }

        return array_unique($frameworks);
    }

    /**
     * Generate basic files for fallback
     */
    private function generateBasicFiles(array $stack, string $title): array
    {
        $primary = $stack['primary'];
        
        switch ($primary) {
            case 'java':
                return $this->generateJavaFiles($title);
            case 'python':
                return $this->generatePythonFiles($title);
            case 'node':
            case 'javascript':
                return $this->generateNodeFiles($title);
            case 'react':
                return $this->generateReactFiles($title);
            case 'php':
                return $this->generatePhpFiles($title);
            default:
                return $this->generateGenericFiles($title, $primary);
        }
    }

    private function generateJavaFiles(string $title): array
    {
        return [
            ['filename' => 'Application.java', 'language' => 'java', 'path' => 'src/main/java/com/example/Application.java', 'description' => 'Main application entry point', 'code' => "package com.example;\n\nimport org.springframework.boot.SpringApplication;\nimport org.springframework.boot.autoconfigure.SpringBootApplication;\n\n@SpringBootApplication\npublic class Application {\n    public static void main(String[] args) {\n        SpringApplication.run(Application.class, args);\n    }\n}"],
            ['filename' => 'Controller.java', 'language' => 'java', 'path' => 'src/main/java/com/example/controller/MainController.java', 'description' => 'Main REST controller', 'code' => "package com.example.controller;\n\nimport org.springframework.web.bind.annotation.*;\n\n@RestController\n@RequestMapping(\"/api\")\npublic class MainController {\n    @GetMapping(\"/health\")\n    public String health() {\n        return \"OK\";\n    }\n}"],
            ['filename' => 'application.yml', 'language' => 'yaml', 'path' => 'src/main/resources/application.yml', 'description' => 'Application configuration', 'code' => "server:\n  port: 8080\n\nspring:\n  application:\n    name: my-app"],
            ['filename' => 'pom.xml', 'language' => 'xml', 'path' => 'pom.xml', 'description' => 'Maven configuration', 'code' => "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<project>\n    <modelVersion>4.0.0</modelVersion>\n    <groupId>com.example</groupId>\n    <artifactId>my-app</artifactId>\n    <version>1.0.0</version>\n    <parent>\n        <groupId>org.springframework.boot</groupId>\n        <artifactId>spring-boot-starter-parent</artifactId>\n        <version>3.2.0</version>\n    </parent>\n    <dependencies>\n        <dependency>\n            <groupId>org.springframework.boot</groupId>\n            <artifactId>spring-boot-starter-web</artifactId>\n        </dependency>\n    </dependencies>\n</project>"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Project documentation', 'code' => "# {$title}\n\n## Getting Started\n\n1. Make sure you have Java 17+ installed\n2. Run `mvn clean install`\n3. Run `mvn spring-boot:run`\n4. Access at http://localhost:8080"],
        ];
    }

    private function generatePythonFiles(string $title): array
    {
        return [
            ['filename' => 'main.py', 'language' => 'python', 'path' => 'main.py', 'description' => 'Main application entry point', 'code' => "from flask import Flask, jsonify\n\napp = Flask(__name__)\n\n@app.route('/api/health')\ndef health():\n    return jsonify({'status': 'OK'})\n\nif __name__ == '__main__':\n    app.run(debug=True, port=5000)"],
            ['filename' => 'requirements.txt', 'language' => 'text', 'path' => 'requirements.txt', 'description' => 'Python dependencies', 'code' => "flask>=2.0.0\nrequests>=2.25.0"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Project documentation', 'code' => "# {$title}\n\n## Getting Started\n\n1. Create virtual environment: `python -m venv venv`\n2. Activate: `source venv/bin/activate` (or `venv\\Scripts\\activate` on Windows)\n3. Install: `pip install -r requirements.txt`\n4. Run: `python main.py`"],
        ];
    }

    private function generateNodeFiles(string $title): array
    {
        return [
            ['filename' => 'index.js', 'language' => 'javascript', 'path' => 'src/index.js', 'description' => 'Main application entry point', 'code' => "const express = require('express');\nconst app = express();\nconst PORT = process.env.PORT || 3000;\n\napp.use(express.json());\n\napp.get('/api/health', (req, res) => {\n    res.json({ status: 'OK' });\n});\n\napp.listen(PORT, () => {\n    console.log(`Server running on port \${PORT}`);\n});"],
            ['filename' => 'package.json', 'language' => 'json', 'path' => 'package.json', 'description' => 'Node.js dependencies', 'code' => "{\n  \"name\": \"my-app\",\n  \"version\": \"1.0.0\",\n  \"main\": \"src/index.js\",\n  \"scripts\": {\n    \"start\": \"node src/index.js\",\n    \"dev\": \"nodemon src/index.js\"\n  },\n  \"dependencies\": {\n    \"express\": \"^4.18.0\"\n  },\n  \"devDependencies\": {\n    \"nodemon\": \"^3.0.0\"\n  }\n}"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Project documentation', 'code' => "# {$title}\n\n## Getting Started\n\n1. Install Node.js 18+\n2. Run `npm install`\n3. Run `npm run dev` for development\n4. Access at http://localhost:3000"],
        ];
    }

    private function generateReactFiles(string $title): array
    {
        return [
            ['filename' => 'App.jsx', 'language' => 'jsx', 'path' => 'src/App.jsx', 'description' => 'Main React component', 'code' => "import React, { useState } from 'react';\nimport './App.css';\n\nfunction App() {\n  const [count, setCount] = useState(0);\n\n  return (\n    <div className=\"App\">\n      <h1>Hello React!</h1>\n      <p>Count: {count}</p>\n      <button onClick={() => setCount(count + 1)}>Increment</button>\n    </div>\n  );\n}\n\nexport default App;"],
            ['filename' => 'index.jsx', 'language' => 'jsx', 'path' => 'src/index.jsx', 'description' => 'React entry point', 'code' => "import React from 'react';\nimport ReactDOM from 'react-dom/client';\nimport App from './App';\nimport './index.css';\n\nReactDOM.createRoot(document.getElementById('root')).render(\n  <React.StrictMode>\n    <App />\n  </React.StrictMode>\n);"],
            ['filename' => 'package.json', 'language' => 'json', 'path' => 'package.json', 'description' => 'Dependencies', 'code' => "{\n  \"name\": \"react-app\",\n  \"version\": \"1.0.0\",\n  \"scripts\": {\n    \"dev\": \"vite\",\n    \"build\": \"vite build\"\n  },\n  \"dependencies\": {\n    \"react\": \"^18.2.0\",\n    \"react-dom\": \"^18.2.0\"\n  },\n  \"devDependencies\": {\n    \"vite\": \"^5.0.0\",\n    \"@vitejs/plugin-react\": \"^4.0.0\"\n  }\n}"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Documentation', 'code' => "# {$title}\n\n## Getting Started\n\n1. Install Node.js 18+\n2. Run `npm install`\n3. Run `npm run dev`\n4. Access at http://localhost:5173"],
        ];
    }

    private function generatePhpFiles(string $title): array
    {
        return [
            ['filename' => 'index.php', 'language' => 'php', 'path' => 'public/index.php', 'description' => 'Entry point', 'code' => "<?php\n\nrequire_once __DIR__ . '/../vendor/autoload.php';\n\n\$app = new App\\Application();\n\$app->run();"],
            ['filename' => 'Application.php', 'language' => 'php', 'path' => 'src/Application.php', 'description' => 'Main application class', 'code' => "<?php\n\nnamespace App;\n\nclass Application {\n    public function run(): void {\n        echo json_encode(['status' => 'OK']);\n    }\n}"],
            ['filename' => 'composer.json', 'language' => 'json', 'path' => 'composer.json', 'description' => 'Dependencies', 'code' => "{\n  \"name\": \"my/app\",\n  \"autoload\": {\n    \"psr-4\": {\n      \"App\\\\\": \"src/\"\n    }\n  }\n}"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Documentation', 'code' => "# {$title}\n\n## Getting Started\n\n1. Install PHP 8.2+\n2. Run `composer install`\n3. Run `php -S localhost:8000 -t public`"],
        ];
    }

    private function generateGenericFiles(string $title, string $language): array
    {
        return [
            ['filename' => 'main.' . $this->getExtension($language), 'language' => $language, 'path' => 'src/main.' . $this->getExtension($language), 'description' => 'Main file', 'code' => "// Main application file\n// Generated from: {$title}"],
            ['filename' => 'README.md', 'language' => 'markdown', 'path' => 'README.md', 'description' => 'Documentation', 'code' => "# {$title}\n\nProject extracted from YouTube tutorial."],
        ];
    }

    private function getBasicSetupInstructions(array $stack): string
    {
        $instructions = [
            'java' => "mvn clean install\nmvn spring-boot:run",
            'python' => "pip install -r requirements.txt\npython main.py",
            'node' => "npm install\nnpm run dev",
            'javascript' => "npm install\nnpm start",
            'react' => "npm install\nnpm run dev",
            'php' => "composer install\nphp -S localhost:8000 -t public",
        ];

        return $instructions[$stack['primary']] ?? "See README.md for setup instructions";
    }

    private function getBasicDependencies(array $stack): array
    {
        return [
            'npm' => in_array($stack['primary'], ['node', 'javascript', 'react']) ? ['express'] : [],
            'pip' => $stack['primary'] === 'python' ? ['flask', 'requests'] : [],
            'maven' => $stack['primary'] === 'java' ? ['spring-boot-starter-web'] : [],
        ];
    }

    private function getBasicTutorialGuide(string $title, array $stack): array
    {
        return [
            'overview' => "This project was extracted from the YouTube tutorial: \"{$title}\".\n\nThe detected technology stack is {$stack['primary']}. This guide provides basic setup instructions and code scaffolding to help you follow along with the tutorial.\n\nNote: For a complete tutorial experience with AI-generated explanations and comprehensive code, please ensure your OpenAI API key has available quota.",
            'key_concepts' => [
                ['concept' => ucfirst($stack['primary']) . ' Fundamentals', 'explanation' => "This tutorial covers core {$stack['primary']} concepts and best practices."],
                ['concept' => 'Project Structure', 'explanation' => 'The generated code follows standard project organization patterns for the detected tech stack.'],
            ],
            'learning_outcomes' => [
                "Understanding of {$stack['primary']} project setup",
                'Familiarity with common development patterns',
                'Ability to run and modify the project locally',
            ],
        ];
    }

    private function getBasicIDERecommendations(array $stack): array
    {
        $recommendations = [
            'java' => ['primary' => ['name' => 'IntelliJ IDEA', 'reason' => 'Best IDE for Java development with Spring Boot support', 'download_url' => 'https://www.jetbrains.com/idea/download/', 'extensions' => ['Spring Boot', 'Lombok']], 'alternatives' => [['name' => 'VS Code', 'reason' => 'Lightweight with Java Extension Pack', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['Extension Pack for Java']]]],
            'python' => ['primary' => ['name' => 'PyCharm', 'reason' => 'Best IDE for Python development', 'download_url' => 'https://www.jetbrains.com/pycharm/download/', 'extensions' => []], 'alternatives' => [['name' => 'VS Code', 'reason' => 'Lightweight with Python extension', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['Python', 'Pylance']]]],
            'node' => ['primary' => ['name' => 'VS Code', 'reason' => 'Best IDE for Node.js development', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['ESLint', 'Prettier']], 'alternatives' => [['name' => 'WebStorm', 'reason' => 'Full-featured IDE for JavaScript', 'download_url' => 'https://www.jetbrains.com/webstorm/', 'extensions' => []]]],
            'react' => ['primary' => ['name' => 'VS Code', 'reason' => 'Best IDE for React development', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['ES7+ React snippets', 'Prettier', 'ESLint']], 'alternatives' => [['name' => 'WebStorm', 'reason' => 'Full-featured IDE with React support', 'download_url' => 'https://www.jetbrains.com/webstorm/', 'extensions' => []]]],
            'php' => ['primary' => ['name' => 'PhpStorm', 'reason' => 'Best IDE for PHP development', 'download_url' => 'https://www.jetbrains.com/phpstorm/download/', 'extensions' => ['Laravel Plugin']], 'alternatives' => [['name' => 'VS Code', 'reason' => 'Lightweight with PHP extensions', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => ['PHP Intelephense']]]],
        ];

        return $recommendations[$stack['primary']] ?? ['primary' => ['name' => 'VS Code', 'reason' => 'Universal code editor', 'download_url' => 'https://code.visualstudio.com/', 'extensions' => []], 'alternatives' => []];
    }

    private function getBasicPrerequisites(array $stack): array
    {
        $prerequisites = [
            'java' => ['software' => [['name' => 'Java JDK 17+', 'download_url' => 'https://adoptium.net/', 'purpose' => 'Java runtime'], ['name' => 'Maven', 'download_url' => 'https://maven.apache.org/download.cgi', 'purpose' => 'Build tool']], 'knowledge' => ['Basic Java programming', 'Object-oriented concepts']],
            'python' => ['software' => [['name' => 'Python 3.10+', 'download_url' => 'https://www.python.org/downloads/', 'purpose' => 'Python interpreter'], ['name' => 'pip', 'download_url' => 'https://pip.pypa.io/', 'purpose' => 'Package manager']], 'knowledge' => ['Basic Python programming']],
            'node' => ['software' => [['name' => 'Node.js 18+', 'download_url' => 'https://nodejs.org/', 'purpose' => 'JavaScript runtime'], ['name' => 'npm', 'download_url' => 'https://www.npmjs.com/', 'purpose' => 'Package manager']], 'knowledge' => ['JavaScript fundamentals', 'Async programming']],
            'react' => ['software' => [['name' => 'Node.js 18+', 'download_url' => 'https://nodejs.org/', 'purpose' => 'JavaScript runtime']], 'knowledge' => ['JavaScript ES6+', 'React basics', 'JSX syntax']],
            'php' => ['software' => [['name' => 'PHP 8.2+', 'download_url' => 'https://www.php.net/downloads', 'purpose' => 'PHP runtime'], ['name' => 'Composer', 'download_url' => 'https://getcomposer.org/', 'purpose' => 'Dependency manager']], 'knowledge' => ['Basic PHP programming']],
        ];

        return $prerequisites[$stack['primary']] ?? ['software' => [], 'knowledge' => ['Basic programming concepts']];
    }

    private function getBasicSetupGuide(array $stack): array
    {
        $guides = [
            'java' => ['steps' => [['step' => 1, 'title' => 'Install Java', 'commands' => ['java -version'], 'explanation' => 'Verify Java JDK 17+ is installed'], ['step' => 2, 'title' => 'Install Maven', 'commands' => ['mvn -version'], 'explanation' => 'Verify Maven is installed'], ['step' => 3, 'title' => 'Build Project', 'commands' => ['mvn clean install'], 'explanation' => 'Download dependencies and build']]],
            'python' => ['steps' => [['step' => 1, 'title' => 'Create Virtual Environment', 'commands' => ['python -m venv venv'], 'explanation' => 'Create isolated Python environment'], ['step' => 2, 'title' => 'Activate Environment', 'commands' => ['source venv/bin/activate'], 'explanation' => 'Activate the virtual environment'], ['step' => 3, 'title' => 'Install Dependencies', 'commands' => ['pip install -r requirements.txt'], 'explanation' => 'Install all required packages']]],
            'node' => ['steps' => [['step' => 1, 'title' => 'Verify Node.js', 'commands' => ['node -v', 'npm -v'], 'explanation' => 'Check Node.js and npm are installed'], ['step' => 2, 'title' => 'Install Dependencies', 'commands' => ['npm install'], 'explanation' => 'Install all npm packages']]],
            'react' => ['steps' => [['step' => 1, 'title' => 'Install Dependencies', 'commands' => ['npm install'], 'explanation' => 'Install React and all dependencies']]],
            'php' => ['steps' => [['step' => 1, 'title' => 'Install Composer', 'commands' => ['composer -v'], 'explanation' => 'Verify Composer is installed'], ['step' => 2, 'title' => 'Install Dependencies', 'commands' => ['composer install'], 'explanation' => 'Install all PHP packages']]],
        ];

        return $guides[$stack['primary']] ?? ['steps' => []];
    }

    private function getBasicRunGuide(array $stack): array
    {
        $guides = [
            'java' => ['development' => ['commands' => ['mvn spring-boot:run'], 'explanation' => 'Start the development server', 'access_url' => 'http://localhost:8080'], 'production' => ['commands' => ['mvn clean package', 'java -jar target/*.jar'], 'explanation' => 'Build and run production JAR']],
            'python' => ['development' => ['commands' => ['python main.py'], 'explanation' => 'Start the Flask development server', 'access_url' => 'http://localhost:5000'], 'production' => ['commands' => ['gunicorn main:app'], 'explanation' => 'Run with Gunicorn for production']],
            'node' => ['development' => ['commands' => ['npm run dev'], 'explanation' => 'Start with nodemon for auto-reload', 'access_url' => 'http://localhost:3000'], 'production' => ['commands' => ['npm start'], 'explanation' => 'Start production server']],
            'react' => ['development' => ['commands' => ['npm run dev'], 'explanation' => 'Start Vite development server', 'access_url' => 'http://localhost:5173'], 'production' => ['commands' => ['npm run build'], 'explanation' => 'Build for production']],
            'php' => ['development' => ['commands' => ['php -S localhost:8000 -t public'], 'explanation' => 'Start PHP development server', 'access_url' => 'http://localhost:8000']],
        ];

        return $guides[$stack['primary']] ?? ['development' => ['commands' => ['See README.md'], 'explanation' => 'Check documentation for run instructions']];
    }
}
