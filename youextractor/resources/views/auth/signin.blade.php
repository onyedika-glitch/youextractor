<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>Sign In - YouTube Code Extractor</title>
    
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
            box-sizing: border-box;
        }

        .auth-split-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Left Side: Brand Panel */
        .auth-sidebar-panel {
            display: none;
        }

        @media (min-width: 1024px) {
            .auth-sidebar-panel {
                display: flex;
                width: 50%;
                background: linear-gradient(135deg, #f0fdfa 0%, #eff6ff 100%);
                border-right: 1px solid var(--ds-border-subtle);
                padding: var(--theme-spacing-12);
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
        }

        .auth-sidebar-content {
            position: relative;
            z-index: 10;
            max-width: 480px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-8);
        }

        .logo-link {
            display: inline-flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
            font-weight: var(--theme-font-weight-semibold);
            font-size: 1.5rem;
        }

        .logo-link img {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid rgba(20, 184, 166, 0.25);
        }

        .auth-sidebar-hero h2 {
            font-size: 2.25rem;
            font-weight: 800;
            line-height: 1.25;
            margin: 0 0 var(--theme-spacing-3);
            color: var(--ds-text-primary);
            background: linear-gradient(180deg, var(--ds-text-primary), var(--ds-text-secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .auth-sidebar-hero p {
            font-size: var(--theme-font-size-md);
            color: var(--ds-text-secondary);
            line-height: 1.6;
            margin: 0;
        }

        .auth-feature-list {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-6);
        }

        .auth-feature-item {
            display: flex;
            align-items: flex-start;
            gap: var(--theme-spacing-4);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--theme-radius-lg);
            background: rgba(20, 184, 166, 0.1);
            border: 1px solid rgba(20, 184, 166, 0.2);
            color: var(--ds-text-brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .auth-feature-item h4 {
            font-size: var(--theme-font-size-md);
            font-weight: 600;
            color: var(--ds-text-primary);
            margin: 0 0 var(--theme-spacing-1);
        }

        .auth-feature-item p {
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-muted);
            margin: 0;
            line-height: 1.5;
        }

        /* Mock Preview Card */
        .mock-preview-container {
            background: rgba(255, 255, 255, 0.65);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-xl);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            width: 100%;
        }

        .mock-preview-header {
            height: 36px;
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid var(--ds-border-subtle);
            display: flex;
            align-items: center;
            padding: 0 var(--theme-spacing-4);
            gap: 6px;
        }

        .mock-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }
        .mock-dot.red { background: #ef4444; }
        .mock-dot.yellow { background: #f59e0b; }
        .mock-dot.green { background: #22c55e; }

        .mock-title {
            font-family: var(--theme-font-mono);
            font-size: 11px;
            color: var(--ds-text-muted);
            margin-left: 6px;
        }

        .mock-preview-body {
            padding: var(--theme-spacing-5);
            font-family: var(--theme-font-mono);
            font-size: var(--theme-font-size-xs);
            line-height: 1.7;
            color: var(--ds-text-secondary);
        }

        .mock-line {
            display: flex;
            gap: var(--theme-spacing-2);
        }

        .c-tag { color: var(--ds-text-brand); }
        .c-success { color: var(--ds-text-accent); }
        .c-info { color: var(--ds-text-electric); }
        .c-path { color: #f59e0b; }

        .ambient-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: var(--theme-radius-full);
            filter: blur(100px);
            opacity: 0.12;
            pointer-events: none;
            z-index: 0;
        }
        .glow-1 { top: -100px; left: -100px; background: radial-gradient(circle, rgba(20,184,166,0.15) 0%, transparent 70%); }
        .glow-2 { bottom: -100px; right: -100px; background: radial-gradient(circle, rgba(59,130,246,0.15) 0%, transparent 70%); }

        /* Right Side: Form Panel */
        .auth-form-panel {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--theme-spacing-8) var(--theme-spacing-6);
            overflow-y: auto;
        }

        @media (min-width: 1024px) {
            .auth-form-panel {
                width: 50%;
                padding: var(--theme-spacing-12);
            }
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-6);
        }

        .logo-section {
            text-align: center;
        }

        @media (min-width: 1024px) {
            .auth-form-panel .logo-section {
                display: none;
            }
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
        }

        .logo:hover {
            filter: drop-shadow(0 0 8px var(--ds-color-brand-subtle));
        }

        .auth-header {
            text-align: center;
            margin-bottom: var(--theme-spacing-4);
        }

        .auth-title {
            margin: 0 0 var(--theme-spacing-2);
            color: var(--ds-text-primary);
        }

        .auth-subtitle {
            color: var(--ds-text-secondary);
            margin: 0;
        }

        /* Banner alerts */
        .banner {
            margin-bottom: var(--theme-spacing-6);
        }

        /* Social login buttons */
        .google-btn, .github-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--theme-spacing-3);
            width: 100%;
            height: 46px;
            border-radius: var(--theme-radius-xl);
            font-weight: var(--theme-font-weight-semibold);
            text-decoration: none;
            cursor: pointer;
            box-sizing: border-box;
            font-family: var(--theme-font-sans);
            font-size: var(--theme-font-size-sm);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
        }

        .google-btn {
            background: #ffffff;
            color: #1a1f2c;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        .google-btn:hover {
            background: #f8fafc;
            border-color: rgba(0, 0, 0, 0.15);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05), 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .github-btn {
            background: #24292e;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .github-btn:hover {
            background: #1b1f23;
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        }

        /* Divider */
        .divider-container {
            position: relative;
            margin-bottom: var(--theme-spacing-6);
            text-align: center;
        }

        .divider-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px solid var(--ds-border-subtle);
            z-index: 1;
        }

        .divider-text {
            position: relative;
            display: inline-block;
            padding: 0 var(--theme-spacing-4);
            background: var(--ds-surface-card);
            color: var(--ds-text-muted);
            font-size: var(--theme-font-size-xs);
            z-index: 2;
        }

        /* Form layout */
        form {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-5);
        }

        .form-row {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-1.5);
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .label-text {
            font-family: var(--theme-font-sans);
            font-size: var(--theme-font-size-sm);
            font-weight: var(--theme-font-weight-medium);
            color: var(--ds-text-primary);
        }

        .forgot-link {
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-brand);
            text-decoration: none;
            transition: color var(--theme-motion-fast) var(--theme-ease-default);
        }

        .forgot-link:hover {
            color: var(--ds-color-brand-subtle);
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-2);
        }

        .checkbox-input {
            width: 16px;
            height: 16px;
            background: var(--theme-neutral-900);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-sm);
            accent-color: var(--ds-color-brand);
        }

        .checkbox-label {
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-secondary);
            user-select: none;
        }

        .footer-link {
            text-align: center;
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-secondary);
            margin-top: var(--theme-spacing-6);
        }

        .footer-link a {
            color: var(--ds-text-brand);
            text-decoration: none;
            font-weight: var(--theme-font-weight-semibold);
        }

        .footer-link a:hover {
            color: var(--ds-color-brand-subtle);
        }
    </style>
