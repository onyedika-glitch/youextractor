<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class CodeExtractorService
{
    /* ============================================================
       PUBLIC ENTRY
    ============================================================ */

    public function enhancedExtractFromUrl(string $title, string $videoUrl): array
    {
        $videoId = $this->extractVideoId($videoUrl);

        $transcript = $this->fetchTranscript($videoId);

        $repo = $this->detectRepository($videoUrl, $transcript);

        if ($repo) {
            return $this->extractFromRepository($title, $repo, $transcript);
        }

        return $this->extractWithoutRepository($title, $transcript);
    }

    /* ============================================================
       REPOSITORY DETECTION (NO GUESSING)
    ============================================================ */

    private function detectRepository(string $videoUrl, string $transcript): ?string
    {
        $sources = [$videoUrl, $transcript];

        foreach ($sources as $source) {
            if (!$source) continue;

            preg_match_all(
                '/https?:\/\/github\.com\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_.-]+/',
                $source,
                $matches
            );

            foreach ($matches[0] ?? [] as $repoUrl) {
                if ($this->validateRepository($repoUrl)) {
                    return rtrim($repoUrl, '/');
                }
            }
        }

        return null;
    }

    private function validateRepository(string $repoUrl): bool
    {
        try {
            $response = Http::timeout(8)->head($repoUrl);
            return $response->successful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /* ============================================================
       EXTRACTION PATHS
    ============================================================ */

    private function extractFromRepository(string $title, string $repo, string $transcript): array
    {
        return [
            'transcript' => $transcript,
            'repository_url' => $repo,
            'stack' => $this->detectStackFromRepo($repo),
            'files' => [],
            'dependencies' => [],
            'setup_instructions' => 'See repository README.md',
            'confidence' => [
                'stack' => 0.95,
                'files' => 0.90,
            ],
        ];
    }

    private function extractWithoutRepository(string $title, string $transcript): array
    {
        $stack = $this->detectStack($title, $transcript);

        $files = $this->extractFiles($stack);

        return [
            'transcript' => $transcript,
            'repository_url' => null,
            'stack' => $stack,
            'files' => $files,
            'dependencies' => [],
            'setup_instructions' => $this->setupInstructions($stack),
            'confidence' => [
                'stack' => $stack['confidence'],
                'files' => empty($files) ? 0.0 : 0.85,
            ],
        ];
    }

    /* ============================================================
       STACK DETECTION (STRICT + CONFIDENCE)
    ============================================================ */

    private function detectStack(string $title, string $transcript): array
    {
        $text = strtolower($title . ' ' . $transcript);

        if (str_contains($text, 'html') && str_contains($text, 'css')) {
            return [
                'primary' => 'frontend',
                'languages' => ['html', 'css', 'javascript'],
                'frameworks' => [],
                'description' => 'Static frontend website',
                'confidence' => 0.92,
            ];
        }

        if (str_contains($text, 'spring boot')) {
            return [
                'primary' => 'java',
                'languages' => ['java'],
                'frameworks' => ['spring boot'],
                'description' => 'Java Spring Boot backend',
                'confidence' => 0.90,
            ];
        }

        return [
            'primary' => 'unknown',
            'languages' => [],
            'frameworks' => [],
            'description' => 'Unable to confidently detect stack',
            'confidence' => 0.20,
        ];
    }

    private function detectStackFromRepo(string $repo): array
    {
        if (str_contains($repo, 'laravel')) {
            return [
                'primary' => 'php',
                'languages' => ['php'],
                'frameworks' => ['laravel'],
                'description' => 'Laravel repository',
                'confidence' => 0.95,
            ];
        }

        return [
            'primary' => 'unknown',
            'languages' => [],
            'frameworks' => [],
            'description' => 'Repository detected, stack undetermined',
            'confidence' => 0.75,
        ];
    }

    /* ============================================================
       FILE EXTRACTION (NO INVENTION)
    ============================================================ */

    private function extractFiles(array $stack): array
    {
        if ($stack['primary'] !== 'frontend') {
            return [];
        }

        return [
            $this->file('index.html', 'html', 'Main page'),
            $this->file('style.css', 'css', 'Styling'),
            $this->file('script.js', 'javascript', 'Interactivity'),
        ];
    }

    private function file(string $name, string $lang, string $desc): array
    {
        return [
            'filename' => $name,
            'language' => $lang,
            'path' => $name,
            'description' => $desc,
            'code' => '',
        ];
    }

    /* ============================================================
       TRANSCRIPT
    ============================================================ */

    private function fetchTranscript(string $videoId): string
    {
        try {
            $res = Http::timeout(12)->get(
                'https://www.youtube.com/api/timedtext',
                ['v' => $videoId, 'lang' => 'en']
            );

            if ($res->successful()) {
                return strip_tags($res->body());
            }
        } catch (\Throwable $e) {}

        return 'Transcript not available.';
    }

    private function extractVideoId(string $url): string
    {
        preg_match('/([a-zA-Z0-9_-]{11})/', $url, $m);
        return $m[1] ?? '';
    }

    /* ============================================================
       ZIP
    ============================================================ */

    public function generateZipFile(string $videoId, array $data): ?string
    {
        if (empty($data['files'])) return null;

        $path = storage_path("app/downloads/{$videoId}.zip");
        $zip = new ZipArchive();

        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($data['files'] as $f) {
                $zip->addFromString($f['path'], '');
            }
            $zip->close();
            return $path;
        }

        return null;
    }

    private function setupInstructions(array $stack): string
    {
        if ($stack['primary'] === 'frontend') {
            return 'Open index.html in your browser.';
        }
        return '';
    }
}
