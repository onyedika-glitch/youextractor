<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;
use App\Markdown\Renderer;

/**
 * Mirrors resources/views/seo/hub.blade.php.
 */
class ToolsHub implements Renderer
{
    public function pattern(): string
    {
        return '/tools';
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
        $tools = [];
        foreach (config('seo_pages.tools', []) as $slug => $tool) {
            $tools[] = Format::link($tool['h1'], '/tools/' . $slug) . ' - ' . $tool['description'];
        }

        $stacks = [];
        foreach (config('seo_pages.stacks', []) as $slug => $stack) {
            $stacks[] = Format::link($stack['h1'], '/for/' . $slug) . ' - ' . $stack['description'];
        }

        return new MarkdownPage(
            title: 'YouExtractor Tools: YouTube Code Extractor, GitHub Export & Guides',
            summary: 'YouExtractor developer tools: extract code from YouTube tutorials, push projects to GitHub, and follow along with React, Python, Next.js, Laravel, and more.',
            path: '/tools',
            body: "## Extractor tools\n\n" . Format::list($tools)
                . "\n\n## Extract tutorials by stack\n\n" . Format::list($stacks),
            updated: Format::fileDate('config/seo_pages.php'),
        );
    }
}
