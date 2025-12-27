<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - YouExtractor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background Blobs -->
    <div class="absolute top-0 -left-40 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 -right-40 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative w-full max-w-md p-8 glass-panel">
        <div class="bg-gray-800/50 backdrop-blur-xl border border-gray-700/50 rounded-2xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <div class="text-4xl mb-4">🚀</div>
                <h1 class="text-2xl font-bold mb-2">Create Account</h1>
                <p class="text-gray-400">Join thousands of developers learning faster</p>
            </div>

            <div class="space-y-4">
                <a href="/auth/google" class="w-full flex items-center justify-center gap-3 bg-white text-gray-900 hover:bg-gray-100 font-bold py-3.5 px-4 rounded-xl transition duration-300 shadow-lg">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-6 h-6" alt="Google">
                    Sign up with Google
                </a>
            </div>

            <p class="mt-8 text-center text-sm text-gray-500">
                By signing up, you verify that you are a real person and agree to our 
                <a href="#" class="text-purple-400 hover:text-purple-300">Terms of Service</a>.
            </p>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                Already have an account? 
                <a href="/login" class="text-purple-400 font-semibold hover:text-purple-300">Sign in</a>
            </p>
        </div>
    </div>
    <!-- Floating Donate Button -->
    <a href="https://buymeacoffee.com/omogo" target="_blank" 
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 rounded-full shadow-lg font-semibold transition-all hover:scale-105">
        ☕ Buy me a coffee
    </a>
</body>
</html>
