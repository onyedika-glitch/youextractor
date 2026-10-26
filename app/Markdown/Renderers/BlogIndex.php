<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;
use App\Markdown\Renderer;
use App\Services\BlogRepository;

/**
 * Mirrors resources/views/blog/index.blade.php (not paginated there either).
 */
class BlogIndex implements Renderer
{
    public function __construct(private BlogRepository $blog)
    {
    }

    public function pattern(): string
    {
        return '/blog';
    }

    public function section(): string
    {
        return 'core';
    }

    public function instances(): array
    {
        return [[]];
    }

    public function render(array $params): ?MarkdownPage
    {
        $posts = $this->blog->all();

        $items = array_map(
            fn ($post) => Format::link($post['title'], '/blog/' . $post['slug'])
                . ' (' . Format::date($post['date']) . ')'
                . ($post['excerpt'] ? ' - ' . $post['excerpt'] : ''),
            $posts
        );

        return new MarkdownPage(
            title: 'YouExtractor Blog',
            summary: 'Articles from YouExtractor about turning YouTube coding tutorials into real projects, AI-assisted learning, and the YouExtractor pipeline.',
            path: '/blog',
            body: "## Posts\n\n" . ($items ? Format::list($items) : 'No posts yet.'),
            updated: Format::date($posts[0]['date'] ?? null),
        );
    }
}
