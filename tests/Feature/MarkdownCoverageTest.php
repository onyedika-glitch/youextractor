<?php

namespace Tests\Feature;

use App\Markdown\Format;
use App\Markdown\Registry;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class MarkdownCoverageTest extends TestCase
{
    /**
     * Every public GET page needs a markdown renderer, or an entry in
     * Registry::SKIPPED with a reason.
     */
    public function test_every_public_route_has_a_markdown_renderer(): void
    {
        $patterns = app(Registry::class)->patterns();
        $missing = [];

        foreach (Route::getRoutes() as $route) {
            $uri = '/' . ltrim($route->uri(), '/');
            $middleware = $route->gatherMiddleware();

            $private = in_array('auth', $middleware, true) || in_array('guest', $middleware, true);
            $notAPage = str_starts_with($uri, '/api/') || str_starts_with($uri, '/dev/') || str_starts_with($uri, '/webhooks/');

            if (!in_array('GET', $route->methods(), true) || $private || $notAPage) {
                continue;
            }

            if (!in_array($uri, $patterns, true) && !array_key_exists($uri, Registry::SKIPPED)) {
                $missing[] = $uri;
            }
        }

        $this->assertSame([], $missing, 'Add a renderer to App\Markdown\Registry or list the route in Registry::SKIPPED.');
    }

    public function test_every_listed_page_renders(): void
    {
        foreach (app(Registry::class)->listMarkdownRoutes() as $pages) {
            foreach ($pages as $page) {
                $this->get(parse_url(Format::mdUrl($page->path), PHP_URL_PATH))
                    ->assertOk()
                    ->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
            }
        }
    }
}
