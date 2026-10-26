<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Loads Markdown blog posts from resources/content/blog.
 * Shared by the HTML blog and the markdown/llms.txt renderers.
 */
class BlogRepository
{
    private string $postsPath = 'resources/content/blog';

    /**
     * Get all posts sorted by date desc.
     */
    public function all(): array
    {
        $path = base_path($this->postsPath);

        if (!File::isDirectory($path)) {
            return [];
        }

        $files = File::glob($path . '/*.md');
        $posts = [];

        foreach ($files as $file) {
            $content = File::get($file);
            $post = $this->parseMarkdown($content);
            $post['slug'] = basename($file, '.md');
            $posts[] = $post;
        }

        // Sort by date descending
        usort($posts, fn($a, $b) => strcmp($b['date'] ?? '', $a['date'] ?? ''));

        return $posts;
    }

    public function find(string $slug): ?array
    {
        if (!preg_match('/^[A-Za-z0-9_-]+$/', $slug)) {
            return null;
        }

        $file = base_path($this->postsPath . '/' . $slug . '.md');

        if (!File::exists($file)) {
            return null;
        }

        $content = File::get($file);
        $post = $this->parseMarkdown($content);
        $post['slug'] = $slug;

        return $post;
    }

    /**
     * Very simple frontmatter + markdown parser.
     * Supports:
     * ---
     * title: ...
     * date: 2026-06-01
     * excerpt: ...
     * reading_time: 6
     * ---
     * # Markdown content here
     *
     * 'content' is rendered HTML; 'body' is the original Markdown.
     */
    private function parseMarkdown(string $raw): array
    {
        $post = [
            'title' => 'Untitled Post',
            'date' => now()->format('Y-m-d'),
            'excerpt' => '',
            'reading_time' => 5,
            'content' => '',
            'body' => '',
        ];

        // Extract frontmatter
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $raw, $matches)) {
            $frontmatter = $matches[1];
            $body = $matches[2];

            foreach (explode("\n", $frontmatter) as $line) {
                if (preg_match('/^(\w+):\s*(.+)$/', trim($line), $fm)) {
                    $key = strtolower($fm[1]);
                    $value = trim($fm[2], " \t\n\r\0\x0B\"'");

                    if ($key === 'title') $post['title'] = $value;
                    if ($key === 'date') $post['date'] = $value;
                    if ($key === 'excerpt') $post['excerpt'] = $value;
                    if ($key === 'reading_time') $post['reading_time'] = (int)$value;
                }
            }

            $post['content'] = Str::markdown($body);
            $post['body'] = trim($body);
        } else {
            // No frontmatter - treat whole thing as markdown, use first heading as title
            $post['content'] = Str::markdown($raw);
            $post['body'] = trim($raw);
            if (preg_match('/^#\s+(.+)$/m', $raw, $titleMatch)) {
                $post['title'] = trim($titleMatch[1]);
            }
        }

        return $post;
    }
}