</head>
<body>

    <div class="auth-split-wrapper">
        <!-- Left Side: Brand Panel -->
        <div class="auth-sidebar-panel">
            <div class="ambient-glow glow-1"></div>
            <div class="ambient-glow glow-2"></div>
            
            <div class="auth-sidebar-content">
                <a href="{{ route('landing') }}" class="logo-link">
                    <img src="/img/youextractor-logo.jpg" alt="YouExtractor">
                    <span>YouExtractor</span>
                </a>
                
                <div class="auth-sidebar-hero">
                    <h2>Extract Clean Code From Video Tutorials</h2>
                    <p>Turn technical screen recordings and coding guides into clean, structural workspace directories in seconds.</p>
                </div>
                
                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <div class="feature-icon">
                            <i class="ph ph-magic-wand"></i>
                        </div>
                        <div>
                            <h4>AI Code Reconstruction</h4>
                            <p>Automatically rebuild codebases, scripts, and configs from frames and voice transcripts.</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature-item">
                        <div class="feature-icon">
                            <i class="ph ph-folder-open"></i>
                        </div>
                        <div>
                            <h4>Structured Workspace Exports</h4>
                            <p>Download clean file directories with standard file tree architecture.</p>
                        </div>
                    </div>
                    
                    <div class="auth-feature-item">
                        <div class="feature-icon">
                            <i class="ph ph-lock"></i>
                        </div>
                        <div>
                            <h4>Secure & Private</h4>
                            <p>Your search history and extracted code bases are fully encrypted and private.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Mock Preview Card -->
                <div class="mock-preview-container">
                    <div class="mock-preview-header">
                        <span class="mock-dot red"></span>
                        <span class="mock-dot yellow"></span>
                        <span class="mock-dot green"></span>
                        <span class="mock-title">extract-sandbox</span>
                    </div>
                    <div class="mock-preview-body">
                        <div class="mock-line"><span class="c-tag">Analyzing</span> <span>YouTube Video Transcript...</span></div>
                        <div class="mock-line"><span class="c-success">✓</span> <span>Parsed 1,245 words of programming instruction</span></div>
                        <div class="mock-line"><span class="c-info">→</span> <span>Generated 12 files in <span class="c-path">/src/components/</span></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Panel -->
        <div class="auth-form-panel">
            <div class="auth-container">
                <!-- Logo (Mobile only) -->
                <div class="logo-section">
                    <a href="{{ route('landing') }}" class="logo">
                        <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:32px;height:32px;border-radius:6px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);">
                        <span class="ds-type-heading-md" style="margin: 0; font-size: 1.5rem;">YouExtractor</span>
                    </a>
                </div>

                <!-- Sign In Card -->
                <ds-card variant="glass-accent" padding="lg">
                    @if(!empty($prefillUrl))
                        <div class="ds-badge-brand" style="margin-bottom: var(--theme-spacing-4); justify-content:center; font-size:11px; padding:4px 10px;">
                            <i class="ph ph-link" style="margin-right:6px;"></i> Continuing demo extraction
                            <span style="opacity:.7; margin-left:6px; font-family:monospace; font-size:10px;">{{ Str::limit($prefillUrl, 42) }}</span>
                        </div>
                    @endif

                    <div class="auth-header">
                        <h1 class="ds-type-heading-md auth-title">Welcome back</h1>
                        <p class="ds-type-body-sm auth-subtitle">Sign in to your account to continue</p>
                    </div>

                    <!-- Error Banner -->
                    @if ($errors->has('error'))
                        <div class="banner">
                            <span class="ds-badge-error" style="width: 100%; justify-content: center; padding: var(--theme-spacing-2.5) 0;">
                                <i class="ph ph-warning-circle" style="margin-right: var(--theme-spacing-2);"></i> {{ $errors->first('error') }}
                            </span>
                        </div>
                    @endif

                    <!-- Success Banner -->
                    @if (session('success'))
                        <div class="banner">
                            <span class="ds-badge-success" style="width: 100%; justify-content: center; padding: var(--theme-spacing-2.5) 0;">
                                <i class="ph ph-check-circle" style="margin-right: var(--theme-spacing-2);"></i> {{ session('success') }}
                            </span>
                        </div>
                    @endif

                    <!-- Google & GitHub Sign In -->
                    <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-3); margin-bottom: var(--theme-spacing-6);">
                        <a href="{{ route('auth.google') }}" class="google-btn" style="margin-bottom: 0;">
                            <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Continue with Google
                        </a>
                        <a href="{{ route('auth.github') }}" class="github-btn">
                            <i class="ph ph-github-logo" style="font-size: 20px;"></i>
                            Continue with GitHub
                        </a>
                    </div>

                    <!-- Divider -->
                    <div class="divider-container">
                        <div class="divider-line"></div>
                        <span class="divider-text">or continue with email</span>
                    </div>

                    <!-- Sign In Form -->
                    <form method="POST" action="{{ route('signin.submit') }}" id="signinForm">
                        @csrf
                        @if(!empty($prefillUrl))
                            <input type="hidden" name="youtube_url" value="{{ $prefillUrl }}">
                        @endif

                        <!-- Email -->
                        <div>
                            <ds-input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                label="Email Address" 
                                placeholder="you@example.com" 
                                icon="envelope" 
                                @error('email') error="{{ $message }}" @enderror
                                required>
                            </ds-input>
                        </div>

                        <!-- Password -->
                        <div class="form-row">
                            <div class="label-row">
                                <label class="label-text">Password</label>
                                <a href="#" class="forgot-link">Forgot password?</a>
                            </div>
                            <ds-input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="••••••••" 
                                icon="lock" 
                                @error('password') error="{{ $message }}" @enderror
                                required>
                            </ds-input>
                        </div>

                        <!-- Remember Me -->
                        <div class="checkbox-container">
                            <input 
                                type="checkbox" 
                                id="remember" 
                                name="remember" 
                                class="checkbox-input">
                            <label for="remember" class="checkbox-label">Remember me</label>
                        </div>

                        <!-- Submit Button -->
                        <div style="margin-top: var(--theme-spacing-2);">
                            <ds-button type="submit" label="Sign In" variant="primary" size="lg" full-width></ds-button>
                        </div>
                    </form>

                    <!-- Sign Up Link -->
                    <div class="footer-link">
                        Don't have an account? 
                        <a href="{{ route('signup') }}">Sign up free</a>
                    </div>
                </ds-card>

                <!-- Terms / Privacy Links -->
                <p class="terms-text" style="text-align: center; font-size: var(--theme-font-size-xs); color: var(--ds-text-muted); margin: 0;">
                    <a href="{{ route('terms') }}" style="color: var(--ds-text-secondary); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-primary)'" onmouseout="this.style.color='var(--ds-text-secondary)'">Terms of Service</a>
                    <span style="margin: 0 8px; opacity: 0.5;">•</span>
                    <a href="{{ route('privacy') }}" style="color: var(--ds-text-secondary); text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-primary)'" onmouseout="this.style.color='var(--ds-text-secondary)'">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
    <script>
        // Form submission wiring
        document.querySelector('ds-button[type="submit"]').addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('signinForm');
            if (form) {
                form.submit();
            }
        });
    </script>
</body>
</html>