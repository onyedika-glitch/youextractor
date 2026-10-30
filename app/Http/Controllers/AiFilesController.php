<?php

namespace App\Http\Controllers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;
use App\Markdown\MarkdownResponse;
use App\Markdown\Registry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Machine-readable site files for crawlers and AI agents. All of them are
 * generated from config/site.php and the markdown Registry, so robots.txt,
 * llms.txt and ai.txt stay in sync with each other and with the pages.
 */
class AiFilesController extends Controller
{
    private const BLOG_LIMIT = 50;
    private const FULL_LIMIT_CHARS = 500_000;

    /** User agents that get an explicit group in robots.txt. */
    private const AI_SEARCH_BOTS = ['OAI-SearchBot', 'ChatGPT-User', 'Claude-SearchBot', 'Claude-User', 'PerplexityBot', 'Perplexity-User'];
    private const AI_TRAINING_BOTS = ['GPTBot', 'ClaudeBot', 'Google-Extended', 'Applebot-Extended', 'CCBot', 'Bytespider'];

    public function __construct(private Registry $registry)
    {
    }

    /**
     * /llms.txt (llmstxt.org format).
     */
    public function llms(): Response
    {
        $pages = $this->registry->listMarkdownRoutes();
        $site = config('site');

        $out = '# ' . $site['name'] . "\n\n";
        $out .= '> ' . $site['description'] . "\n\n";
        $out .= $site['disambiguation'] . ' ' . $site['pricing']
            . ' Built by ' . $site['founder']['name'] . ' (' . $site['founder']['url'] . ').'
            . ' Contact: ' . $site['contact']['email'] . ' or ' . $site['contact']['issues'] . ".\n";

        foreach (Registry::SECTIONS as $key => $heading) {
            if ($key === 'legal') {
                continue;
            }
            $items = $pages[$key];
            if ($key === 'blog' && count($items) > self::BLOG_LIMIT) {
                $items = array_slice($items, 0, self::BLOG_LIMIT);
            }
            $out .= $this->llmsSection($heading, $items);
        }

        $out .= "\n## Machine-readable\n\n" . Format::list([
            Format::link('Full content', '/llms-full.txt') . ': every page above as markdown, in one file',
            Format::link('Markdown catalog', '/api/md/_catalog') . ': every markdown route',
            Format::link('Sitemap', '/sitemap.xml') . ': canonical HTML URLs',
            Format::link('AI usage policy', '/ai.txt') . ': permissions, restrictions and attribution',
            Format::link('Articles', '/articles') . ': SEO articles about learning to code from video (HTML, hosted by Outrank)',
            Format::link('API reference', '/api-docs') . ': REST API docs (HTML)',
            Format::link('Chrome extension', $site['chrome_extension']) . ': extract from the YouTube watch page',
        ]) . "\n";

        $out .= $this->llmsSection(Registry::SECTIONS['legal'], $pages['legal']);
        $out .= "\n<!-- Generated from config/site.php and app/Markdown/Registry.php. Keep in sync with /robots.txt and /ai.txt. -->\n";

        return MarkdownResponse::make($out, 200, false, MarkdownResponse::CACHE_STATIC, 'text/plain');
    }

    /**
     * /llms-full.txt: every llms.txt page rendered by the same registry.
     */
    public function llmsFull(): Response
    {
        $site = config('site');

        $out = '# ' . $site['name'] . " - full content\n\n";
        $out .= '> ' . $site['description'] . "\n\n";
        $out .= 'Generated: ' . now()->toDateString() . "\n";
        $out .= 'Index: ' . Format::url('/llms.txt') . "\n";

        foreach ($this->registry->listMarkdownRoutes() as $key => $pages) {
            if ($key === 'blog' && count($pages) > self::BLOG_LIMIT) {
                $out .= "\n---\n\nShowing the newest " . self::BLOG_LIMIT . ' of ' . count($pages)
                    . ' blog posts. All posts: ' . Format::mdUrl('/blog') . "\n";
                $pages = array_slice($pages, 0, self::BLOG_LIMIT);
            }

            foreach ($pages as $page) {
                $out .= "\n---\n<!-- source: " . $page->canonicalUrl() . " -->\n\n" . $page->toMarkdown();
            }
        }

        if (strlen($out) > self::FULL_LIMIT_CHARS) {
            Log::warning('llms-full.txt is larger than the target size', ['chars' => strlen($out)]);
        }

        return MarkdownResponse::make($out, 200, false, 'public, max-age=3600, s-maxage=86400, stale-while-revalidate=604800', 'text/plain');
    }

