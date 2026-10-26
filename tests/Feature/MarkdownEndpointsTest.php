<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarkdownEndpointsTest extends TestCase
{
    public function test_md_suffix_returns_markdown_with_agent_headers(): void
    {
        $this->get('/about.md')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/markdown; charset=utf-8')
            ->assertHeader('X-Robots-Tag', 'noindex, follow')
            ->assertHeader('Content-Location', 'https://youextractor.me/about')
            ->assertHeader('Access-Control-Allow-Origin', '*')
            ->assertSee('# About YouExtractor', false);
    }

    public function test_accept_header_negotiation(): void
    {
        $this->get('/tools', ['Accept' => 'text/markdown'])
            ->assertHeader('Content-Type', 'text/markdown; charset=utf-8')
            ->assertHeader('Vary', 'Accept');

        $this->get('/tools', ['Accept' => 'text/markdown;q=0.5, text/html'])
            ->assertHeader('Content-Type', 'text/html; charset=utf-8');

        $this->get('/tools', ['Accept' => '*/*'])
            ->assertHeader('Content-Type', 'text/html; charset=utf-8');
    }

    public function test_html_pages_advertise_ai_files(): void
    {
        $link = implode(', ', $this->get('/about')->headers->all('link'));

        $this->assertStringContainsString('/llms.txt>; rel="describedby"', $link);
        $this->assertStringContainsString('/about.md>; rel="alternate"', $link);
    }

    public function test_unknown_and_private_pages_are_markdown_404s(): void
    {
        foreach (['/does-not-exist.md', '/dashboard.md', '/videos/1.md', '/tools/nope.md'] as $path) {
            $this->get($path)
                ->assertNotFound()
                ->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
        }
    }

    public function test_site_files(): void
    {
        $this->get('/llms.txt')->assertOk()->assertSee('# YouExtractor', false)->assertSee('/about.md', false);
        $this->get('/llms-full.txt')->assertOk()->assertSee('<!-- source: https://youextractor.me/privacy -->', false);
        $this->get('/ai.txt')->assertOk()->assertSee('[identity]', false);
        $this->get('/robots.txt')->assertOk()->assertSee('User-agent: OAI-SearchBot', false)->assertSee('Sitemap: https://youextractor.me/sitemap.xml', false);
        $this->get('/api/md/_catalog')->assertOk()->assertSee('https://youextractor.me/index.md', false);
    }
}
