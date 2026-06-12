<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>Sign Up - YouTube Code Extractor</title>
    
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
            justify-content: space-between;
            box-sizing: border-box;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: var(--theme-spacing-6) 0;
            margin: auto 0;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-6);
        }

        .logo-section {
            text-align: center;
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
            margin-bottom: var(--theme-spacing-6);
        }

        .auth-title {
            margin: 0 0 var(--theme-spacing-2);
            color: var(--ds-text-primary);
        }

        .auth-subtitle {
            color: var(--ds-text-secondary);
            margin: 0;
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
            gap: var(--theme-spacing-4);
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

        .terms-text {
            text-align: center;
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
            margin: 0;
        }

        .terms-text a {
            color: var(--ds-text-secondary);
            text-decoration: none;
        }

        .terms-text a:hover {
            color: var(--ds-text-primary);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Logo -->
        <div class="logo-section">
            <a href="{{ route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:32px;height:32px;border-radius:6px;object-fit:cover;border:1px solid rgba(168,85,247,0.25);">
                <span class="ds-type-heading-md" style="margin: 0; font-size: 1.5rem;">YouExtractor</span>
            </a>
        </div>

        <!-- Sign Up Card -->
        <ds-card variant="glass-accent" padding="lg">
            @if(!empty($prefillUrl))
                <div class="ds-badge-brand" style="margin-bottom: var(--theme-spacing-4); justify-content:center; font-size:11px; padding:4px 10px;">
                    <i class="ph ph-link" style="margin-right:6px;"></i> Continuing demo extraction
                    <span style="opacity:.7; margin-left:6px; font-family:monospace; font-size:10px;">{{ Str::limit($prefillUrl, 42) }}</span>
                </div>
            @endif

            <div class="auth-header">
                <h1 class="ds-type-heading-md auth-title">Create your account</h1>
                <p class="ds-type-body-sm auth-subtitle">Start extracting code from videos today</p>
            </div>

            <!-- Google & GitHub Sign Up -->
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

            <!-- Sign Up Form -->
            <form method="POST" action="{{ route('signup.submit') }}" id="signupForm">
                @csrf
                @if(!empty($prefillUrl))
                    <input type="hidden" name="youtube_url" value="{{ $prefillUrl }}">
                @endif

                <!-- Name -->
                <div>
                    <ds-input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}" 
                        label="Full Name" 
                        placeholder="John Doe" 
                        icon="user" 
                        @error('name') error="{{ $message }}" @enderror
                        required>
                    </ds-input>
                </div>

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
                <div>
                    <ds-input 
                        type="password" 
                        id="password" 
                        name="password" 
                        label="Password"
                        placeholder="••••••••" 
                        icon="lock" 
                        @error('password') error="{{ $message }}" @enderror
                        required>
                    </ds-input>
                </div>

                <!-- Confirm Password -->
                <div>
                    <ds-input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        label="Confirm Password"
                        placeholder="••••••••" 
                        icon="lock" 
                        required>
                    </ds-input>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: var(--theme-spacing-2);">
                    <ds-button type="submit" label="Create Account" variant="primary" size="lg" full-width></ds-button>
                </div>
            </form>

            <!-- Sign In Link -->
            <div class="footer-link">
                Already have an account? 
                <a href="{{ route('signin') }}">Sign in</a>
            </div>
        </ds-card>

        <!-- Terms -->
        <p class="terms-text">
            By signing up, you agree to our 
            <a href="{{ route('terms') }}">Terms of Service</a> and 
            <a href="{{ route('privacy') }}">Privacy Policy</a>
        </p>
    </div>

    @include('partials.footer')

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>
    <script>
        // Form submission wiring
        document.querySelector('ds-button[type="submit"]').addEventListener('click', (e) => {
            e.preventDefault();
            const form = document.getElementById('signupForm');
            if (form) {
                form.submit();
            }
        });
    </script>
</body>
</html>