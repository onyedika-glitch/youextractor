<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.favicon')
    @include('partials.seo', [
        'title' => 'About YouExtractor — the AI YouTube-to-code tool',
        'description' => 'YouExtractor is the official AI product at youextractor.me. It turns YouTube coding tutorials into runnable source-code projects. It is not a YouTube tags or metadata extractor.',
        'keywords' => 'about YouExtractor, YouExtractor AI, youextractor.me, YouTube to code, AI YouTube-to-code tool, not a YouTube tags extractor, Omogo Peter Onyedika, extract code from YouTube tutorial, YouExtractor brand',
    ])
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    @php
        $base = rtrim(config('app.url') ?: 'https://youextractor.me', '/');
        $aboutGraph = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'AboutPage',
                    '@id' => $base . '/about#page',
                    'url' => $base . '/about',
                    'name' => 'About YouExtractor',
                    'description' => 'Official about page for YouExtractor, the AI YouTube-to-code tool.',
                    'isPartOf' => ['@id' => $base . '/#website'],
                    'about' => ['@id' => $base . '/#organization'],
                ],
                [
                    '@type' => 'Organization',
                    '@id' => $base . '/#organization',
                    'name' => 'YouExtractor',
                    'alternateName' => ['youextractor', 'You Extractor', 'YouExtractor.me', 'YouExtractor AI'],
                    'url' => $base . '/',
                    'logo' => $base . '/img/youextractor-logo.png',
                    'disambiguatingDescription' => 'AI tool that turns YouTube coding tutorials into runnable source-code projects. Not a YouTube tags or metadata extractor.',
                    'sameAs' => [
                        'https://github.com/onyedika-glitch/youextractor',
                        'https://chromewebstore.google.com/detail/youextractor/ihajahjkhnelimamilebbcjibbhghbcn',
                        'https://devomogo.tech',
                        'https://productwatch.io/products/youextractor',
                    ],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'YouExtractor', 'item' => $base . '/'],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => $base . '/about'],
                    ],
                ],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($aboutGraph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="stylesheet" href="/css/youextractor-design-system.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <style>
        body {
            font-family: var(--theme-font-sans);
            background: var(--ds-surface-base);
            color: var(--ds-text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }
        .container {
            width: 100%;
            max-width: 800px;
            padding: var(--theme-spacing-8) var(--theme-spacing-6);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-6);
        }
        .header-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--ds-border-subtle);
            padding-bottom: var(--theme-spacing-4);
        }
        .logo {
            display: inline-flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
        }
        .doc-content h1, .doc-content h2 {
            color: var(--ds-text-primary);
            margin-top: var(--theme-spacing-6);
        }
        .doc-content p, .doc-content li {
            color: var(--ds-text-secondary);
            line-height: 1.65;
        }
        .doc-content ul {
            padding-left: var(--theme-spacing-5);
        }
        .doc-content li {
            margin-bottom: var(--theme-spacing-2);
        }
        .doc-content a {
            color: var(--ds-text-brand);
            text-decoration: none;
        }
        .doc-content a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <a href="{{ route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" width="28" height="28" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);">
                <span class="ds-type-heading-sm" style="margin: 0; font-size: 1.25rem;">YouExtractor</span>
            </a>
            <a href="{{ route('landing') }}" style="text-decoration: none;">
                <ds-button label="Back" variant="glow" size="sm"></ds-button>
            </a>
        </div>

        <ds-card variant="glass" padding="xl" style="margin-top: 4vh;">
            <div class="doc-content">
                <h1 class="ds-type-heading-md" style="margin-top: 0;">About YouExtractor</h1>
                <p>
                    <strong>YouExtractor</strong> is an AI developer tool at
                    <a href="{{ url('/') }}">youextractor.me</a>.
                    Paste a public YouTube coding tutorial and YouExtractor rebuilds a complete,
                    runnable source-code project — files, folders, dependencies, a written guide,
                    and a learning roadmap.
                </p>

                <h2 class="ds-type-heading-sm">The official brand</h2>
                <p>
                    The product name is one word: <strong>YouExtractor</strong>.
                    People also search <em>youextractor</em> or <em>You Extractor</em>.
                    The official website is <strong>https://youextractor.me</strong>.
                </p>
                <p>
                    YouExtractor is <strong>not</strong> a YouTube tags extractor, title copier,
                    thumbnail downloader, or SEO metadata tool. Those products are often listed
                    when Google rewrites the query “youextractor” to “yt extractor”.
                    YouExtractor extracts <em>source code from programming tutorials</em>.
                </p>

                <h2 class="ds-type-heading-sm">What you get</h2>
                <ul>
                    <li>A reconstructed project from a public coding video</li>
                    <li>Folder structure, config files, and setup notes</li>
                    <li>A written guide plus a checklist roadmap</li>
                    <li>ZIP download or a one-click GitHub push</li>
                    <li>A Chrome extension on the YouTube watch page</li>
                </ul>

                <h2 class="ds-type-heading-sm">Who built it</h2>
                <p>
                    YouExtractor is built by
                    <a href="https://devomogo.tech" target="_blank" rel="noopener">Omogo Peter Onyedika</a>.
                    The source and issue tracker live on
                    <a href="https://github.com/onyedika-glitch/youextractor" target="_blank" rel="noopener">GitHub</a>.
                </p>

                <h2 class="ds-type-heading-sm">Links</h2>
                <ul>
                    <li><a href="{{ url('/') }}">Homepage — youextractor.me</a></li>
                    <li><a href="{{ route('blog.show', 'what-is-youextractor') }}">What is YouExtractor?</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="https://chromewebstore.google.com/detail/youextractor/ihajahjkhnelimamilebbcjibbhghbcn" target="_blank" rel="noopener">Chrome extension</a></li>
                    <li><a href="{{ route('support') }}">Support</a></li>
                    <li><a href="{{ route('signup') }}">Create a free account</a></li>
                </ul>
            </div>
        </ds-card>
    </div>

    @include('partials.footer')
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
