<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>Support & Help Center • YouExtractor - AI YouTube Code Extractor</title>
    <meta name="description" content="Get support for YouExtractor. Reach out via email, check open GitHub issues, or join our community for help converting YouTube tutorials into code projects.">
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Fonts & Icons -->
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
            max-width: 900px;
            padding: var(--theme-spacing-8) var(--theme-spacing-6);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-8);
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

        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--theme-spacing-6);
        }

        .channel-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
            gap: var(--theme-spacing-4);
        }

        .channel-icon {
            font-size: 2.5rem;
            color: var(--ds-text-brand);
            margin-bottom: var(--theme-spacing-2);
        }

        .channel-title {
            margin: 0;
            color: var(--ds-text-primary);
        }

        .channel-desc {
            margin: 0;
            color: var(--ds-text-secondary);
            font-size: var(--theme-font-size-sm);
            line-height: 1.6;
            flex-grow: 1;
        }

        .faq-section {
            margin-top: var(--theme-spacing-4);
        }

        .faq-title {
            color: var(--ds-text-primary);
            margin-bottom: var(--theme-spacing-6);
            border-left: 3px solid var(--ds-border-accent);
            padding-left: var(--theme-spacing-3);
        }

        .faq-item {
            margin-bottom: var(--theme-spacing-6);
        }

        .faq-question {
            color: var(--ds-text-primary);
            margin-top: 0;
            margin-bottom: var(--theme-spacing-2);
        }

        .faq-answer {
            color: var(--ds-text-secondary);
            margin: 0;
            line-height: 1.6;
            font-size: var(--theme-font-size-sm);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Top Navigation Header -->
        <div class="header-section">
            <a href="{{ route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(168,85,247,0.25);">
                <span class="ds-type-heading-sm" style="margin: 0; font-size: 1.25rem;">YouExtractor</span>
            </a>
            <a href="{{ url()->previous() ?: route('landing') }}" style="text-decoration: none;">
                <ds-button label="Back" variant="glow" size="sm" icon="arrow-left"></ds-button>
            </a>
        </div>

        <!-- Hero Intro -->
        <div style="text-align: center; max-width: 600px; margin: 0 auto;">
            <h1 class="ds-type-heading-md" style="margin-top: 0; margin-bottom: var(--theme-spacing-2);">Support & Help Center</h1>
            <p style="color: var(--ds-text-secondary); margin: 0;">Have questions, feedback, or run into an issue? Choose a channel below to get help with YouExtractor.</p>
        </div>

        <!-- Support Channels Grid -->
        <div class="support-grid">
            <!-- Email Card -->
            <ds-card variant="glass" padding="lg">
                <div class="channel-card">
                    <div>
                        <i class="ph ph-envelope-simple channel-icon"></i>
                        <h3 class="ds-type-heading-sm channel-title">Direct Email</h3>
                        <p class="channel-desc">For account queries, billing concerns, API issues, or generic support inquiries. We typically reply within 24 hours.</p>
                    </div>
                    <a href="mailto:omogopeter48@gmail.com" style="text-decoration: none; display: block; margin-top: auto;">
                        <ds-button label="Email Support" variant="primary" size="md" icon="envelope-simple" full-width></ds-button>
                    </a>
                </div>
            </ds-card>

            <!-- GitHub Card -->
            <ds-card variant="glass" padding="lg">
                <div class="channel-card">
                    <div>
                        <i class="ph ph-github-logo channel-icon"></i>
                        <h3 class="ds-type-heading-sm channel-title">GitHub Issues</h3>
                        <p class="channel-desc">For technical bugs, Chrome extension issues, feature requests, or open-source discussions. Post here to track developer updates.</p>
                    </div>
                    <a href="https://github.com/onyedika-glitch/youextractor/issues" target="_blank" style="text-decoration: none; display: block; margin-top: auto;">
                        <ds-button label="Open GitHub Issue" variant="glow" size="md" icon="github-logo" full-width></ds-button>
                    </a>
                </div>
            </ds-card>

            <!-- Buy Me A Coffee Card -->
            <ds-card variant="glass" padding="lg">
                <div class="channel-card">
                    <div>
                        <i class="ph ph-coffee channel-icon"></i>
                        <h3 class="ds-type-heading-sm channel-title">Developer Tip</h3>
                        <p class="channel-desc">Want to support YouExtractor's development? Buy the developer a coffee to help maintain the hosted server infrastructure and model APIs.</p>
                    </div>
                    <a href="https://buymeacoffee.com/omogo" target="_blank" style="text-decoration: none; display: block; margin-top: auto;">
                        <ds-button label="Buy Me A Coffee" variant="ghost" size="md" icon="coffee" full-width></ds-button>
                    </a>
                </div>
            </ds-card>
        </div>

        <!-- Simple FAQ section to address immediate needs -->
        <div class="faq-section">
            <h2 class="ds-type-heading-sm faq-title">Frequently Asked Questions</h2>
            
            <div class="faq-item">
                <h3 class="ds-type-heading-xs faq-question">Is my GitHub token safe?</h3>
                <p class="faq-answer">Absolutely. We do not store your GitHub Personal Access Token on our database. It is stored transiently on your local browser/session, and is only sent to GitHub's official API to push your generated repo.</p>
            </div>

            <div class="faq-item">
                <h3 class="ds-type-heading-xs faq-question">The Chrome Extension says "Go to a YouTube Video". What do I do?</h3>
                <p class="faq-answer">The YouExtractor Chrome Extension is designed to activate only when you are on a YouTube video playback page (URLs starting with `youtube.com/watch` or `youtu.be/`). Open any coding tutorial first, and the "Extract Code & Guide" button will light up.</p>
            </div>

            <div class="faq-item">
                <h3 class="ds-type-heading-xs faq-question">Can I extract code from long videos?</h3>
                <p class="faq-answer">Yes, YouExtractor can parse long coding tutorials. If you run into any API rate limits or timeout errors on extremely long videos, please reach out to us at our email address above so we can inspect and optimize it.</p>
            </div>
        </div>
    </div>
    
    @include('partials.footer')

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
