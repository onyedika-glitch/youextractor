<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post['title'] }} • YouExtractor</title>
    <meta name="description" content="{{ $post['excerpt'] ?? Str::limit(strip_tags($post['content']), 160) }}">
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <style>
        body {
            font-family: var(--theme-font-sans);
            background: var(--ds-surface-base);
            color: var(--ds-text-primary);
            line-height: 1.75;
        }
        .article-container {
            max-width: 720px;
            margin: 0 auto;
            padding: 0 var(--theme-spacing-6);
        }
        .article-header {
            padding: 40px 0 32px;
            border-bottom: 1px solid var(--ds-border-subtle);
            margin-bottom: 40px;
        }
        .article-content {
            font-size: 16.2px;
            color: var(--ds-text-secondary);
        }
        .article-content h2, .article-content h3 {
            color: var(--ds-text-primary);
            margin-top: 2.2em;
            margin-bottom: 0.6em;
        }
        .article-content h2 { font-size: 1.45rem; }
        .article-content h3 { font-size: 1.2rem; }
        .article-content p {
            margin-bottom: 1.3em;
        }
        .article-content ul, .article-content ol {
            margin-bottom: 1.4em;
            padding-left: 1.3em;
        }
        .article-content pre {
            background: rgba(0,0,0,0.35);
            padding: 18px;
            border-radius: var(--theme-radius-lg);
            overflow-x: auto;
            font-size: 13.5px;
            margin: 1.6em 0;
        }
        .article-content code {
            font-family: var(--theme-font-mono);
            background: rgba(255,255,255,0.06);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.92em;
        }
        .article-content pre code {
            background: transparent;
            padding: 0;
        }
        .reading-meta {
            display: flex;
            gap: 14px;
            font-size: 13px;
            color: var(--ds-text-muted);
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="article-container">
        <!-- Top bar -->
        <div style="padding: 24px 0 8px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('blog.index') }}" style="display: inline-flex; align-items: center; gap: 6px; color: var(--ds-text-muted); font-size: 14px; text-decoration: none;">
                <i class="ph ph-arrow-left"></i>
                <span>Back to Blog</span>
            </a>
            <a href="{{ route('landing') }}" style="font-size: 13px; color: var(--ds-text-muted); text-decoration: none;">
                youextractor.me
            </a>
        </div>

        <!-- Article Header -->
        <div class="article-header">
            <div class="reading-meta">
                <span>{{ \Carbon\Carbon::parse($post['date'])->format('F j, Y') }}</span>
                <span>•</span>
                <span>{{ $post['reading_time'] ?? 5 }} min read</span>
            </div>

            <h1 style="margin: 12px 0 0; font-size: 2.1rem; line-height: 1.15; font-weight: 700; color: var(--ds-text-primary);">
                {{ $post['title'] }}
            </h1>

            @if(!empty($post['excerpt']))
                <p style="margin: 16px 0 0; font-size: 15.5px; color: var(--ds-text-secondary); max-width: 62ch;">
                    {{ $post['excerpt'] }}
                </p>
            @endif
        </div>

        <!-- Article Body -->
        <article class="article-content">
            {!! $post['content'] !!}
        </article>

        <!-- Footer CTA -->
        <div style="margin-top: 70px; padding: 32px 0; border-top: 1px solid var(--ds-border-subtle); text-align: center;">
            <p style="color: var(--ds-text-secondary); margin-bottom: 16px; font-size: 14px;">
                Enjoyed this? Try turning your next YouTube tutorial into a real project.
            </p>
            <a href="{{ route('landing') }}" style="text-decoration: none;">
                <ds-button label="Start extracting for free" variant="gradient" size="md" icon="rocket-launch" icon-position="right"></ds-button>
            </a>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>