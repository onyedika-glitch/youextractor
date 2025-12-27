<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - YouTube Code Extractor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
        }
        .gradient-text {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center gap-2">
                <span class="text-3xl">🎬</span>
                <span class="text-2xl font-bold text-purple-400">CodeExtractor</span>
            </a>
        </div>

        <!-- Sign In Card -->
        <div class="bg-gray-800/50 backdrop-blur rounded-2xl p-8 border border-gray-700/50 shadow-2xl">
            <h1 class="text-2xl font-bold text-center mb-2">Welcome back</h1>
            <p class="text-gray-400 text-center mb-8">Sign in to your account to continue</p>

            <!-- Error Message -->
            @if ($errors->has('error'))
                <div class="mb-6 p-4 bg-red-900/50 border border-red-500 rounded-xl">
                    <p class="text-red-300 text-sm">{{ $errors->first('error') }}</p>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-900/50 border border-green-500 rounded-xl">
                    <p class="text-green-300 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Google Sign In -->
            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-3 w-full py-3 bg-white text-gray-900 rounded-xl font-semibold hover:bg-gray-100 transition mb-6">
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
                    <div class="w-full border-t border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-gray-800/50 text-gray-400">or continue with email</span>
                </div>
            </div>

            <!-- Sign In Form -->
            <form method="POST" action="{{ route('signin.submit') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                        required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                        <a href="#" class="text-sm text-purple-400 hover:text-purple-300">Forgot password?</a>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 @error('password') border-red-500 @enderror"
                        placeholder="••••••••"
                        required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember" 
                        class="w-4 h-4 bg-gray-900 border-gray-700 rounded text-purple-600 focus:ring-purple-500 focus:ring-offset-gray-800">
                    <label for="remember" class="ml-2 text-sm text-gray-300">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 bg-purple-600 hover:bg-purple-700 rounded-xl font-bold text-lg transition transform hover:scale-[1.02]">
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
            <p class="mt-6 text-center text-gray-400">
                Don't have an account? 
                <a href="{{ route('signup') }}" class="text-purple-400 hover:text-purple-300 font-semibold">Sign up free</a>
            </p>
        </div>
    </div>

    <!-- Buy Me a Coffee Widget -->
    <script data-name="BMC-Widget" data-cfasync="false" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="youextractor" data-description="Support me on Buy me a coffee!" data-message="Thanks for using CodeExtractor! Buy me a coffee to support development." data-color="#a855f7" data-position="Right" data-x_margin="18" data-y_margin="18"></script>
</body>
</html>