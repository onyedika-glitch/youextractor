<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YouExtractor - Learn Programming Faster</title>
    
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
            background: rgba(10, 16, 34, 0.5);
            backdrop-filter: blur(12px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
            transition: opacity var(--theme-motion-fast) var(--theme-ease-default);
        }

        .logo:hover {
            opacity: 0.9;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-8);
        }

        .nav-link {
            color: var(--ds-text-secondary);
            text-decoration: none;
            transition: color var(--theme-motion-fast) var(--theme-ease-default);
        }

        .nav-link:hover {
            color: var(--ds-text-primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-4);
        }

        /* Hero Section */
        .hero {
            padding-top: 160px;
            padding-bottom: var(--theme-spacing-32);
            position: relative;
            text-align: center;
        }

        .hero-badge-container {
            margin-bottom: var(--theme-spacing-6);
        }

        .hero-title {
            color: var(--ds-text-primary);
            margin-top: var(--theme-spacing-4);
            margin-bottom: var(--theme-spacing-6);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-highlight {
            background: var(--ds-gradient-brand);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            color: var(--ds-text-secondary);
            margin-bottom: var(--theme-spacing-10);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            justify-content: center;
            gap: var(--theme-spacing-4);
            flex-wrap: wrap;
        }

        /* Features Section */
        .features {
            padding: var(--theme-spacing-24) 0;
            background: rgba(15, 23, 42, 0.3);
            border-top: 1px solid var(--ds-border-subtle);
            border-bottom: 1px solid var(--ds-border-subtle);
        }

        .features-header {
            text-align: center;
            margin-bottom: var(--theme-spacing-16);
        }

        .features-title {
            color: var(--ds-text-primary);
            margin-bottom: var(--theme-spacing-4);
        }

        .features-subtitle {
            color: var(--ds-text-secondary);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--theme-spacing-8);
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: var(--theme-radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--theme-spacing-6);
        }

        .feature-icon-brand {
            background: var(--ds-color-brand-muted);
            border: 1px solid var(--ds-border-accent);
        }

        .feature-icon-accent {
            background: rgba(236, 72, 153, 0.1);
            border: 1px solid rgba(236, 72, 153, 0.2);
        }

        .feature-icon-electric {
            background: rgba(6, 182, 212, 0.1);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        .feature-title {
            color: var(--ds-text-primary);
            margin-top: 0;
            margin-bottom: var(--theme-spacing-3);
        }

        .feature-desc {
            color: var(--ds-text-secondary);
            margin: 0;
        }

        /* Footer */
        footer {
            background: rgba(6, 11, 24, 0.8);
            border-top: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-12) 0;
            margin-top: auto;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            gap: var(--theme-spacing-4);
            text-align: center;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                text-align: left;
            }
        }

        .footer-text {
            color: var(--ds-text-muted);
            margin: 0;
            font-size: var(--theme-font-size-sm);
        }

        .footer-heart {
            color: var(--ds-text-accent);
        }

        /* Ambient Glow Blobs */
        .ambient-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: var(--theme-radius-full);
            filter: blur(120px);
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        .glow-purple {
            top: -100px;
            left: -200px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.3) 0%, rgba(10, 16, 34, 0) 70%);
        }

        .glow-pink {
            top: 100px;
            right: -200px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.3) 0%, rgba(10, 16, 34, 0) 70%);
        }

        /* Navigation adjustments for responsiveness */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container header-content">
            <a href="{{ route('landing') }}" class="logo">
                <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                <span class="ds-type-heading-sm" style="margin: 0;">YouExtractor</span>
            </a>
            <div class="nav-links">
                <a href="#features" class="nav-link ds-type-body-sm">Features</a>
                <a href="https://buymeacoffee.com/omogo" target="_blank" class="nav-link ds-type-body-sm">Donate</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('signin') }}" style="text-decoration: none;">
                    <ds-button label="Sign In" variant="ghost" size="sm"></ds-button>
                </a>
                <a href="{{ route('signup') }}" style="text-decoration: none;">
                    <ds-button label="Get Started" variant="primary" size="sm"></ds-button>
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="ambient-glow glow-purple"></div>
        <div class="ambient-glow glow-pink"></div>
        
        <div class="container" style="position: relative; z-index: 1;">
            <div class="hero-badge-container">
                <span class="ds-badge-brand">✨ AI-Powered Visual Learning</span>
            </div>
            <h1 class="ds-type-display-md hero-title">
                Turn YouTube Videos into <span class="hero-highlight">Real Code</span>
            </h1>
            <p class="ds-type-body-lg hero-subtitle">
                Stop pausing and typing. Instantly extract working code projects, tutorials, and setup guides from any programming video with one click.
            </p>
            <div class="hero-actions">
                <a href="{{ route('signup') }}" style="text-decoration: none;">
                    <ds-button label="Try It Free" variant="gradient" size="lg" icon="arrow-right" icon-position="right"></ds-button>
                </a>
                <a href="#features" style="text-decoration: none;">
                    <ds-button label="Explore Features" variant="secondary" size="lg" icon="compass"></ds-button>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <div class="features-header">
                <h2 class="ds-type-heading-lg features-title">Why YouExtractor?</h2>
                <p class="ds-type-body-lg features-subtitle">Everything you need to learn from video tutorials efficiently.</p>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <ds-card variant="glass" interactive padding="lg">
                    <div class="feature-icon-wrapper feature-icon-brand">
                        <i class="ph ph-lightning" style="color: var(--ds-text-brand); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Instant Code Extraction</h3>
                    <p class="ds-type-body-md feature-desc">
                        Don't manually copy code from paused videos. We generate working file structures instantly.
                    </p>
                </ds-card>

                <!-- Feature 2 -->
                <ds-card variant="glass" interactive padding="lg">
                    <div class="feature-icon-wrapper feature-icon-accent">
                        <i class="ph ph-book-open" style="color: var(--ds-text-accent); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Detailed Guides</h3>
                    <p class="ds-type-body-md feature-desc">
                        Get comprehensive written tutorials, setup instructions, and key concept explanations automatically.
                    </p>
                </ds-card>

                <!-- Feature 3 -->
                <ds-card variant="glass" interactive padding="lg">
                    <div class="feature-icon-wrapper feature-icon-electric">
                        <i class="ph ph-download-simple" style="color: var(--ds-text-electric); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-title">Download & Run</h3>
                    <p class="ds-type-body-md feature-desc">
                        Download the entire project as a ZIP file, complete with dependencies and environment configuration.
                    </p>
                </ds-card>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-content">
            <p class="footer-text">&copy; {{ date('Y') }} YouExtractor. All rights reserved.</p>
            <div style="display: flex; gap: var(--theme-spacing-4); align-items: center; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('privacy') }}" class="footer-text" style="text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-primary)'" onmouseout="this.style.color='var(--ds-text-muted)'">Privacy Policy</a>
                <span class="footer-text" style="opacity: 0.5;">•</span>
                <a href="{{ route('terms') }}" class="footer-text" style="text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-primary)'" onmouseout="this.style.color='var(--ds-text-muted)'">Terms of Service</a>
            </div>
            <p class="footer-text">
                Built for developers who learn by watching <i class="ph ph-heart footer-heart"></i>
            </p>
        </div>
    </footer>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
