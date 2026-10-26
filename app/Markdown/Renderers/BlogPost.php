<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;
use App\Markdown\Renderer;
use App\Services\BlogRepository;
use Illuminate\Support\Str;

/**
 * Serves the post's own Markdown source, with links made absolute.
 */
class BlogPost implements Renderer
{
    public function __construct(private BlogRepository $blog)
    {
    }

    public function pattern(): string
    {
        return '/blog/{slug}';
    }

    public function section(): string
    {
        return 'blog';
    }

    public function instances(): array
    {
        return array_map(fn ($post) => ['slug' => $post['slug']], $this->blog->all());
    }

    public function render(array $params): ?MarkdownPage
    {
        $post = $this->blog->find($params['slug'] ?? '');
        if (!$post) {
            return null;
        }

        // Same fallback as blog/show.blade.php
        $summary = $post['excerpt'] ?: Str::limit(strip_tags($post['content']), 160);

        // The H1 comes from MarkdownPage; drop a leading H1 in the source.
        $body = preg_replace('/\A#\s+.+\R+/', '', $post['body']);

        return new MarkdownPage(
            title: $post['title'],
            summary: $summary,
            path: '/blog/' . $post['slug'],
            body: 'Published: ' . Format::date($post['date']) . ' · ' . $post['reading_time'] . " min read\n\n"
                . Format::absolutizeLinks($body),
            updated: Format::date($post['date']),
            related: [['All posts', '/blog'], ['About YouExtractor', '/about']],
        );
    }
}
