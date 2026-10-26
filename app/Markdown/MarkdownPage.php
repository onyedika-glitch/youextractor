<?php

namespace App\Markdown;

/**
 * One page's markdown version: the same facts as its HTML twin, main content only.
 */
class MarkdownPage
{
    /**
     * @param  string  $path     Canonical HTML path, e.g. '/blog/my-post'
     * @param  string  $body     Main content as markdown (no H1)
     * @param  list<array{0: string, 1: string}>  $related  [text, url] pairs
     */
    public function __construct(
        public string $title,
        public string $summary,
        public string $path,
        public string $body,
        public ?string $updated = null,
        public array $related = [],
    ) {
    }

    public function canonicalUrl(): string
    {
        return Format::url($this->path);
    }

    public function toMarkdown(): string
    {
        $out = '# ' . $this->title . "\n\n";
        $out .= '> ' . $this->summary . "\n\n";
        $out .= 'Canonical: ' . $this->canonicalUrl() . "\n";
        if ($this->updated) {
            $out .= 'Last updated: ' . $this->updated . "\n";
        }
        $out .= "\n" . trim($this->body) . "\n";

        if ($this->related) {
            $out .= "\n## Related\n\n";
            $out .= Format::list(array_map(fn ($r) => Format::link($r[0], $r[1]), $this->related)) . "\n";
        }

        return $out;
    }
}
