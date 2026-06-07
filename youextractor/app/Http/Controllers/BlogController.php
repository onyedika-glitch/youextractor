<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    private string $postsPath = 'resources/content/blog';

    /**
     * List all blog posts.
     */
    public function index(): View
    {
        $posts = $this->getAllPosts();

        return view('blog.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Show a single blog post.
     */
    public function show(string $slug): View
    {
        $post = $this->getPostBySlug($slug);

        if (!$post) {
            abort(404);
        }

        return view('blog.show', [
            'post' => $post,
            'slug' => $slug,
        ]);
    }

    /**
     * Get all posts sorted by date desc.
     */
    private function getAllPosts(): array
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

    private function getPostBySlug(string $slug): ?array
    {
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
     */
    private function parseMarkdown(string $raw): array
    {
        $post = [
            'title' => 'Untitled Post',
            'date' => now()->format('Y-m-d'),
            'excerpt' => '',
            'reading_time' => 5,
            'content' => '',
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
        } else {
            // No frontmatter - treat whole thing as markdown, use first heading as title
            $post['content'] = Str::markdown($raw);
            if (preg_match('/^#\s+(.+)$/m', $raw, $titleMatch)) {
                $post['title'] = trim($titleMatch[1]);
            }
        }

        return $post;
    }
}