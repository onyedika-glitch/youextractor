<?php

namespace App\Markdown;

/**
 * Small formatting helpers shared by every markdown renderer.
 */
class Format
{
    public static function baseUrl(): string
    {
        return rtrim(config('site.url') ?: 'https://youextractor.me', '/');
    }

    /**
     * Absolute HTML URL for a site path ('/' → https://youextractor.me/).
     */
    public static function url(string $path): string
    {
        if (preg_match('#^https?://#', $path)) {
            return $path;
        }

        return self::baseUrl() . '/' . ltrim($path, '/');
    }

    /**
     * Absolute URL of the markdown version of a site path.
     */
    public static function mdUrl(string $path): string
    {
        $path = '/' . trim($path, '/');

        return self::baseUrl() . ($path === '/' ? '/index.md' : $path . '.md');
    }

    /**
     * Escape characters that would break inline markdown.
     */
    public static function escape(string $text): string
    {
        return addcslashes(trim($text), '[]*_`<>');
    }

    public static function link(string $text, string $url): string
    {
        return '[' . self::escape($text) . '](' . self::url($url) . ')';
    }

    /**
     * @param  list<string>  $items
     */
    public static function list(array $items): string
    {
        return implode("\n", array_map(fn ($item) => '- ' . $item, $items));
    }

    /**
     * @param  list<array{q: string, a: string}>  $faqs
     */
    public static function faq(array $faqs): string
    {
        return implode("\n\n", array_map(
            fn ($faq) => '### ' . trim($faq['q']) . "\n\n" . trim($faq['a']),
            $faqs
        ));
    }

    /**
     * Rewrite root-relative markdown links ("](/about)") to absolute URLs.
     */
    public static function absolutizeLinks(string $markdown): string
    {
        return preg_replace_callback(
            '/\]\((\/[^)\s]*)\)/',
            fn ($m) => '](' . self::url($m[1]) . ')',
            $markdown
        );
    }

    public static function date(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        $time = strtotime($value);

        return $time ? date('Y-m-d', $time) : null;
    }

    /**
     * Last-modified date of a file relative to the project root.
     */
    public static function fileDate(string $relativePath): ?string
    {
        $mtime = @filemtime(base_path($relativePath));

        return $mtime ? date('Y-m-d', $mtime) : null;
    }
}
