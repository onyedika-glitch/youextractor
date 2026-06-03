<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - YouTube Code Extractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Tailwind CSS (paired with Design System) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    <style>
        body { 
            font-family: var(--theme-font-sans); 
            background: var(--ds-surface-base);
            color: var(--ds-text-primary);
        }
        .logo-glow:hover {
            filter: drop-shadow(0 0 8px var(--ds-color-brand-subtle));
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 logo-glow transition-all duration-300">
                <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 2.25rem;"></i>
                <span class="text-2xl font-bold tracking-tight text-white">YouExtractor</span>
            </a>
        </div>

        <!-- Sign In Card -->
        <ds-card variant="glass-accent" padding="lg" class="shadow-2xl">
            <div class="text-center mb-8">
                <h1 class="ds-type-heading-md text-white mb-2">Welcome back</h1>
                <p class="ds-type-body-sm text-gray-400">Sign in to your account to continue</p>
            </div>

            <!-- Error Banner -->
            @if ($errors->has('error'))
                <div class="mb-6">
                    <span class="ds-badge-error w-full justify-center">
                        <i class="ph ph-warning-circle mr-2"></i> {{ $errors->first('error') }}
                    </span>
                </div>
            @endif

            <!-- Success Banner -->
            @if (session('success'))
                <div class="mb-6">
                    <span class="ds-badge-success w-full justify-center">
                        <i class="ph ph-check-circle mr-2"></i> {{ session('success') }}
                    </span>
                </div>
            @endif

            <!-- Google Sign In -->
            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 w-full py-3 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-200 mb-6">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>

            <!-- Divider -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-700/50"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-4 text-gray-500" style="background: var(--ds-surface-card);">or continue with email</span>
                </div>
            </div>

            <!-- Sign In Form -->
            <form method="POST" action="{{ route('signin.submit') }}" class="space-y-5">
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
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="ds-input__label">Password</label>
                        <a href="#" class="text-xs text-purple-400 hover:text-purple-300">Forgot password?</a>
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
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="w-4 h-4 bg-gray-900 border-gray-750 rounded text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-800">
                    <label for="remember" class="ml-2 text-sm text-gray-300">Remember me</label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <ds-button type="submit" label="Sign In" variant="primary" size="lg" full-width></ds-button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center text-sm text-gray-400">
                Don't have an account? 
                <a href="{{ route('signup') }}" class="text-purple-400 hover:text-purple-300 font-semibold">Sign up free</a>
            </div>
        </ds-card>
    </div>

    <!-- Buy Me a Coffee Widget -->
    <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="youextractor" data-description="Support me on Buy me a coffee!" data-message="Thanks for using CodeExtractor! Buy me a coffee to support development." data-color="#a855f7" data-position="Right" data-x_margin="18" data-y_margin="18"></script>
    
    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js"></script>
    <script>
        // Make sure standard button click submits the form
        document.querySelectorAll('ds-button[type="submit"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const form = btn.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });
    </script>
</body>
</html>