<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog • YouExtractor</title>
    <meta name="description" content="Articles about AI-assisted learning, building developer tools, and turning passive video watching into active building.">
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
        }
        .blog-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 var(--theme-spacing-6);
        }
        .blog-nav {
            padding: 20px 0;
            border-bottom: 1px solid var(--ds-border-subtle);
            margin-bottom: 40px;
        }
        .blog-header {
            text-align: center;
            padding: 40px 0 60px;
        }
        .blog-card {
            transition: transform 0.2s var(--theme-ease-out), box-shadow 0.2s var(--theme-ease-out);
        }
        .blog-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0,0,0,0.3);
        }
        .post-meta {
            font-size: 12px;
            color: var(--ds-text-muted);
            display: flex;
            gap: 12px;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="blog-container">
        <!-- Top Navigation -->
        <div class="blog-nav">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <a href="{{ route('landing') }}" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--ds-text-primary);">
                    <img src="/img/youextractor-logo.png" alt="YouExtractor logo" width="24" height="24" style="width:24px;height:24px;border-radius:5px;object-fit:cover;border:1px solid rgba(168,85,247,0.25);">
                    <span style="font-weight: 700; font-size: 1.05rem;">YouExtractor</span>
                </a>
                <div style="display: flex; gap: 16px; font-size: 14px;">
                    <a href="{{ route('landing') }}#how" style="color: var(--ds-text-secondary); text-decoration: none;">How it works</a>
                    <a href="{{ route('landing') }}#demo" style="color: var(--ds-text-secondary); text-decoration: none;">Demo</a>
                    <a href="{{ route('landing') }}" style="color: var(--ds-text-secondary); text-decoration: none;">Home</a>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="blog-header">
            <div class="ds-badge-brand" style="margin-bottom: 16px;">From the team</div>
            <h1 class="ds-type-display-sm" style="margin: 0 0 12px;">Blog</h1>
            <p style="max-width: 520px; margin: 0 auto; color: var(--ds-text-secondary); font-size: 15px;">
                Deep dives into how we build AI tools that help developers learn faster by doing instead of just watching.
            </p>
        </div>

        <!-- Posts Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 28px; margin-bottom: 80px;">
            @foreach($posts as $post)
                <a href="{{ route('blog.show', $post['slug']) }}" style="text-decoration: none; color: inherit;">
                    <ds-card variant="glass" padding="lg" class="blog-card" style="height: 100%; display: flex; flex-direction: column;">
                        <div class="post-meta" style="margin-bottom: 12px;">
                            <span>{{ \Carbon\Carbon::parse($post['date'])->format('M j, Y') }}</span>
                            <span style="opacity: 0.4;">•</span>
                            <span>{{ $post['reading_time'] ?? 5 }} min read</span>
                        </div>

                        <h3 class="ds-type-heading-sm" style="margin: 0 0 12px; line-height: 1.25; color: var(--ds-text-primary);">
                            {{ $post['title'] }}
                        </h3>

                        <p style="color: var(--ds-text-secondary); font-size: 14px; line-height: 1.55; flex: 1; margin: 0;">
                            {{ $post['excerpt'] }}
                        </p>

                        <div style="margin-top: 20px; display: flex; align-items: center; gap: 6px; color: var(--ds-text-brand); font-size: 13px; font-weight: 600;">
                            Read article
                            <i class="ph ph-arrow-right"></i>
                        </div>
                    </ds-card>
                </a>
            @endforeach
        </div>

        <div style="text-align: center; padding: 40px 0 60px; color: var(--ds-text-muted); font-size: 13px;">
            More articles coming soon. Have a suggestion?
            <a href="https://buymeacoffee.com/omogo" target="_blank" style="color: var(--ds-text-brand);">Tell us</a>.
        </div>
    </div>
</body>
</html>