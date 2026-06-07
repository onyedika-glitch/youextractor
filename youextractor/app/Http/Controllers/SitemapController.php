<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        $baseUrl = rtrim(config('app.url', 'https://youextractor.me'), '/');

        // Static public pages
        $urls[] = [
            'loc' => $baseUrl . '/',
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '1.0',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/privacy',
            'lastmod' => now()->subMonths(3)->toAtomString(),
            'changefreq' => 'yearly',
            'priority' => '0.3',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/terms',
            'lastmod' => now()->subMonths(3)->toAtomString(),
            'changefreq' => 'yearly',
            'priority' => '0.3',
        ];

        $urls[] = [
            'loc' => $baseUrl . '/blog',
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.7',
        ];

        // Dynamic videos (showcase / recent completed extractions)
        $videos = Video::where('extraction_status', 'completed')
            ->orderByDesc('extracted_at')
            ->limit(100)
            ->get(['id', 'extracted_at', 'updated_at']);

        foreach ($videos as $video) {
            $lastmod = $video->extracted_at?->toAtomString() ?? $video->updated_at->toAtomString();

            $urls[] = [
                'loc' => $baseUrl . '/videos/' . $video->id,
                'lastmod' => $lastmod,
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        // Dynamic blog posts from Markdown files
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

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}