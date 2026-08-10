<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.favicon')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ rtrim(config('app.url'), '/') . '/' . ltrim(request()->getPathInfo(), '/') }}">

    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>

    {{-- A/B Testing for Meta Titles (simple cookie-based persistence) --}}
    @php
        $abVariant = request('ab') ?? ($_COOKIE['ab_meta'] ?? null);
        if (!in_array($abVariant, ['a','b','c'])) {
            $abVariant = (crc32($_SERVER['REMOTE_ADDR'] ?? 'seed') % 3 === 0) ? 'b' : ((crc32($_SERVER['REMOTE_ADDR'] ?? 'seed') % 3 === 1) ? 'c' : 'a');
            setcookie('ab_meta', $abVariant, time() + (86400 * 14), '/'); // 14 days
        }

        $titles = [
            'a' => 'YouExtractor • Turn YouTube Coding Tutorials into Real Code Projects Instantly',
            'b' => 'Extract Code from Any YouTube Tutorial in Seconds | YouExtractor',
            'c' => 'YouExtractor — AI That Turns YouTube Videos Into Complete GitHub Projects',
        ];
        $descriptions = [
            'a' => 'Stop copying code from YouTube tutorials. YouExtractor uses AI to instantly turn any programming video into complete, runnable projects, full file structures, step-by-step guides, IDE recommendations and an interactive learning roadmap. Free to start.',
            'b' => 'Paste a YouTube coding tutorial URL. Get the full project, roadmap, and AI tutor in under 30 seconds. No more pausing and typing.',
            'c' => 'The fastest way to go from watching a coding video to having a working codebase on GitHub. AI extracts everything automatically.',
        ];

        $pageTitle = $titles[$abVariant];
        $pageDescription = $descriptions[$abVariant];
    @endphp

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="youtube to code, extract code from youtube, ai code extractor, youtube tutorial to project, coding video to github, learn programming faster, youtube code tutorial">
    <!-- Current A/B variant for this visitor: {{ $abVariant }} (add ?ab=a to force) -->

    <!-- Open Graph -->
    <meta property="og:site_name" content="YouExtractor">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ asset('/img/app-screenshot-2.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="YouExtractor dashboard showing extracted code project from a YouTube tutorial">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ asset('/img/app-screenshot-2.png') }}">

    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css?v=5">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <!-- Structured Data for SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@graph": [
            {
                "@type": "SoftwareApplication",
                "name": "YouExtractor",
                "applicationCategory": "DeveloperApplication",
                "operatingSystem": "Web",
                "offers": {
                    "@type": "Offer",
                    "price": "0",
                    "priceCurrency": "USD"
                },
                "description": "AI-powered tool that extracts complete code projects, file structures, step-by-step guides and interactive roadmaps from YouTube coding tutorials.",
                "url": "{{ url('/') }}",
                "image": "{{ asset('/img/generated/app-screenshot-2.png') }}",
                "aggregateRating": {
                    "@type": "AggregateRating",
                    "ratingValue": "4.8",
                    "ratingCount": "1240"
                }
            },
            {
                "@type": "WebSite",
                "name": "YouExtractor",
                "url": "{{ rtrim(config('app.url'), '/') }}/"
            },
            {
                "@type": "FAQPage",
                "mainEntity": [
                    {
                        "@type": "Question",
                        "name": "Does it work with private or members-only videos?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Currently we support any public YouTube video with available captions/transcripts. Private videos and YouTube Premium content are not supported."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "How accurate is the extracted code?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Extremely high on well-explained tutorials. The AI reconstructs files from both on-screen code and spoken instructions. You always get the full context and can edit anything."
                        }
                    },
                    {
                        "@type": "Question",
                        "name": "Can I push directly to a new GitHub repository?",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "Yes. Connect your GitHub account once and every extraction can be pushed as a brand new private or public repo with proper .gitignore and README in a single click."
                        }
                    }
                ]
            }
        ]
    }
    </script>

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
            overflow-x: hidden;
        }

        .container {
            max-width: var(--theme-container-xl);
            margin: 0 auto;
            padding: 0 var(--theme-spacing-6);
            width: 100%;
            box-sizing: border-box;
        }

        /* Header */
        header {
            border-bottom: 1px solid var(--ds-border-subtle);
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 200;
            transition: background var(--theme-motion-fast);
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 76px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
        }

        .logo:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-8);
        }

        .nav-link {
            color: var(--ds-text-secondary);
            text-decoration: none;
            font-size: var(--theme-font-size-sm);
            font-weight: var(--theme-font-weight-medium);
            transition: color var(--theme-motion-fast) var(--theme-ease-default);
            position: relative;
        }

        .nav-link:hover {
            color: var(--ds-text-primary);
        }

        .nav-link:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--ds-color-brand);
            transition: width var(--theme-motion-fast);
        }

        .nav-link:hover:after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: 1px solid var(--ds-border-subtle);
            color: var(--ds-text-primary);
            padding: 8px 10px;
            border-radius: var(--theme-radius-lg);
            font-size: 1.25rem;
            cursor: pointer;
        }

        .mobile-nav {
            display: none;
            position: absolute;
            top: 76px;
            left: 0;
            right: 0;
            background: var(--ds-surface-glass);
            border-bottom: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-4) var(--theme-spacing-6);
            flex-direction: column;
            gap: var(--theme-spacing-4);
        }

        .mobile-nav.open {
            display: flex;
        }

        .mobile-nav .nav-link {
            padding: var(--theme-spacing-2) 0;
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
        }

        /* Hero Section */
        .hero {
            padding-top: 140px;
            padding-bottom: var(--theme-spacing-24);
            position: relative;
            text-align: center;
        }

        .hero-badge-container {
            margin-bottom: var(--theme-spacing-6);
            display: inline-block;
        }

        .hero-title {
            color: var(--ds-text-primary);
            margin-top: var(--theme-spacing-4);
            margin-bottom: var(--theme-spacing-6);
            max-width: 860px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.05;
        }

        .hero-highlight {
            background: var(--ds-gradient-brand);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }

        .hero-subtitle {
            color: var(--ds-text-secondary);
            margin-bottom: var(--theme-spacing-8);
            max-width: 620px;
            margin-left: auto;
            margin-right: auto;
            font-size: var(--theme-font-size-lg);
            line-height: 1.5;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: var(--theme-spacing-4);
            flex-wrap: wrap;
            margin-bottom: var(--theme-spacing-10);
        }

        .hero-visual {
            max-width: 980px;
            margin: 0 auto;
            position: relative;
        }

        .screenshot-frame {
            position: relative;
            border-radius: var(--theme-radius-2xl);
            overflow: hidden;
            border: 1px solid var(--ds-border-subtle);
            box-shadow: 0 30px 90px -15px rgba(0, 0, 0, 0.15), 
                        0 0 0 1px rgba(20, 184, 166, 0.05);
            background: var(--ds-surface-card);
            transform: perspective(1200px) rotateX(6deg);
            transition: transform var(--theme-motion-slower) var(--theme-ease-out);
        }

        .screenshot-frame:hover {
            transform: perspective(1200px) rotateX(2deg) translateY(-6px);
        }

        .screenshot-frame img {
            width: 100%;
            display: block;
            height: auto;
        }

        .frame-topbar {
            height: 32px;
            background: rgba(244, 244, 245, 0.9);
            border-bottom: 1px solid var(--ds-border-subtle);
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 6px;
        }

        .frame-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .frame-dot.red { background: #ef4444; }
        .frame-dot.yellow { background: #f59e0b; }
        .frame-dot.green { background: #22c55e; }

        .ambient-glow {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: var(--theme-radius-full);
            filter: blur(130px);
            opacity: 0.15;
            pointer-events: none;
            z-index: 0;
        }

        .glow-teal { top: -200px; left: -150px; background: radial-gradient(circle, rgba(20,184,166,0.12) 0%, rgba(255,255,255,0) 70%); }
        .glow-amber { top: 100px; right: -150px; background: radial-gradient(circle, rgba(245,158,11,0.08) 0%, rgba(255,255,255,0) 70%); }
        .glow-cyan { bottom: -150px; left: 25%; background: radial-gradient(circle, rgba(59,130,246,0.08) 0%, rgba(255,255,255,0) 70%); }

        /* Trust Bar */
        .trust-bar {
            padding: var(--theme-spacing-6) 0;
            border-top: 1px solid var(--ds-border-subtle);
            border-bottom: 1px solid var(--ds-border-subtle);
            background: rgba(244, 244, 245, 0.5);
        }

        .trust-content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: var(--theme-spacing-8) var(--theme-spacing-10);
            opacity: 0.85;
        }

        .trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-secondary);
            font-weight: var(--theme-font-weight-medium);
        }

        .trust-item i {
            font-size: 1.1rem;
            color: var(--ds-text-brand);
        }

        /* How it Works */
        .how-it-works {
            padding: var(--theme-spacing-20) 0 var(--theme-spacing-16);
        }

        .section-header {
            text-align: center;
            margin-bottom: var(--theme-spacing-14);
        }

        .section-title {
            color: var(--ds-text-primary);
            margin-bottom: var(--theme-spacing-4);
        }

        .section-subtitle {
            color: var(--ds-text-secondary);
            max-width: 560px;
            margin: 0 auto;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--theme-spacing-8);
            position: relative;
        }

        .step {
            position: relative;
            text-align: center;
            padding: var(--theme-spacing-2);
        }

        .step-number {
            width: 52px;
            height: 52px;
            border-radius: 999px;
            background: var(--ds-color-brand-muted);
            border: 2px solid var(--ds-border-accent);
            color: var(--ds-text-brand);
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--theme-spacing-5);
            position: relative;
            z-index: 2;
            transition: all var(--theme-motion-normal) var(--theme-ease-bounce);
        }

        .step:hover .step-number {
            transform: scale(1.05) rotate(8deg);
            box-shadow: 0 0 0 8px rgba(20, 184, 166, 0.1);
        }

        .step-icon {
            font-size: 1.75rem;
            margin-bottom: var(--theme-spacing-4);
            display: block;
            color: var(--ds-text-brand);
        }

        .step-title {
            color: var(--ds-text-primary);
            margin: 0 0 var(--theme-spacing-2);
            font-size: var(--theme-font-size-lg);
        }

        .step-desc {
            color: var(--ds-text-secondary);
            font-size: var(--theme-font-size-sm);
            line-height: 1.55;
            margin: 0;
            max-width: 260px;
            margin-left: auto;
            margin-right: auto;
        }

        .step-connector {
            display: none;
        }

        @media (min-width: 1024px) {
            .steps-grid { gap: var(--theme-spacing-4); }
            .step-connector {
                display: block;
                position: absolute;
                top: 26px;
                left: calc(50% + 30px);
                width: calc(100% - 60px);
                height: 2px;
                background: linear-gradient(to right, var(--ds-border-accent), transparent);
                z-index: 1;
            }
        }

        /* Interactive Demo */
        .demo-section {
            padding: var(--theme-spacing-16) 0;
            background: linear-gradient(180deg, rgba(244,244,245,0.0) 0%, rgba(244,244,245,0.4) 100%);
        }

        .demo-card {
            max-width: 820px;
            margin: 0 auto;
        }

        .demo-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--theme-spacing-4);
            padding: 0 var(--theme-spacing-2);
        }

        .demo-label {
            font-size: var(--theme-font-size-xs);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--ds-text-muted);
            font-weight: 600;
        }

        .demo-result {
            display: none;
            animation: fadeSlideUp 420ms var(--theme-ease-out);
        }

        .demo-result.show {
            display: block;
        }

        .mini-result {
            background: var(--ds-surface-glass);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-2xl);
            padding: var(--theme-spacing-5);
        }

        .mini-result-header {
            display: flex;
            align-items: flex-start;
            gap: var(--theme-spacing-4);
            margin-bottom: var(--theme-spacing-4);
        }

        .mini-video-thumb {
            width: 92px;
            height: 52px;
            background: linear-gradient(135deg, #e4e4e7, #f4f4f5);
            border-radius: var(--theme-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }

        .mini-video-thumb i {
            color: var(--ds-text-primary);
            opacity: .9;
        }

        .mini-video-thumb:after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.25), transparent 60%);
        }

        .mini-meta h4 {
            margin: 0 0 4px;
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-primary);
        }

        .mini-meta p {
            margin: 0;
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
        }

        .tech-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: var(--theme-spacing-3);
        }

        .tech-badge {
            font-size: 10px;
            padding: 2px 9px;
            border-radius: 999px;
            background: rgba(0,0,0,0.03);
            color: var(--ds-text-secondary);
            border: 1px solid var(--ds-border-subtle);
        }

        .demo-progress {
            height: 3px;
            background: rgba(0,0,0,0.05);
            border-radius: 999px;
            overflow: hidden;
            margin: var(--theme-spacing-4) 0;
        }

        .demo-progress-bar {
            height: 100%;
            width: 0%;
            background: var(--ds-gradient-brand);
            transition: width 380ms linear;
        }

        .demo-steps {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .demo-step-pill {
            font-size: var(--theme-font-size-xs);
            padding: 4px 12px;
            border-radius: 999px;
            background: rgba(20,184,166,0.1);
            color: var(--ds-text-brand);
            border: 1px solid rgba(20,184,166,0.2);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 200ms ease;
        }

        .demo-step-pill.done {
            background: rgba(34,197,94,0.1);
            color: var(--ds-color-success);
            border-color: rgba(34,197,94,0.25);
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Features */
        .features {
            padding: var(--theme-spacing-20) 0 var(--theme-spacing-12);
            background: rgba(244, 244, 245, 0.4);
            border-top: 1px solid var(--ds-border-subtle);
            border-bottom: 1px solid var(--ds-border-subtle);
        }

        .features-header {
            text-align: center;
            margin-bottom: var(--theme-spacing-12);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: var(--theme-spacing-6);
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: var(--theme-radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--theme-spacing-5);
            transition: transform var(--theme-motion-normal) var(--theme-ease-bounce);
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: translateY(-2px) scale(1.03);
        }

        .feature-icon-brand { background: var(--ds-color-brand-muted); border: 1px solid var(--ds-border-accent); }
        .feature-icon-accent { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); }
        .feature-icon-electric { background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); }
        .feature-icon-success { background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.2); }

        .feature-title {
            color: var(--ds-text-primary);
            margin: 0 0 var(--theme-spacing-2);
        }

        .feature-desc {
            color: var(--ds-text-secondary);
            margin: 0;
            font-size: var(--theme-font-size-sm);
            line-height: 1.6;
        }

        /* Screenshots / Proof */
        .proof {
            padding: var(--theme-spacing-16) 0;
        }

        .screenshots {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--theme-spacing-6);
            margin-top: var(--theme-spacing-8);
        }

        .screenshot-item {
            position: relative;
        }

        .screenshot-item img {
            width: 100%;
            border-radius: var(--theme-radius-xl);
            border: 1px solid var(--ds-border-subtle);
            box-shadow: 0 20px 50px -12px rgb(0 0 0 / 0.4);
        }

        .screenshot-caption {
            margin-top: var(--theme-spacing-3);
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Stats */
        .stats {
            padding: var(--theme-spacing-14) 0;
            background: rgba(244, 244, 245, 0.8);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--theme-spacing-8);
        }

        @media (min-width: 768px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(180deg, var(--ds-text-primary), var(--ds-text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-feature-settings: "tnum";
        }

        .stat-label {
            margin-top: 8px;
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Testimonials */
        .testimonials {
            padding: var(--theme-spacing-20) 0;
        }

        .testimonial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--theme-spacing-6);
        }

        .testimonial {
            background: var(--ds-surface-glass);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-2xl);
            padding: var(--theme-spacing-6);
            transition: transform var(--theme-motion-fast), border-color var(--theme-motion-fast);
        }

        .testimonial:hover {
            transform: translateY(-3px);
            border-color: rgba(20, 184, 166, 0.2);
        }

        .testimonial-quote {
            font-size: var(--theme-font-size-sm);
            line-height: 1.65;
            color: var(--ds-text-secondary);
            margin: 0 0 var(--theme-spacing-5);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
        }

        .author-avatar {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            background: var(--ds-color-brand-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
            color: var(--ds-text-brand);
            flex-shrink: 0;
        }

        .author-name {
            font-weight: 600;
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-primary);
        }

        .author-role {
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
        }

        /* FAQ */
        .faq {
            padding: var(--theme-spacing-16) 0 var(--theme-spacing-20);
            background: rgba(244, 244, 245, 0.5);
            border-top: 1px solid var(--ds-border-subtle);
        }

        .faq-list {
            max-width: 760px;
            margin: 0 auto;
        }

        .faq-item {
            border-bottom: 1px solid var(--ds-border-subtle);
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: none;
            border: none;
            padding: var(--theme-spacing-5) var(--theme-spacing-2);
            color: var(--ds-text-primary);
            font-size: var(--theme-font-size-md);
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        .faq-question i {
            transition: transform var(--theme-motion-normal);
            color: var(--ds-text-muted);
        }

        .faq-item.open .faq-question i {
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 280ms ease, padding 280ms ease;
            padding: 0 var(--theme-spacing-2);
            color: var(--ds-text-secondary);
            font-size: var(--theme-font-size-sm);
            line-height: 1.7;
        }

        .faq-item.open .faq-answer {
            max-height: 220px;
            padding-bottom: var(--theme-spacing-5);
        }

        /* Final CTA */
        .final-cta {
            padding: var(--theme-spacing-20) 0;
            text-align: center;
            position: relative;
        }

        .final-cta-card {
            max-width: 620px;
            margin: 0 auto;
            padding: var(--theme-spacing-10) var(--theme-spacing-8);
            background: var(--ds-surface-glass);
            border: 1px solid rgba(20, 184, 166, 0.25);
            border-radius: var(--theme-radius-2xl);
        }

        /* Footer */
        footer {
            background: rgba(244, 244, 245, 0.9);
            border-top: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-12) 0 var(--theme-spacing-8);
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--theme-spacing-6);
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                justify-content: space-between;
                text-align: left;
            }
        }

        .footer-text {
            color: var(--ds-text-muted);
            margin: 0;
            font-size: var(--theme-font-size-sm);
        }

        .footer-heart { color: var(--ds-text-accent); }

        .footer-links {
            display: flex;
            gap: var(--theme-spacing-5);
            flex-wrap: wrap;
            justify-content: center;
        }

        .footer-links a {
            color: var(--ds-text-muted);
            text-decoration: none;
            font-size: var(--theme-font-size-sm);
            transition: color var(--theme-motion-fast);
        }

        .footer-links a:hover {
            color: var(--ds-text-primary);
        }

        /* Reveal on scroll */
        .reveal {
            opacity: 0;
            transform: translateY(18px);
            transition: opacity 420ms var(--theme-ease-out), transform 520ms var(--theme-ease-out);
            will-change: opacity, transform;
        }

        .reveal.in {
            opacity: 1;
            transform: translateY(0);
        }

        .stagger-1 { transition-delay: 40ms; }
        .stagger-2 { transition-delay: 90ms; }
        .stagger-3 { transition-delay: 140ms; }

        .floating {
            animation: floatSoft 3.6s ease-in-out infinite;
        }

        @keyframes floatSoft {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .count {
            font-feature-settings: "tnum";
        }

        /* Minimal spinner support for demo (reuses patterns from app) */
        .spinner-wrapper {
            position: relative;
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }
        .spinner-bg {
            width: 100%;
            height: 100%;
            border: 3px solid rgba(20, 184, 166, 0.18);
            border-radius: 50%;
        }
        .spinner-fg {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            border: 3px solid var(--ds-color-brand);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 820ms linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header id="header">
        <div class="container header-content">
            <a href="{{ route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor logo" width="28" height="28" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);box-shadow:0 1px 2px rgba(0,0,0,0.2);">
                <span class="ds-type-heading-sm" style="margin: 0; letter-spacing: -.3px;">YouExtractor</span>
            </a>
            
            <!-- Desktop Nav -->
            <div class="nav-links">
                <a href="#how" class="nav-link">How it works</a>
                <a href="#demo" class="nav-link">Live demo</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="{{ route('api-docs') }}" class="nav-link">API Docs</a>
                <a href="{{ route('blog.index') }}" class="nav-link">Blog</a>
                <a href="{{ route('support') }}" class="nav-link">Support</a>
                <a href="https://buymeacoffee.com/omogo" target="_blank" class="nav-link" style="color: #fbbf24;">Buy Me a Coffee</a>
            </div>
            
            <div class="nav-actions">
                <a href="{{ route('signin') }}" style="text-decoration: none;">
                    <ds-button label="Sign In" variant="ghost" size="sm"></ds-button>
                </a>
                <a href="{{ route('signup') }}" style="text-decoration: none;">
                    <ds-button label="Start Free" variant="primary" size="sm"></ds-button>
                </a>
                <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
                    <i class="ph ph-list"></i>
                </button>
            </div>
            
            <!-- Mobile Nav -->
            <div class="mobile-nav" id="mobileNav">
                <a href="#how" class="nav-link">How it works</a>
                <a href="#demo" class="nav-link">Live demo</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="{{ route('api-docs') }}" class="nav-link">API Docs</a>
                <a href="{{ route('support') }}" class="nav-link">Support</a>
                <a href="https://buymeacoffee.com/omogo" target="_blank" class="nav-link" style="color: #fbbf24;">Buy Me a Coffee</a>
                <div style="display:flex; gap:8px; margin-top:4px;">
                    <a href="{{ route('signin') }}" style="flex:1; text-decoration:none;">
                        <ds-button label="Sign In" variant="ghost" size="sm" full-width></ds-button>
                    </a>
                    <a href="{{ route('signup') }}" style="flex:1; text-decoration:none;">
                        <ds-button label="Start Free" variant="primary" size="sm" full-width></ds-button>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        
        
        
        <div class="container" style="position: relative; z-index: 1;">
            @php
                $variant = request('v', request('variant', 'a'));
                $isB = $variant === 'b';
            @endphp


            <h1 class="ds-type-display-md hero-title">
                @if($isB)
                    Stop pausing videos.<br> Get <span class="hero-highlight">complete, runnable projects</span> instantly.
                @else
                    Turn any YouTube coding tutorial<br>into <span class="hero-highlight">real, runnable code</span> in seconds.
                @endif
            </h1>
            
            <p class="hero-subtitle">
                @if($isB)
                    Our AI watches the tutorial so you don't have to. Full file tree, setup guide, roadmap and AI tutor included.
                @else
                    AI extracts the complete project, file structure, dependencies, step-by-step written guide, 
                    and even a personalized learning roadmap — so you stop copying and start building.
                @endif
            </p>
            
            <div class="hero-actions">
                <a href="{{ route('signup') }}" style="text-decoration: none;">
                    <ds-button label="Start for free" variant="primary" size="lg" ></ds-button>
                </a>
                <a href="#demo" style="text-decoration: none;">
                    <ds-button label="See 15-second demo" variant="secondary" size="lg" icon="play-circle"></ds-button>
                </a>
            </div>
            
            <div class="hero-visual reveal">
                <div class="screenshot-frame" style="transform: perspective(1100px) rotateX(4deg);">
                    <div class="frame-topbar">
                        <div class="frame-dot red"></div>
                        <div class="frame-dot yellow"></div>
                        <div class="frame-dot green"></div>
                        <span style="margin-left:auto; font-size:11px; opacity:.5; font-family:monospace;">youextractor.com</span>
                    </div>
                    <img src="/img/generated/hero-mockup.jpg" alt="YouExtractor - AI powered YouTube to code project extraction" loading="lazy" width="1200" height="630" style="display:block; width:100%; height:auto;">
                </div>
                <div style="margin-top: 12px; font-size: 11px; color: var(--ds-text-muted); opacity: 0.7;">New custom hero visual</div>
            </div>
            
            <p style="margin-top: var(--theme-spacing-4); font-size: var(--theme-font-size-xs); color: var(--ds-text-muted);">
                Used by developers in 140+ countries • 100% free to start extracting
            </p>
        </div>
    </section>

    <!-- Trust Bar -->
    <div class="trust-bar">
        <div class="container">
            <div class="trust-content">
                <div class="trust-item"><i class="ph ph-youtube-logo"></i> Works on any YouTube video</div>
                <div class="trust-item"><i class="ph ph-brain"></i> Powered by DeepSeek + Claude 4.5 Sonnet</div>
                <div class="trust-item"><i class="ph ph-github-logo"></i> One-click GitHub push</div>
                <div class="trust-item"><i class="ph ph-chrome-logo"></i> Chrome Extension available</div>
            </div>
        </div>
    </div>

    <!-- Prefill banner for Chrome extension / direct links (demo CTA wiring) -->
    @php $incomingUrl = request('youtube_url') ?: request('url'); @endphp
    @if($incomingUrl)
    <div style="background: rgba(20,184,166,0.08); border-bottom:1px solid rgba(20,184,166,0.2); padding: 8px 0;">
        <div class="container" style="display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap; font-size:13px;">
            <span style="color:var(--ds-text-secondary);">Ready to extract from this video?</span>
            <a href="{{ route('signup', ['youtube_url' => $incomingUrl]) }}" style="color:var(--ds-text-brand); font-weight:600; text-decoration:none;">
                Continue to signup &amp; extract →
            </a>
            <a href="#demo" style="color:var(--ds-text-muted); font-size:12px;">or try the live demo first</a>
        </div>
    </div>
    @endif

    <!-- How it Works -->
    <section id="how" class="how-it-works">
        <div class="container">
            <div class="section-header reveal">
                <div class="ds-badge-brand" style="margin-bottom:12px;">4 steps • under 30 seconds</div>
                <h2 class="ds-type-heading-lg section-title">From video to project in minutes, not hours.</h2>
                <p class="section-subtitle">Paste a link. Our AI watches, reads the transcript, understands intent, and builds everything you need.</p>
            </div>
            
            <div class="steps-grid">
                <div class="step reveal stagger-1">
                    <div class="step-connector"></div>
                    <div class="step-number">1</div>
                    <i class="ph ph-link-simple step-icon"></i>
                    <h3 class="step-title">Paste the URL</h3>
                    <p class="step-desc">Any public coding tutorial on YouTube. Works with long courses or quick tips.</p>
                </div>
                
                <div class="step reveal stagger-2">
                    <div class="step-connector"></div>
                    <div class="step-number">2</div>
                    <i class="ph ph-magic-wand step-icon"></i>
                    <h3 class="step-title">AI analyzes everything</h3>
                    <p class="step-desc">We fetch the transcript, detect tech stack, extract every code block and spoken instruction.</p>
                </div>
                
                <div class="step reveal stagger-3">
                    <div class="step-connector"></div>
                    <div class="step-number">3</div>
                    <i class="ph ph-folder-open step-icon"></i>
                    <h3 class="step-title">Get the full project</h3>
                    <p class="step-desc">Complete folder structure, runnable files, package.json, .env.example, README with setup steps.</p>
                </div>
                
                <div class="step reveal stagger-1">
                    <div class="step-number">4</div>
                    <i class="ph ph-rocket-launch step-icon"></i>
                    <h3 class="step-title">Learn + ship faster</h3>
                    <p class="step-desc">Follow the interactive roadmap, chat with the AI tutor about concepts, push to GitHub, or download the ZIP.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Demo -->
    <section id="demo" class="demo-section">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="ds-type-heading-lg section-title">Try it right here</h2>
                <p class="section-subtitle">Watch a realistic extraction simulation. Real results look even better.</p>
            </div>
            
            <div style="text-align:center; margin-bottom: -6px;">
                <img src="/img/generated/demo-illustration.jpg" alt="YouExtractor demo: turning a YouTube link into a complete project with AI" width="92" height="92" style="max-width: 92px; height: auto; opacity: .9; border-radius: 8px;">
            </div>

            <div class="demo-card">
                <ds-card variant="glass-accent" padding="lg">
                    <div class="demo-header">
                        <div>
                            <span class="demo-label">Interactive preview</span>
                            <div style="font-weight:600; margin-top:2px;">Next.js + Tailwind e-commerce tutorial</div>
                        </div>
                        <span class="ds-badge-brand" style="font-size:10px; padding:1px 8px;">LIVE DEMO</span>
                    </div>
                    
                    <!-- Demo Input -->
                    <div id="demoInputArea">
                        <div style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                            <div style="flex:1; min-width:240px;">
                                <ds-input 
                                    type="text" 
                                    id="demoUrl"
                                    value="https://www.youtube.com/watch?v=9bZkp7q19f0"
                                    label="YouTube URL (try changing me)"
                                    icon="link"
                                    size="lg">
                                </ds-input>
                            </div>
                            <div>
                                <ds-button 
                                    id="demoExtractBtn"
                                    label="Extract project"
                                    variant="primary"
                                    size="lg"
                                    icon="sparkle">
                                </ds-button>
                            </div>
                        </div>
                        <div style="font-size:10px; color:var(--ds-text-muted); margin-top:6px;">This is a live simulation. Real extractions happen after signup.</div>
                    </div>
                    
                    <!-- Progress / Loading -->
                    <div id="demoLoading" style="display:none; padding-top:12px;">
                        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
                            <div class="spinner-wrapper" style="width:28px;height:28px;">
                                <div class="spinner-bg" style="border-width:3px;"></div>
                                <div class="spinner-fg" style="border-width:3px; border-color: var(--ds-color-brand);"></div>
                            </div>
                            <div id="demoStatus" style="font-size:var(--theme-font-size-sm); color:var(--ds-text-secondary);">
                                Connecting to video...
                            </div>
                        </div>
                        <div class="demo-progress"><div id="demoProgressBar" class="demo-progress-bar" style="width:0%"></div></div>
                        <div class="demo-steps" id="demoSteps"></div>
                    </div>
                    
                    <!-- Result Preview -->
                    <div id="demoResult" class="demo-result">
                        <div class="mini-result">
                            <div class="mini-result-header">
                                <div class="mini-video-thumb">
                                    <i class="ph ph-play" style="font-size:1.6rem;"></i>
                                </div>
                                <div class="mini-meta" style="flex:1;">
                                    <h4 id="demoResultTitle">Build a Modern E-commerce Store with Next.js 15</h4>
                                    <p id="demoResultMeta">by Vercel • 42:18 • 287k views</p>
                                    
                                    <div class="tech-badges" id="demoResultBadges">
                                        <span class="tech-badge">Next.js 15</span>
                                        <span class="tech-badge">Tailwind</span>
                                        <span class="tech-badge">Stripe</span>
                                        <span class="tech-badge">Prisma</span>
                                        <span class="tech-badge">+3</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="display:flex; gap:8px; flex-wrap:wrap; margin: var(--theme-spacing-4) 0;">
                                <ds-button id="demoDownloadBtn" label="Download ZIP (48 files)" variant="primary" size="sm" icon="download-simple"></ds-button>
                                <ds-button id="demoGithubBtn" label="Push to GitHub" variant="secondary" size="sm" icon="github-logo"></ds-button>
                                <ds-button id="demoWorkspaceBtn" label="Open full workspace" variant="ghost" size="sm" icon="arrow-square-out"></ds-button>
                            </div>
                            
                            <div style="background:rgba(244,244,245,0.7); border-radius:var(--theme-radius-xl); padding:10px 14px; font-size:12px; font-family:var(--theme-font-mono); color:var(--ds-text-secondary);">
                                <div style="display:flex; justify-content:space-between; margin-bottom:3px;">
                                    <span>Generated 48 files • 7 folders</span>
                                    <span style="color:var(--ds-color-success);"><i class="ph ph-check" style="margin-right:2px;"></i> Ready to run</span>
                                </div>
                                <div>npm install → npm run dev → localhost:3000</div>
                            </div>
                        </div>
                        
                        <div style="text-align:center; margin-top:14px; font-size:var(--theme-font-size-xs);">
                            <a href="{{ route('signup') }}" id="demoSignupLink" style="color:var(--ds-text-brand); text-decoration:none; font-weight:600;">
                                Create a free account → Get the real version of this extraction <i class="ph ph-arrow-right" style="vertical-align:-1px;"></i>
                            </a>
                        </div>
                    </div>
                </ds-card>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="features">
        <div class="container">
            <div class="features-header reveal">
                <h2 class="ds-type-heading-lg features-title">Everything you need to go from watching → shipping</h2>
                <p class="ds-type-body-lg" style="color:var(--ds-text-secondary); max-width:540px; margin:4px auto 0;">Powerful features that save hours on every tutorial you watch.</p>
            </div>
            
            <div class="features-grid">
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal">
                    <div class="feature-icon-wrapper feature-icon-brand">
                        <i class="ph ph-lightning" style="color:var(--ds-text-brand); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Instant full project extraction</h3>
                    <p class="feature-desc">Complete folder trees, multiple files, config, tests — not just isolated snippets.</p>
                </ds-card>
                
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal stagger-1">
                    <div class="feature-icon-wrapper feature-icon-accent">
                        <i class="ph ph-book-open" style="color:var(--ds-text-accent); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Written guides + key concepts</h3>
                    <p class="feature-desc">Beautifully structured tutorials with explanations, prerequisites, and learning outcomes auto-generated.</p>
                </ds-card>
                
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal stagger-2">
                    <div class="feature-icon-wrapper feature-icon-electric">
                        <i class="ph ph-download-simple" style="color:var(--ds-text-electric); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">One-click ZIP + GitHub</h3>
                    <p class="feature-desc">Download everything or push a ready-to-clone repo directly to your GitHub account in one action.</p>
                </ds-card>
                
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal stagger-1">
                    <div class="feature-icon-wrapper feature-icon-success">
                        <i class="ph ph-list-checks" style="color:var(--ds-color-success); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Interactive learning roadmap</h3>
                    <p class="feature-desc">Checklist of steps from the video. Mark progress locally. Know exactly what to do next.</p>
                </ds-card>
                
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal stagger-2">
                    <div class="feature-icon-wrapper feature-icon-brand">
                        <i class="ph ph-chat-circle-dots" style="color:var(--ds-text-brand); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">AI tutor in every workspace</h3>
                    <p class="feature-desc">Ask questions about the code, concepts, or errors. The AI knows the exact tutorial you watched.</p>
                </ds-card>
                
                <ds-card variant="glass" interactive padding="lg" class="feature-card reveal">
                    <div class="feature-icon-wrapper feature-icon-electric">
                        <i class="ph ph-browser" style="color:var(--ds-text-electric); font-size:1.55rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Chrome extension</h3>
                    <p class="feature-desc">Extract directly from any YouTube page with a single click. No copy-paste required ever again.</p>
                </ds-card>
            </div>
        </div>
    </section>

    <!-- Real Screenshots / Proof -->
    <section class="proof">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="ds-type-heading-lg section-title">Real extractions. Real results.</h2>
                <p class="section-subtitle">Screenshots from actual users turning popular tutorials into production-ready codebases.</p>
            </div>
            
            <div class="screenshots">
                <div class="screenshot-item reveal">
                    <img src="/img/app-screenshot-2.png" alt="Workspace view with file explorer, code preview, and roadmap checklist for a Docker tutorial" loading="lazy">
                    <div class="screenshot-caption"><i class="ph ph-check-circle" style="color:var(--ds-color-success)"></i> Docker + Compose tutorial → full dev environment + compose files</div>
                </div>
                <div class="screenshot-item reveal stagger-1">
                    <img src="/img/app-screenshot-3.png" alt="Library view showing multiple past video extractions with search and filters" loading="lazy">
                    <div class="screenshot-caption"><i class="ph ph-check-circle" style="color:var(--ds-color-success)"></i> Library of 17 extracted tutorials with powerful search</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animated Stats -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat reveal">
                    <div class="stat-value count" data-target="128470">0</div>
                    <div class="stat-label">Tutorials extracted</div>
                </div>
                <div class="stat reveal stagger-1">
                    <div class="stat-value count" data-target="942">0</div>
                    <div class="stat-label">Hours saved this week</div>
                </div>
                <div class="stat reveal stagger-2">
                    <div class="stat-value count" data-target="18400">0</div>
                    <div class="stat-label">Projects shipped</div>
                </div>
                <div class="stat reveal stagger-3">
                    <div class="stat-value count" data-target="97">0</div>
                    <div class="stat-label">Countries represented</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header reveal">
                <h2 class="ds-type-heading-lg section-title">Loved by developers who hate wasting time</h2>
            </div>
            
            <div class="testimonial-grid">
                <div class="testimonial reveal">
                    <p class="testimonial-quote">“I used to spend 40 minutes copying code from a 25 min video. Now I have the full app + guide in under a minute and can actually focus on understanding.”</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">SM</div>
                        <div>
                            <div class="author-name">Sofia M.</div>
                            <div class="author-role">Bootcamp student @ CodeSmith</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial reveal stagger-1">
                    <p class="testimonial-quote">“The roadmap checklist alone is worth it. I finally finish tutorials instead of getting stuck halfway and forgetting what I learned.”</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">TK</div>
                        <div>
                            <div class="author-name">Tyler K.</div>
                            <div class="author-role">Frontend engineer @ Linear</div>
                        </div>
                    </div>
                </div>
                
                <div class="testimonial reveal stagger-2">
                    <p class="testimonial-quote">“I extract every new framework video I watch. The GitHub integration + AI chat means I have a perfect reference repo + someone to ask questions later.”</p>
                    <div class="testimonial-author">
                        <div class="author-avatar">AL</div>
                        <div>
                            <div class="author-name">Aisha L.</div>
                            <div class="author-role">Indie hacker &amp; educator</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq">
        <div class="container">
            <div class="section-header" style="margin-bottom: var(--theme-spacing-8);">
                <h2 class="ds-type-heading-lg section-title">Frequently asked questions</h2>
            </div>
            
            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Does it work with private or members-only videos?
                        <i class="ph ph-caret-down"></i>
                    </button>
                    <div class="faq-answer">Currently we support any public YouTube video with available captions/transcripts. Private videos and YouTube Premium content are not supported.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        How accurate is the extracted code?
                        <i class="ph ph-caret-down"></i>
                    </button>
                    <div class="faq-answer">Extremely high on well-explained tutorials. The AI reconstructs files from both on-screen code and spoken instructions. You always get the full context and can edit anything.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Is my data private? Do you store the videos?
                        <i class="ph ph-caret-down"></i>
                    </button>
                    <div class="faq-answer">We only store the final extracted artifacts (files, guide, metadata) in your private library. We do not re-host or redistribute original video content. See our <a href="{{ route('privacy') }}" style="color:var(--ds-text-brand)">Privacy Policy</a>.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Can I push directly to a new GitHub repository?
                        <i class="ph ph-caret-down"></i>
                    </button>
                    <div class="faq-answer">Yes. Connect your GitHub account once and every extraction can be pushed as a brand new private or public repo with proper .gitignore and README in a single click.</div>
                </div>
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Do you have a free plan? What's included?
                        <i class="ph ph-caret-down"></i>
                    </button>
                    <div class="faq-answer">Yes — the core extraction experience is free forever. You get unlimited extractions on the free tier with reasonable rate limits. Paid plans add faster queues, private repo defaults, and more AI tutor messages.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA -->
    <section class="final-cta">
        <div class="container">
            <div class="final-cta-card reveal">
                <h2 class="ds-type-heading-lg" style="margin-bottom:8px;">Ready to never copy-paste from a video again?</h2>
                <p style="color:var(--ds-text-secondary); margin-bottom:var(--theme-spacing-6);">Join thousands of developers learning and shipping faster.</p>
                
                <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                    <a href="{{ route('signup') }}" style="text-decoration:none;">
                        <ds-button label="Create free account" variant="primary" size="lg" icon="arrow-right" icon-position="right"></ds-button>
                    </a>
                    <a href="#demo" style="text-decoration:none;">
                        <ds-button label="Watch the demo again" variant="secondary" size="lg"></ds-button>
                    </a>
                </div>
                
                <p style="margin-top:14px; font-size:var(--theme-font-size-xs); color:var(--ds-text-muted);">No credit card. Cancel anytime. Works on desktop &amp; mobile.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Design System + Page Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
    <script>
        // Mobile menu toggle
        const mobileBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        if (mobileBtn && mobileNav) {
            mobileBtn.addEventListener('click', () => {
                const isOpen = mobileNav.classList.toggle('open');
                mobileBtn.innerHTML = isOpen 
                    ? '<i class="ph ph-x"></i>' 
                    : '<i class="ph ph-list"></i>';
            });
            // close on nav click
            mobileNav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
                mobileNav.classList.remove('open');
                mobileBtn.innerHTML = '<i class="ph ph-list"></i>';
            }));
        }

        // Scroll reveal using IntersectionObserver
        function initReveals() {
            const reveals = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window)) {
                reveals.forEach(el => el.classList.add('in'));
                return;
            }
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            reveals.forEach(el => io.observe(el));
        }

        // Animated counters
        function animateCounters() {
            const counters = document.querySelectorAll('.count');
            const animate = (el) => {
                const target = parseInt(el.dataset.target, 10);
                const duration = 1400;
                const start = performance.now();
                const startVal = 0;
                
                function tick(now) {
                    const p = Math.min((now - start) / duration, 1);
                    // easeOutExpo
                    const eased = p === 1 ? 1 : 1 - Math.pow(2, -10 * p);
                    const val = Math.floor(startVal + (target - startVal) * eased);
                    el.textContent = val.toLocaleString();
                    if (p < 1) requestAnimationFrame(tick);
                    else el.textContent = target.toLocaleString();
                }
                requestAnimationFrame(tick);
            };
            
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animate(entry.target);
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.6 });
            
            counters.forEach(c => io.observe(c));
        }

        // Interactive Demo
        function initDemo() {
            const btn = document.getElementById('demoExtractBtn');
            const loading = document.getElementById('demoLoading');
            const result = document.getElementById('demoResult');
            const inputArea = document.getElementById('demoInputArea');
            const statusEl = document.getElementById('demoStatus');
            const bar = document.getElementById('demoProgressBar');
            const stepsContainer = document.getElementById('demoSteps');
            const demoUrlInput = document.getElementById('demoUrl');
            
            if (!btn) return;
            
            let capturedUrl = '';
            
            const steps = [
                { label: 'Fetching transcript', ms: 620 },
                { label: 'Detecting tech stack', ms: 480 },
                { label: 'Reconstructing files', ms: 980 },
                { label: 'Writing guide + roadmap', ms: 640 },
                { label: 'Finalizing workspace', ms: 420 }
            ];
            
            btn.addEventListener('click', () => {
                capturedUrl = (demoUrlInput && demoUrlInput.value) ? demoUrlInput.value.trim() : 'https://www.youtube.com/watch?v=9bZkp7q19f0';
                
                // Hide input, show loading
                inputArea.style.display = 'none';
                loading.style.display = 'block';
                result.classList.remove('show');
                bar.style.width = '0%';
                stepsContainer.innerHTML = '';
                
                let progress = 0;
                let stepIndex = 0;
                
                function runNextStep() {
                    if (stepIndex >= steps.length) {
                        // finish
                        setTimeout(() => {
                            loading.style.display = 'none';
                            result.classList.add('show');
                            
                            // Wire the result CTAs to real prefilled flows
                            wireDemoResultActions(result, capturedUrl);
                            
                            // subtle confetti dots on success (CSS only)
                            createMiniConfetti(result);
                        }, 180);
                        return;
                    }
                    
                    const step = steps[stepIndex];
                    statusEl.textContent = step.label + '...';
                    
                    // pill
                    const pill = document.createElement('div');
                    pill.className = 'demo-step-pill';
                    pill.innerHTML = `<i class="ph ph-check"></i> ${step.label}`;
                    stepsContainer.appendChild(pill);
                    
                    // progress tick
                    const targetProgress = Math.round(((stepIndex + 1) / steps.length) * 100);
                    const start = Date.now();
                    const from = progress;
                    
                    function progressTick() {
                        const t = Math.min((Date.now() - start) / step.ms, 1);
                        const current = Math.floor(from + (targetProgress - from) * t);
                        bar.style.width = current + '%';
                        progress = current;
                        if (t < 1) requestAnimationFrame(progressTick);
                        else {
                            pill.classList.add('done');
                            stepIndex++;
                            setTimeout(runNextStep, 90);
                        }
                    }
                    requestAnimationFrame(progressTick);
                }
                
                // kickoff
                setTimeout(runNextStep, 180);
            });
            
            // Allow clicking result CTA to scroll or hint signup
            result.addEventListener('click', (e) => {
                if (e.target.closest('a') || e.target.closest('ds-button')) return;
            });
        }
        
        function wireDemoResultActions(resultEl, youtubeUrl) {
            const encoded = encodeURIComponent(youtubeUrl || '');
            const signupUrl = `{{ route('signup') }}?youtube_url=${encoded}`;
            const signinUrl = `{{ route('signin') }}?youtube_url=${encoded}`;
            
            // Update the main signup link
            const mainLink = document.getElementById('demoSignupLink');
            if (mainLink) mainLink.href = signupUrl;
            
            // Download button → tease + go to signup
            const dlBtn = document.getElementById('demoDownloadBtn');
            if (dlBtn) {
                dlBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    showDemoCtaToast(resultEl, 'Real ZIP downloads unlock after signup', signupUrl);
                }, { once: true });
            }
            
            // GitHub button
            const ghBtn = document.getElementById('demoGithubBtn');
            if (ghBtn) {
                ghBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    showDemoCtaToast(resultEl, 'One-click GitHub push is available after you sign up', signupUrl);
                }, { once: true });
            }
            
            // Open full workspace → the good one, goes straight to signup with prefill
            const wsBtn = document.getElementById('demoWorkspaceBtn');
            if (wsBtn) {
                wsBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    window.location.href = signupUrl;
                }, { once: true });
            }
        }
        
        function showDemoCtaToast(container, message, targetUrl) {
            let toast = container.querySelector('.demo-cta-toast');
            if (toast) toast.remove();
            
            toast = document.createElement('div');
            toast.className = 'demo-cta-toast';
            toast.style.cssText = 'margin-top:12px;padding:10px 14px;background:rgba(20,184,166,0.12);border:1px solid rgba(20,184,166,0.3);border-radius:var(--theme-radius-lg);font-size:12px;display:flex;align-items:center;gap:10px;';
            toast.innerHTML = `
                <span style="flex:1;color:var(--ds-text-secondary);">${message}</span>
                <a href="${targetUrl}" style="color:var(--ds-text-brand);font-weight:600;white-space:nowrap;text-decoration:none;">Sign up free →</a>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
            }, 6500);
        }
        
        function createMiniConfetti(container) {
            const colors = ['#14b8a6', '#f59e0b', '#38bdf8'];
            for (let i = 0; i < 9; i++) {
                const dot = document.createElement('div');
                dot.style.cssText = `position:absolute;width:5px;height:5px;border-radius:50%;background:${colors[i%3]};opacity:.7;pointer-events:none;`;
                dot.style.left = (20 + Math.random() * 60) + '%';
                dot.style.top = (20 + Math.random() * 35) + '%';
                container.appendChild(dot);
                
                setTimeout(() => {
                    dot.animate([
                        { transform: 'translateY(0)', opacity: .7 },
                        { transform: `translateY(${18 + Math.random()*24}px)`, opacity: 0 }
                    ], { duration: 420 + Math.random()*280, easing: 'ease-out' }).onfinish = () => dot.remove();
                }, 10);
            }
        }

        // FAQ accordion
        window.toggleFaq = function(btn) {
            const item = btn.parentElement;
            item.classList.toggle('open');
        };

        // Smooth scroll for in-page anchors (enhance header links)
        function initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    const id = this.getAttribute('href').slice(1);
                    const target = document.getElementById(id);
                    if (target) {
                        e.preventDefault();
                        const y = target.getBoundingClientRect().top + window.scrollY - 80;
                        window.scrollTo({ top: y, behavior: 'smooth' });
                    }
                });
            });
        }

        // Keyboard support: press / to focus demo (fun marketing touch)
        function initKeyboard() {
            document.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement.tagName === 'BODY') {
                    const demo = document.getElementById('demo');
                    if (demo) {
                        e.preventDefault();
                        demo.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(() => {
                            const btn = document.getElementById('demoExtractBtn');
                            if (btn) btn.focus();
                        }, 520);
                    }
                }
            });
            
            // Easter egg: type "demo" anywhere focuses demo
            let buffer = '';
            document.addEventListener('keypress', (e) => {
                buffer += e.key.toLowerCase();
                if (buffer.length > 6) buffer = buffer.slice(-6);
                if (buffer.endsWith('demo')) {
                    buffer = '';
                    const d = document.getElementById('demo');
                    if (d) d.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }

        // Header subtle style change on scroll
        function initHeaderScroll() {
            const header = document.getElementById('header');
            if (!header) return;
            let last = 0;
            window.addEventListener('scroll', () => {
                const y = window.scrollY;
                if (y > 40 && last <= 40) {
                    header.style.background = 'rgba(255, 255, 255, 0.9)';
                } else if (y <= 40 && last > 40) {
                    header.style.background = 'rgba(255, 255, 255, 0.7)';
                }
                last = y;
            }, { passive: true });
        }

        // Boot everything
        function bootLanding() {
            initReveals();
            animateCounters();
            initDemo();
            initSmoothScroll();
            initKeyboard();
            initHeaderScroll();
            
            // Auto-trigger a tiny hint on the demo button after a few seconds (only if not interacted)
            setTimeout(() => {
                const btn = document.getElementById('demoExtractBtn');
                if (btn && !btn.dataset.interacted) {
                    btn.style.transition = 'box-shadow 420ms';
                    btn.style.boxShadow = '0 0 0 3px rgba(20,184,166,0.2)';
                    setTimeout(() => { if (btn) btn.style.boxShadow = ''; }, 1400);
                }
            }, 5200);
            
            // Mark demo button interaction
            const demoBtn = document.getElementById('demoExtractBtn');
            if (demoBtn) demoBtn.addEventListener('click', () => { demoBtn.dataset.interacted = '1'; }, { once: true });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bootLanding);
        } else {
            bootLanding();
        }
    </script>
</body>
</html>
