<?php

namespace App\Markdown;

/**
 * Maps public route patterns to markdown renderers.
 *
 * Adding a page = add its renderer to RENDERERS. The .md URLs, Accept
 * negotiation, /api/md, the catalog, llms.txt and llms-full.txt pick it up.
 */
class Registry
{
    /** llms.txt / llms-full.txt order. */
    public const SECTIONS = [
        'core' => 'Core pages',
        'guides' => 'Tools and stack guides',
        'blog' => 'Blog',
        'legal' => 'Optional',
    ];

    /** @var list<class-string<Renderer>> */
    private const RENDERERS = [
        Renderers\HomePage::class,
        Renderers\AboutPage::class,
        Renderers\SupportPage::class,
        Renderers\ToolsHub::class,
        Renderers\ToolPage::class,
        Renderers\StackPage::class,
        Renderers\BlogIndex::class,
        Renderers\BlogPost::class,
        Renderers\PrivacyPage::class,
        Renderers\TermsPage::class,
    ];

    /**
     * Public GET routes that intentionally have no markdown version.
     * MarkdownCoverageTest fails for any route that is in neither list.
     */
    public const SKIPPED = [
        '/pricing' => 'noindex page',
        '/api-docs' => 'static HTML file; no data source to render from, linked from llms.txt instead',
        '/robots.txt' => 'machine file',
        '/sitemap.xml' => 'machine file',
        '/llms.txt' => 'machine file',
        '/llms-full.txt' => 'machine file',
        '/ai.txt' => 'machine file',
        '/payment/callback' => 'payment redirect',
        '/up' => 'health check',
    ];

    /** @var list<Renderer>|null */
    private ?array $renderers = null;

    /**
     * @return list<Renderer>
     */
    public function renderers(): array
    {
        return $this->renderers ??= array_map(fn ($class) => app($class), self::RENDERERS);
    }

    /**
     * @return list<string>
     */
    public function patterns(): array
    {
        return array_map(fn (Renderer $r) => $r->pattern(), $this->renderers());
    }

    /**
     * Whether a path belongs to a registered pattern (the content-route allowlist).
     */
    public function matches(string $path): bool
    {
        return $this->resolve($path) !== null;
    }

    /**
     * Cache-Control for a path: pages without route parameters only change on deploy.
     */
    public function cacheFor(string $path): string
    {
        $match = $this->resolve($path);

        return $match && !str_contains($match[0]->pattern(), '{')
            ? MarkdownResponse::CACHE_STATIC
            : MarkdownResponse::CACHE_PAGE;
    }

    public function render(string $path): ?MarkdownPage
    {
        $match = $this->resolve($path);

        return $match ? $match[0]->render($match[1]) : null;
    }

    /**
     * Every addressable markdown page, in section order.
     *
     * @return array<string, list<MarkdownPage>>  section key => pages
     */
    public function listMarkdownRoutes(): array
    {
        $out = array_fill_keys(array_keys(self::SECTIONS), []);

        foreach ($this->renderers() as $renderer) {
            foreach ($renderer->instances() as $params) {
                $page = $renderer->render($params);
                if ($page) {
                    $out[$renderer->section()][] = $page;
                }
            }
        }

        return $out;
    }

    /**
     * @return array{0: Renderer, 1: array<string, string>}|null
     */
    private function resolve(string $path): ?array
    {
        $path = '/' . trim($path, '/');

        foreach ($this->renderers() as $renderer) {
            $regex = '#^' . preg_replace('/\\\\\{(\w+)\\\\\}/', '(?P<$1>[A-Za-z0-9_-]+)', preg_quote($renderer->pattern(), '#')) . '$#';

            if (preg_match($regex, $path, $m)) {
                return [$renderer, array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY)];
            }
        }

        return null;
    }
}
