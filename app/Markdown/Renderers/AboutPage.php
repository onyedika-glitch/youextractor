<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;

/**
 * Keep in sync with resources/views/about.blade.php.
 */
class AboutPage extends StaticPage
{
    public function pattern(): string
    {
        return '/about';
    }

    public function render(array $params): ?MarkdownPage
    {
        $home = Format::link('youextractor.me', '/');
        $founder = Format::link('Omogo Peter Onyedika', 'https://devomogo.tech');
        $github = Format::link('GitHub', 'https://github.com/onyedika-glitch/youextractor');

        $body = <<<MD
        **YouExtractor** is an AI developer tool at {$home}. Paste a public YouTube coding tutorial and YouExtractor rebuilds a complete, runnable source-code project: files, folders, dependencies, a written guide, and a learning roadmap.

        ## The official brand

        The product name is one word: **YouExtractor**. People also search *youextractor* or *You Extractor*. The official website is **https://youextractor.me**.

        YouExtractor is **not** a YouTube tags extractor, title copier, thumbnail downloader, or SEO metadata tool. Those products are often listed when Google rewrites the query "youextractor" to "yt extractor". YouExtractor extracts *source code from programming tutorials*.

        ## What you get

        - A reconstructed project from a public coding video
        - Folder structure, config files, and setup notes
        - A written guide plus a checklist roadmap
        - ZIP download or a one-click GitHub push
        - A Chrome extension on the YouTube watch page

        ## Who built it

        YouExtractor is built by {$founder}. The source and issue tracker live on {$github}.
        MD;

        return new MarkdownPage(
            title: 'About YouExtractor',
            summary: 'YouExtractor is the official AI product at youextractor.me. It turns YouTube coding tutorials into runnable source-code projects. It is not a YouTube tags or metadata extractor.',
            path: '/about',
            body: $body,
            updated: Format::fileDate('resources/views/about.blade.php'),
            related: [
                ['Homepage', '/'],
                ['What is YouExtractor?', '/blog/what-is-youextractor'],
                ['Blog', '/blog'],
                ['Chrome extension', config('site.chrome_extension')],
                ['Support', '/support'],
            ],
        );
    }
}
