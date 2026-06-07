<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class SitemapController extends Controller
{
    public function index(): Response
    {
        try {
            $urls = [];

            $baseUrl = rtrim(config('app.url', 'https://youextractor.me'), '/');

            // Static public pages (always include these)
            $staticPages = [
                ['path' => '/', 'changefreq' => 'weekly', 'priority' => '1.0'],
                ['path' => '/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['path' => '/terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
                ['path' => '/blog', 'changefreq' => 'weekly', 'priority' => '0.7'],
            ];

            foreach ($staticPages as $page) {
                $urls[] = [
                    'loc' => $baseUrl . $page['path'],
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => $page['changefreq'],
                    'priority' => $page['priority'],
                ];
            }

            // Dynamically add blog posts from Markdown files
            $blogPostsPath = base_path('resources/content/blog');
            if (is_dir($blogPostsPath)) {
                foreach (glob($blogPostsPath . '/*.md') as $mdFile) {
                    $slug = basename($mdFile, '.md');
                    $urls[] = [
                        'loc' => $baseUrl . '/blog/' . $slug,
                        'lastmod' => now()->subDays(rand(2, 30))->toAtomString(),
                        'changefreq' => 'monthly',
                        'priority' => '0.5',
                    ];
                }
            }

            // Build XML manually (more robust than Blade view for sitemaps)
            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

            foreach ($urls as $url) {
                $xml .= "  <url>\n";
                $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
                $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
                $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
                $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
                $xml .= "  </url>\n";
            }

            $xml .= '</urlset>';

            return response($xml, 200, [
                'Content-Type' => 'application/xml; charset=UTF-8',
                'Cache-Control' => 'public, max-age=3600',
            ]);

        } catch (\Throwable $e) {
            // Log the error for debugging
            Log::error('Sitemap generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return a minimal valid sitemap on error so Google doesn't get 500
            $fallbackXml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .
                '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" .
                '  <url>' . "\n" .
                '    <loc>https://youextractor.me/</loc>' . "\n" .
                '    <lastmod>' . now()->toAtomString() . '</lastmod>' . "\n" .
                '    <changefreq>weekly</changefreq>' . "\n" .
                '    <priority>1.0</priority>' . "\n" .
                '  </url>' . "\n" .
                '</urlset>';

            return response($fallbackXml, 200, [
                'Content-Type' => 'application/xml; charset=UTF-8',
            ]);
        }
    }
}