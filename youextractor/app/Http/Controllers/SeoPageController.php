<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SeoPageController extends Controller
{
    public function hub(): View
    {
        return view('seo.hub', [
            'tools' => config('seo_pages.tools', []),
            'stacks' => config('seo_pages.stacks', []),
        ]);
    }

    public function tool(string $slug): View
    {
        $page = config('seo_pages.tools.' . $slug);
        abort_unless(is_array($page), 404);

        return view('seo.page', [
            'page' => $page,
            'kind' => 'tool',
            'slug' => $slug,
            'canonicalPath' => '/tools/' . $slug,
            'related' => $this->relatedTools($page['related'] ?? []),
            'relatedStacks' => [],
        ]);
    }

    public function stack(string $slug): View
    {
        $page = config('seo_pages.stacks.' . $slug);
        abort_unless(is_array($page), 404);

        return view('seo.page', [
            'page' => $page,
            'kind' => 'stack',
            'slug' => $slug,
            'canonicalPath' => '/for/' . $slug,
            'related' => $this->relatedTools($page['related_tools'] ?? []),
            'relatedStacks' => $this->relatedStacks($page['related_stacks'] ?? []),
        ]);
    }

    /**
     * @param  list<string>  $slugs
     * @return list<array{slug: string, title: string, href: string, h1: string}>
     */
    private function relatedTools(array $slugs): array
    {
        $out = [];
        foreach ($slugs as $slug) {
            $page = config('seo_pages.tools.' . $slug);
            if (! is_array($page)) {
                continue;
            }
            $out[] = [
                'slug' => $slug,
                'title' => $page['title'],
                'h1' => $page['h1'],
                'href' => url('/tools/' . $slug),
            ];
        }

        return $out;
    }

    /**
     * @param  list<string>  $slugs
     * @return list<array{slug: string, title: string, href: string, h1: string}>
     */
    private function relatedStacks(array $slugs): array
    {
        $out = [];
        foreach ($slugs as $slug) {
            $page = config('seo_pages.stacks.' . $slug);
            if (! is_array($page)) {
                continue;
            }
            $out[] = [
                'slug' => $slug,
                'title' => $page['title'],
                'h1' => $page['h1'],
                'href' => url('/for/' . $slug),
            ];
        }

        return $out;
    }
}
