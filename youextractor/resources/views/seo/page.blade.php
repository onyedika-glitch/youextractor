<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.favicon')
    @include('partials.seo', [
        'title' => $page['title'],
        'description' => $page['description'],
        'keywords' => $page['keywords'] ?? ($page['h1'] . ', YouExtractor, YouTube code extractor, extract code from YouTube, AI code extractor, youextractor.me'),
    ])
    @php
        $base = rtrim(config('app.url') ?: 'https://youextractor.me', '/');
        $url = $base . $canonicalPath;
        $faqEntities = [];
        foreach ($page['faqs'] ?? [] as $faq) {
            $faqEntities[] = [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ];
        }
        $graph = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebPage',
                    '@id' => $url . '#page',
                    'url' => $url,
                    'name' => $page['h1'],
                    'headline' => $page['h1'],
                    'description' => $page['description'],
                    'isPartOf' => ['@id' => $base . '/#website'],
                    'about' => ['@id' => $base . '/#software'],
                ],
                [
                    '@type' => 'SoftwareApplication',
                    '@id' => $base . '/#software',
                    'name' => 'YouExtractor',
                    'applicationCategory' => 'DeveloperApplication',
                    'operatingSystem' => 'Web',
                    'url' => $base . '/',
                    'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'YouExtractor', 'item' => $base . '/'],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Tools', 'item' => $base . '/tools'],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $page['h1'], 'item' => $url],
                    ],
                ],
            ],
        ];
        if ($faqEntities) {
            $graph['@graph'][] = [
                '@type' => 'FAQPage',
                'mainEntity' => $faqEntities,
            ];
        }
        if (($kind ?? '') === 'tool') {
            $graph['@graph'][] = [
                '@type' => 'HowTo',
                'name' => $page['h1'],
                'description' => $page['description'],
                'step' => [
                    ['@type' => 'HowToStep', 'position' => 1, 'name' => 'Paste a public coding tutorial URL', 'text' => 'Open YouExtractor and paste any public YouTube programming tutorial link.'],
                    ['@type' => 'HowToStep', 'position' => 2, 'name' => 'Let the AI rebuild the project', 'text' => 'YouExtractor reads the transcript and reconstructs files, folders, and a written guide.'],
                    ['@type' => 'HowToStep', 'position' => 3, 'name' => 'Download or push to GitHub', 'text' => 'Export a ZIP or create a new GitHub repository from the extraction.'],
                ],
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="stylesheet" href="/css/youextractor-design-system.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <style>
        body {
            font-family: var(--theme-font-sans);
            background: var(--ds-surface-base);
            color: var(--ds-text-primary);
            margin: 0;
            min-height: 100vh;
        }
        .seo-wrap { max-width: 820px; margin: 0 auto; padding: 32px 24px 80px; }
        .seo-nav {
            display: flex; align-items: center; justify-content: space-between;
            padding-bottom: 16px; border-bottom: 1px solid var(--ds-border-subtle); margin-bottom: 28px;
        }
        .seo-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--ds-text-primary); font-weight: 700; }
        .crumbs { font-size: 13px; color: var(--ds-text-muted); margin-bottom: 18px; }
        .crumbs a { color: var(--ds-text-secondary); text-decoration: none; }
        .eyebrow { font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ds-text-brand); margin: 0 0 10px; }
        h1 { font-size: 2rem; line-height: 1.15; margin: 0 0 16px; letter-spacing: -.02em; }
        .lede { font-size: 1.05rem; color: var(--ds-text-secondary); line-height: 1.65; margin: 0 0 28px; }
        .doc h2 { font-size: 1.25rem; margin: 2em 0 .6em; }
        .doc p, .doc li { color: var(--ds-text-secondary); line-height: 1.7; }
        .doc p { margin: 0 0 1em; }
        .doc ul { padding-left: 1.2em; margin: 0 0 1.4em; }
        .doc li { margin-bottom: .4em; }
        .cta-row { display: flex; gap: 12px; flex-wrap: wrap; margin: 28px 0 8px; }
        .cta-row a { text-decoration: none; }
        .faq details {
            border: 1px solid var(--ds-border-subtle); border-radius: 12px;
            padding: 14px 16px; margin-bottom: 10px; background: var(--ds-surface-card, #fff);
        }
        .faq summary { cursor: pointer; font-weight: 600; }
        .faq details p { margin: 10px 0 0; color: var(--ds-text-secondary); }
        .related { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 12px; }
        .related a {
            display: block; text-decoration: none; color: var(--ds-text-primary);
            border: 1px solid var(--ds-border-subtle); border-radius: 12px; padding: 14px 16px;
        }
        .related a:hover { border-color: rgba(20,184,166,.4); }
        .related span { display: block; font-size: 12px; color: var(--ds-text-muted); margin-top: 4px; }
    </style>
</head>
<body>
    <div class="seo-wrap">
        <div class="seo-nav">
            <a class="seo-logo" href="{{ route('landing') }}">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" width="28" height="28" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,.25);">
                YouExtractor
            </a>
            <a href="{{ route('signup') }}" style="text-decoration:none;">
                <ds-button label="Start free" variant="primary" size="sm"></ds-button>
            </a>
        </div>

        <nav class="crumbs" aria-label="Breadcrumb">
            <a href="{{ route('landing') }}">YouExtractor</a> ·
            <a href="{{ route('tools.index') }}">Tools</a> ·
            {{ $page['h1'] }}
        </nav>

        <p class="eyebrow">{{ $page['eyebrow'] ?? 'YouExtractor tool' }}</p>
        <h1>{{ $page['h1'] }}</h1>
        <p class="lede">{{ $page['intro'] }}</p>

        <div class="cta-row">
            <a href="{{ route('signup') }}"><ds-button label="Extract a tutorial free" variant="primary" size="md"></ds-button></a>
            <a href="{{ route('landing') }}"><ds-button label="See how it works" variant="secondary" size="md"></ds-button></a>
        </div>

        <div class="doc">
            @if(!empty($page['bullets']))
                <h2>What you get</h2>
                <ul>
                    @foreach($page['bullets'] as $bullet)
                        <li>{{ $bullet }}</li>
                    @endforeach
                </ul>
            @endif

            @foreach($page['sections'] ?? [] as $section)
                <h2>{{ $section['h2'] }}</h2>
                @foreach($section['body'] ?? [] as $para)
                    <p>{{ $para }}</p>
                @endforeach
            @endforeach
        </div>

        @if(!empty($page['faqs']))
            <h2 class="ds-type-heading-sm" style="margin-top:2.4em;">Frequently asked questions</h2>
            <div class="faq">
                @foreach($page['faqs'] as $faq)
                    <details open>
                        <summary>{{ $faq['q'] }}</summary>
                        <p>{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        @endif

        @if(!empty($related) || !empty($relatedStacks))
            <h2 class="ds-type-heading-sm" style="margin-top:2.4em;">Related YouExtractor tools</h2>
            <div class="related">
                @foreach($related as $item)
                    <a href="{{ $item['href'] }}">{{ $item['h1'] }}<span>YouExtractor tool</span></a>
                @endforeach
                @foreach($relatedStacks as $item)
                    <a href="{{ $item['href'] }}">{{ $item['h1'] }}<span>Stack guide</span></a>
                @endforeach
            </div>
        @endif
    </div>

    @include('partials.footer')
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
