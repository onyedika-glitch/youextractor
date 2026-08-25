<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.favicon')
    @include('partials.seo', [
        'title' => 'YouExtractor Tools — YouTube Code Extractor, GitHub Export & Guides',
        'description' => 'Free YouExtractor developer tools: extract code from YouTube tutorials, push projects to GitHub, and follow along with React, Python, Next.js, Laravel, and more.',
        'keywords' => 'YouExtractor tools, YouTube code extractor, YouTube to GitHub, AI code extractor, extract React code, extract Python code, extract Next.js code, extract Laravel code, extract Docker compose, copy code from coding video, learn programming faster, follow along coding tutorials, youextractor.me tools',
    ])
    @php
        $base = rtrim(config('app.url') ?: 'https://youextractor.me', '/');
        $items = [];
        $i = 1;
        foreach ($tools as $slug => $tool) {
            $items[] = ['@type' => 'ListItem', 'position' => $i++, 'url' => $base . '/tools/' . $slug, 'name' => $tool['h1']];
        }
        foreach ($stacks as $slug => $stack) {
            $items[] = ['@type' => 'ListItem', 'position' => $i++, 'url' => $base . '/for/' . $slug, 'name' => $stack['h1']];
        }
        $graph = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'YouExtractor Tools',
            'url' => $base . '/tools',
            'description' => 'Directory of YouExtractor developer tools and stack guides.',
            'mainEntity' => ['@type' => 'ItemList', 'itemListElement' => $items],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    <link rel="stylesheet" href="/css/youextractor-design-system.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: var(--theme-font-sans); background: var(--ds-surface-base); color: var(--ds-text-primary); margin: 0; }
        .wrap { max-width: 980px; margin: 0 auto; padding: 32px 24px 80px; }
        .seo-nav { display:flex; align-items:center; justify-content:space-between; padding-bottom:16px; border-bottom:1px solid var(--ds-border-subtle); margin-bottom:32px; }
        .seo-logo { display:flex; align-items:center; gap:10px; text-decoration:none; color: var(--ds-text-primary); font-weight:700; }
        h1 { font-size: 2rem; letter-spacing:-.02em; margin: 0 0 12px; }
        .lede { color: var(--ds-text-secondary); max-width: 62ch; line-height: 1.65; margin-bottom: 36px; }
        h2 { font-size: 1.15rem; margin: 2em 0 .8em; }
        .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px; }
        .card {
            display:block; text-decoration:none; color: inherit;
            border: 1px solid var(--ds-border-subtle); border-radius: 14px; padding: 18px 18px 16px;
            background: var(--ds-surface-card, #fff);
        }
        .card:hover { border-color: rgba(20,184,166,.45); }
        .card strong { display:block; margin-bottom: 8px; }
        .card p { margin:0; font-size: 14px; color: var(--ds-text-secondary); line-height: 1.5; }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="seo-nav">
            <a class="seo-logo" href="{{ route('landing') }}">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" width="28" height="28" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,.25);">
                YouExtractor
            </a>
            <a href="{{ route('signup') }}" style="text-decoration:none;"><ds-button label="Start free" variant="primary" size="sm"></ds-button></a>
        </div>

        <p style="font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--ds-text-brand); margin:0 0 10px;">Free developer tools</p>
        <h1>YouExtractor Tools — YouTube Code Extractor, GitHub Export &amp; Guides</h1>
        <p class="lede">
            Unique tools and stack guides. Each page is a real result Google can show — like “Extract Code from YouTube Tutorials” or “Extract React Code” — not a YouTube tags inspector.
        </p>

        <h2>Extractor tools</h2>
        <div class="grid">
            @foreach($tools as $slug => $tool)
                <a class="card" href="{{ url('/tools/'.$slug) }}">
                    <strong>{{ $tool['h1'] }}</strong>
                    <p>{{ $tool['description'] }}</p>
                </a>
            @endforeach
        </div>

        <h2>Extract tutorials by stack</h2>
        <div class="grid">
            @foreach($stacks as $slug => $stack)
                <a class="card" href="{{ url('/for/'.$slug) }}">
                    <strong>{{ $stack['h1'] }}</strong>
                    <p>{{ $stack['description'] }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @include('partials.footer')
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
