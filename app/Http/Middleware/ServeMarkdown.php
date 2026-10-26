<?php

namespace App\Http\Middleware;

use App\Markdown\Format;
use App\Markdown\MarkdownResponse;
use App\Markdown\Registry;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves the markdown version of public pages to AI agents:
 *  - GET /pricing.md, /index.md, /blog/my-post.md
 *  - GET /blog with "Accept: text/markdown" ranked above text/html
 *
 * Runs as global middleware, before sessions and cookies, so markdown responses
 * stay cacheable. Every other response passes through and, when it is HTML,
 * gets Link headers pointing at /llms.txt, /llms-full.txt, /ai.txt and the page's .md twin.
 */
class ServeMarkdown
{
    /** Never treat these as page.md URLs. */
    private const EXCLUDED_PREFIXES = ['/api/', '/.well-known/', '/storage/', '/build/'];

    public function __construct(private Registry $registry)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $path = '/' . trim($request->path(), '/');
        $readOnly = in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);

        if ($readOnly && ($target = $this->markdownTarget($path)) !== null) {
            if ($request->isMethod('OPTIONS')) {
                return MarkdownResponse::preflight()->prepare($request);
            }

            $page = $this->registry->render($target);

            // prepare(): these responses skip the router, which normally strips HEAD bodies.
            return ($page
                ? MarkdownResponse::page($page, false, $this->registry->cacheFor($target))
                : MarkdownResponse::notFound($path))->prepare($request);
        }

        $negotiable = $readOnly && $this->registry->matches($path);

        if ($negotiable && !$request->isMethod('OPTIONS') && $this->prefersMarkdown((string) $request->header('Accept', ''))) {
            $page = $this->registry->render($path);

            return ($page
                ? MarkdownResponse::page($page, true, $this->registry->cacheFor($path))
                : MarkdownResponse::notFound($path, true))->prepare($request);
        }

        $response = $next($request);

        if (str_starts_with((string) $response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Link', $this->describedBy(), false);

            if ($negotiable) {
                $response->headers->set('Link', '<' . Format::mdUrl($path) . '>; rel="alternate"; type="text/markdown"', false);
                $response->setVary('Accept', false);
            }
        }

        return $response;
    }

    /**
     * '/pricing.md' → '/pricing', '/index.md' → '/'. Null when the path is not a page.md URL.
     */
    private function markdownTarget(string $path): ?string
    {
        if (!str_ends_with($path, '.md') || $path === '/.md') {
            return null;
        }

        foreach (self::EXCLUDED_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return null;
            }
        }

        // A real .md file in public/ wins (nginx normally serves it first).
        if (is_file(public_path(ltrim($path, '/')))) {
            return null;
        }

        $target = substr($path, 0, -3);

        return $target === '/index' ? '/' : $target;
    }

    /**
     * True only when text/markdown has a strictly higher q than text/html.
     * A missing header or a bare "*\/*" always gets HTML.
     */
    private function prefersMarkdown(string $accept): bool
    {
        $markdown = 0.0;
        $html = ['text/html' => null, 'text/*' => null, '*/*' => null];

        foreach (explode(',', $accept) as $range) {
            $params = array_map('trim', explode(';', $range));
            $type = strtolower(array_shift($params));
            $q = 1.0;

            foreach ($params as $param) {
                if (preg_match('/^q\s*=\s*([0-9.]+)$/i', $param, $m)) {
                    $q = max(0.0, min(1.0, (float) $m[1]));
                }
            }

            if ($type === 'text/markdown' || $type === 'text/x-markdown') {
                $markdown = max($markdown, $q);
            } elseif (array_key_exists($type, $html)) {
                $html[$type] = max($html[$type] ?? 0.0, $q);
            }
        }

        // The most specific range that matches text/html decides its q.
        $htmlQ = $html['text/html'] ?? $html['text/*'] ?? $html['*/*'] ?? 0.0;

        return $markdown > 0 && $markdown > $htmlQ;
    }

    private function describedBy(): string
    {
        return implode(', ', [
            '<' . Format::url('/llms.txt') . '>; rel="describedby"; type="text/plain"',
            '<' . Format::url('/llms-full.txt') . '>; rel="describedby"; type="text/plain"',
            '<' . Format::url('/ai.txt') . '>; rel="describedby"; type="text/plain"',
        ]);
    }
}
