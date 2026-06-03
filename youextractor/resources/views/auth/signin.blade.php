<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            align-items: center;
            justify-content: center;
            box-sizing: border-box;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            padding: var(--theme-spacing-6);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-8);
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
            margin-bottom: var(--theme-spacing-8);
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

        /* Google Sign-in button */
        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--theme-spacing-3);
            width: 100%;
            padding: var(--theme-spacing-3.5) 0;
            background: var(--theme-neutral-0);
            color: var(--theme-neutral-1000);
            border-radius: var(--theme-radius-xl);
            font-weight: var(--theme-font-weight-semibold);
            text-decoration: none;
            border: 1px solid transparent;
            cursor: pointer;
            box-sizing: border-box;
            font-family: var(--theme-font-sans);
            font-size: var(--theme-font-size-sm);
            transition: background var(--theme-motion-fast) var(--theme-ease-default);
            margin-bottom: var(--theme-spacing-6);
        }

        .google-btn:hover {
            background: var(--theme-neutral-100);
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

    <div class="auth-container">
        <!-- Logo -->
        <div class="logo-section">
            <a href="{{ route('landing') }}" class="logo">
                <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 2.25rem;"></i>
                <span class="ds-type-heading-md" style="margin: 0; font-size: 1.5rem;">YouExtractor</span>
            </a>
        </div>

        <!-- Sign In Card -->
        <ds-card variant="glass-accent" padding="lg">
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

            <!-- Google Sign In -->
            <a href="{{ route('auth.google') }}" class="google-btn">
                <svg style="width: 20px; height: 20px;" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>

            <!-- Divider -->
            <div class="divider-container">
                <div class="divider-line"></div>
                <span class="divider-text">or continue with email</span>
            </div>

            <!-- Sign In Form -->
            <form method="POST" action="{{ route('signin.submit') }}" id="signinForm">
                @csrf

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
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js"></script>
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