<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;
use App\Markdown\Renderer;

/**
 * Shared renderer for the config/seo_pages.php pages (/tools/{slug}, /for/{slug}).
 * Mirrors resources/views/seo/page.blade.php.
 */
abstract class SeoConfigPage implements Renderer
{
    /** 'tools' or 'stacks' in config/seo_pages.php */
    abstract protected function group(): string;

    /** URL prefix, e.g. '/tools/' */
    abstract protected function prefix(): string;

    public function pattern(): string
    {
        return rtrim($this->prefix(), '/') . '/{slug}';
    }

    public function section(): string
    {
        return 'guides';
    }

    public function instances(): array
    {
        return array_map(
            fn ($slug) => ['slug' => (string) $slug],
            array_keys(config('seo_pages.' . $this->group(), []))
        );
    }

    public function render(array $params): ?MarkdownPage
    {
        $page = config('seo_pages.' . $this->group() . '.' . ($params['slug'] ?? ''));
        if (!is_array($page)) {
            return null;
        }

        $parts = [];
        if (!empty($page['eyebrow'])) {
            $parts[] = '*' . $page['eyebrow'] . '*';
        }
        $parts[] = $page['intro'];

        if (!empty($page['bullets'])) {
            $parts[] = "## What you get\n\n" . Format::list($page['bullets']);
        }

        foreach ($page['sections'] ?? [] as $section) {
            $parts[] = '## ' . $section['h2'] . "\n\n" . implode("\n\n", $section['body'] ?? []);
        }

        if (!empty($page['faqs'])) {
            $parts[] = "## Frequently asked questions\n\n" . Format::faq($page['faqs']);
        }

        $related = [];
        foreach ($page['related'] ?? $page['related_tools'] ?? [] as $slug) {
            if ($tool = config('seo_pages.tools.' . $slug)) {
                $related[] = [$tool['h1'], '/tools/' . $slug];
            }
        }
        foreach ($page['related_stacks'] ?? [] as $slug) {
            if ($stack = config('seo_pages.stacks.' . $slug)) {
                $related[] = [$stack['h1'], '/for/' . $slug];
            }
        }
        $related[] = ['All YouExtractor tools', '/tools'];

        return new MarkdownPage(
            title: $page['h1'],
            summary: $page['description'],
            path: $this->prefix() . $params['slug'],
            body: implode("\n\n", $parts),
            updated: Format::fileDate('config/seo_pages.php'),
            related: $related,
        );
    }
}
