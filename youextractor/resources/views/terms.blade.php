<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>Terms of Service • YouExtractor - AI YouTube Code Extractor</title>
    <meta name="description" content="Terms of Service for YouExtractor. Rules for using our AI tool that converts YouTube coding tutorials into complete projects and guides.">
    
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

        .doc-content h1, .doc-content h2, .doc-content h3 {
            color: var(--ds-text-primary);
            margin-top: var(--theme-spacing-6);
        }

        .doc-content p, .doc-content li {
            color: var(--ds-text-secondary);
            line-height: 1.6;
        }

        .doc-content ul {
            padding-left: var(--theme-spacing-5);
        }

        .doc-content li {
            margin-bottom: var(--theme-spacing-2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <a href="{{ route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);">
                <span class="ds-type-heading-sm" style="margin: 0; font-size: 1.25rem;">YouExtractor</span>
            </a>
            <a href="{{ url()->previous() ?: route('landing') }}" style="text-decoration: none;">
                <ds-button label="Back" variant="glow" size="sm" ></ds-button>
            </a>
        </div>

        <ds-card variant="glass" padding="xl" style="margin-top: 6vh;">
            <div class="doc-content">
                <h1 class="ds-type-heading-md" style="margin-top: 0;">Terms of Service</h1>
                <p><strong>Last updated: June 4, 2026</strong></p>
                <p>Please read these Terms of Service ("Terms") carefully before using the YouExtractor service.</p>

                <h2 class="ds-type-heading-sm">1. Acceptance of Terms</h2>
                <p>By accessing or using YouExtractor, you agree to be bound by these Terms. If you disagree with any part of the terms, you may not access the service.</p>

                <h2 class="ds-type-heading-sm">2. Description of Service</h2>
                <p>YouExtractor is a web tool that extracts source code snippets from YouTube tutorials using AI and transcript analysis. It allows users to view, copy, download, or push these code files to GitHub.</p>

                <h2 class="ds-type-heading-sm">3. User Responsibilities</h2>
                <ul>
                    <li>You are responsible for maintaining the confidentiality of your account logins.</li>
                    <li>You must comply with all third-party terms of service, including YouTube's Terms of Service and GitHub's Terms of Service when using our integrations.</li>
                </ul>

                <h2 class="ds-type-heading-sm">4. Intellectual Property</h2>
                <p>YouExtractor does not claim ownership of the code extracted from YouTube videos. The copyright of the code remains with the original content creators of the videos.</p>

                <h2 class="ds-type-heading-sm">5. Disclaimer of Warranties</h2>
                <p>YouExtractor is provided on an "AS IS" and "AS AVAILABLE" basis. We do not guarantee the correctness or reliability of the code generated by the AI model.</p>

                <h2 class="ds-type-heading-sm">6. Limitation of Liability</h2>
                <p>In no event shall YouExtractor be liable for any indirect, incidental, special, consequential, or punitive damages arising out of your use of the service.</p>

                <h2 class="ds-type-heading-sm">7. Changes to Terms</h2>
                <p>We reserve the right to modify or replace these Terms at any time.</p>

                <h2 class="ds-type-heading-sm">8. Contact Us</h2>
                <p>For any inquiries regarding these Terms, please open an issue: <a href="https://github.com/onyedika-glitch/youextractor/issues" style="color: var(--ds-text-brand); text-decoration: none;" target="_blank">onyedika-glitch/youextractor</a></p>
            </div>
        </ds-card>
    </div>
    
    @include('partials.footer')

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
</body>
</html>
