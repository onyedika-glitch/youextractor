<?php

namespace App\Markdown;

use Illuminate\Http\Response;

/**
 * Builds markdown/plain-text responses with the headers agents look for.
 * HEAD requests get the same headers with no body (Symfony strips it in prepare()).
 */
class MarkdownResponse
{
    public const CACHE_PAGE = 'public, max-age=300, s-maxage=3600, stale-while-revalidate=86400';
    public const CACHE_STATIC = 'public, max-age=3600, s-maxage=86400, stale-while-revalidate=604800';

    public static function page(MarkdownPage $page, bool $negotiated = false, string $cache = self::CACHE_PAGE): Response
    {
        $response = self::make($page->toMarkdown(), 200, $negotiated, $cache);
        $response->headers->set('Content-Location', $page->canonicalUrl());
        $response->headers->set('Link', '<' . $page->canonicalUrl() . '>; rel="canonical"');

        if ($page->updated && ($time = strtotime($page->updated))) {
            $response->headers->set('Last-Modified', gmdate('D, d M Y H:i:s', $time) . ' GMT');
        }

        return $response;
    }

    public static function notFound(string $path, bool $negotiated = false): Response
    {
        $body = "# Not found\n\n> There is no markdown version of `" . str_replace('`', '', $path) . "`.\n\n"
            . 'See the list of pages: ' . Format::url('/llms.txt') . "\n";

        return self::make($body, 404, $negotiated, 'public, max-age=60');
    }

    public static function make(string $body, int $status = 200, bool $negotiated = false, string $cache = self::CACHE_PAGE, string $type = 'text/markdown'): Response
    {
        $response = new Response($body, $status, [
            'Content-Type' => $type . '; charset=utf-8',
            'X-Markdown-Tokens' => (string) (int) ceil(strlen($body) / 4),
            'X-Robots-Tag' => 'noindex, follow',
            'Cache-Control' => $cache,
        ] + self::cors());

        if ($negotiated) {
            $response->headers->set('Vary', 'Accept');
        }

        return $response;
    }

    /**
     * Preflight response for OPTIONS.
     */
    public static function preflight(): Response
    {
        return new Response('', 204, self::cors() + [
            'Access-Control-Allow-Headers' => 'Accept, Content-Type',
            'Access-Control-Max-Age' => '86400',
        ]);
    }

    /**
     * @return array<string, string>
     */
    public static function cors(): array
    {
        return [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Methods' => 'GET, HEAD, OPTIONS',
        ];
    }
}