    /**
     * /ai.txt (ai-txt spec v1.1.1 layout).
     */
    public function aiTxt(): Response
    {
        $site = config('site');
        $base = Format::baseUrl();
        $excluded = implode(', ', $site['private_paths']);

        $out = <<<TXT
        # ai.txt for {$site['name']} ({$base}/ai.txt)
        # Spec: ai-txt v1.1.1 (https://ai-visibility.org.uk)
        # Generated from config/site.php. Keep in sync with /robots.txt and /llms.txt.

        [identity]
        name: {$site['name']}
        url: {$base}/
        tagline: {$site['tagline']}
        description: {$site['description']}
        disambiguation: {$site['disambiguation']}
        founder: {$site['founder']['name']} ({$site['founder']['url']})
        contact: {$site['contact']['email']}

        [permissions]
        - Summarise public pages
        - Quote public pages with attribution
        - Cite and link to public pages in answers
        - Index public pages for search and answer engines

        [restrictions]
        - Do not fabricate quotes, features, prices or statistics about YouExtractor
        - Do not invent reviews, ratings or testimonials
        - Do not present YouExtractor content as your own
        - Do not describe YouExtractor as a YouTube tags, title or SEO metadata extractor
        - Do not access, train on or reproduce users' private extractions, accounts or dashboards

        [attribution]
        preferred_citation: {$site['name']} ({$base}/)
        link_to: the canonical HTML URL of the page (not the .md version)

        [training]
        ai_training: permitted - public pages may be included in training corpora
        live_fetching: permitted - markdown versions are available at <page>.md and via Accept: text/markdown

        [scope]
        included: {$base}/ (all public pages)
        excluded: {$excluded}

        [contact]
        email: {$site['contact']['email']}
        issues: {$site['contact']['issues']}
        support: {$base}{$site['contact']['support_page']}

        [metadata]
        llms_txt: {$base}/llms.txt
        llms_full_txt: {$base}/llms-full.txt
        sitemap: {$base}/sitemap.xml
        robots: {$base}/robots.txt

        [updates]
        last_updated: 2026-10-26

        TXT;

        return MarkdownResponse::make($out, 200, false, MarkdownResponse::CACHE_STATIC, 'text/plain');
    }

    /**
     * /robots.txt
     */
    public function robots(): Response
    {
        $rules = "Allow: /\n";
        foreach (config('site.public_exceptions') as $path) {
            $rules .= 'Allow: ' . $path . "\n";
        }
        foreach (config('site.private_paths') as $path) {
            $rules .= 'Disallow: ' . $path . "\n";
        }

        $agents = fn (array $bots) => implode('', array_map(fn ($bot) => 'User-agent: ' . $bot . "\n", $bots));

        $out = "# robots.txt for " . config('site.name') . "\n"
            . "# AI agents: site map for language models at /llms.txt, usage policy at /ai.txt.\n"
            . "# Generated from config/site.php. Keep in sync with /llms.txt and /ai.txt.\n\n"
            . "User-agent: *\n" . $rules
            . "\n# AI search and user-triggered fetchers: allowed so answer engines can cite us.\n"
            . $agents(self::AI_SEARCH_BOTS) . $rules
            . "\n# AI training crawlers: allowed.\n"
            . $agents(self::AI_TRAINING_BOTS) . $rules
            . "\nSitemap: " . Format::url('/sitemap.xml') . "\n";

        return response($out, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => MarkdownResponse::CACHE_STATIC,
        ]);
    }

    /**
     * /api/md/{path}: always markdown, for debugging and direct fetches.
     */
    public function markdown(Request $request, string $path = ''): Response
    {
        if ($request->isMethod('OPTIONS')) {
            return MarkdownResponse::preflight();
        }

        if ($path === '_catalog') {
            return $this->catalog();
        }

        $path = '/' . trim(preg_replace('/\.md$/', '', $path), '/');
        if ($path === '/index') {
            $path = '/';
        }

        $page = $this->registry->render($path);

        return $page
            ? MarkdownResponse::page($page, false, $this->registry->cacheFor($path))
            : MarkdownResponse::notFound($path);
    }

    private function catalog(): Response
    {
        $out = '# ' . config('site.name') . " markdown catalog\n\n"
            . "> Every page with a markdown version. Fetch `<page>.md`, send `Accept: text/markdown`, or use `/api/md/<path>`.\n\n"
            . "| Page | HTML | Markdown |\n|---|---|---|\n";

        foreach ($this->registry->listMarkdownRoutes() as $pages) {
            foreach ($pages as $page) {
                $out .= '| ' . str_replace('|', '\|', $page->title) . ' | ' . $page->canonicalUrl() . ' | ' . Format::mdUrl($page->path) . " |\n";
            }
        }

        return MarkdownResponse::make($out, 200, false, MarkdownResponse::CACHE_PAGE);
    }

    /**
     * @param  list<MarkdownPage>  $pages
     */
    private function llmsSection(string $heading, array $pages): string
    {
        if (!$pages) {
            return '';
        }

        return "\n## " . $heading . "\n\n" . Format::list(array_map(
            fn (MarkdownPage $page) => Format::link($page->title, Format::mdUrl($page->path)) . ': ' . $page->summary,
            $pages
        )) . "\n";
    }
}
