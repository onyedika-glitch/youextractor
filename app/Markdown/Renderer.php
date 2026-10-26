<?php

namespace App\Markdown;

/**
 * Renders the markdown version of one route pattern.
 */
interface Renderer
{
    /**
     * Route pattern exactly as in routes/web.php, e.g. '/' or '/blog/{slug}'.
     */
    public function pattern(): string;

    /**
     * llms.txt section: one of Registry::SECTIONS.
     */
    public function section(): string;

    /**
     * Route parameters of every addressable page, e.g. [['slug' => 'react'], ...].
     * Static pages return [[]].
     *
     * @return list<array<string, string>>
     */
    public function instances(): array;

    /**
     * Null when the entity does not exist.
     *
     * @param  array<string, string>  $params
     */
    public function render(array $params): ?MarkdownPage;
}
