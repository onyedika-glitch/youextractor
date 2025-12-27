<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YouExtractor - Learn Programming Faster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .gradient-text { background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .gradient-bg { background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%); }
        @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-20px); } 100% { transform: translateY(0px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
</head>
<body class="gradient-bg text-white overflow-x-hidden">
    <!-- Navbar -->
    <nav class="absolute w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="flex items-center gap-2">
                <span class="text-3xl">🎬</span>
                <span class="text-xl font-bold tracking-tight">YouExtractor</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-gray-400 hover:text-white transition">Features</a>
                <a href="#how-it-works" class="text-gray-400 hover:text-white transition">How it Works</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('signin') }}" class="text-gray-300 hover:text-white font-medium transition">Sign In</a>
                <a href="{{ route('signup') }}" class="px-6 py-2.5 bg-purple-600 hover:bg-purple-700 rounded-full font-bold transition shadow-lg shadow-purple-500/20">
                    Get Started
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center pt-20 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute top-0 -left-40 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-40 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center relative z-10">
            <div>
                <div class="inline-block px-4 py-1.5 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-300 font-medium text-sm mb-6">
                    ✨ AI-Powered Visual Learning
                </div>
                <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-8">
                    Turn YouTube Videos into <span class="gradient-text">Real Code</span>
                </h1>
                <p class="text-xl text-gray-400 mb-10 leading-relaxed max-w-lg">
                    Stop pausing and typing. Instantly extract working code projects, tutorials, and setup guides from any programming video with one click.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('signup') }}" class="px-8 py-4 bg-white text-gray-900 rounded-full font-bold text-lg hover:bg-gray-100 transition shadow-xl flex items-center justify-center gap-2">
                        Try It Free
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                    <a href="#demo" class="px-8 py-4 bg-gray-800 text-white border border-gray-700 rounded-full font-bold text-lg hover:border-gray-500 transition flex items-center justify-center gap-2">
                        View Demo
                    </a>
                </div>
            </div>
            
            <div class="relative lg:h-[600px] flex items-center justify-center animate-float">
                <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/20 to-pink-500/20 rounded-3xl blur-2xl transform rotate-6"></div>
                <div class="relative w-full glass rounded-2xl p-6 shadow-2xl border border-gray-700/50">
                    <!-- Code Editor Mockup -->
                    <div class="flex items-center gap-2 mb-4 border-b border-gray-700/50 pb-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="ml-4 text-xs text-gray-500 font-mono">App.tsx — Generated by YouExtractor</div>
                    </div>
                    <div class="space-y-3 font-mono text-sm">
                        <div class="flex gap-4"><span class="text-gray-600 select-none">1</span> <span class="text-purple-400">import</span> React <span class="text-purple-400">from</span> <span class="text-green-400">'react'</span>;</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">2</span> <span class="text-purple-400">import</span> { useState } <span class="text-purple-400">from</span> <span class="text-green-400">'react'</span>;</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">3</span> </div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">4</span> <span class="text-blue-400">export default</span> <span class="text-purple-400">function</span> <span class="text-yellow-400">App</span>() {</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">5</span>     <span class="text-purple-400">const</span> [count, setCount] = <span class="text-blue-400">useState</span>(0);</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">6</span>     <span class="text-gray-500">// Extracted from 12:45 of tutorial</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">7</span>     <span class="text-purple-400">return</span> (</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">8</span>         &lt;<span class="text-red-400">div</span> <span class="text-blue-400">className</span>=<span class="text-green-400">"app"</span>&gt;</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">9</span>             &lt;<span class="text-red-400">h1</span>&gt;Hello World&lt;/<span class="text-red-400">h1</span>&gt;</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">10</span>        &lt;/<span class="text-red-400">div</span>&gt;</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">11</span>    );</div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none">12</span> }</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-gray-900 absolute w-full">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-5xl font-bold mb-6">Why YouExtractor?</h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">Everything you need to learn from video tutorials efficiently.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass p-8 rounded-3xl hover:bg-gray-800/50 transition duration-300">
                    <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        ⚡
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Instant Code Extraction</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Don't manually copy code from paused videos. We generate working file structures instantly.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="glass p-8 rounded-3xl hover:bg-gray-800/50 transition duration-300">
                    <div class="w-14 h-14 bg-pink-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        📚
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Detailed Guides</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Get comprehensive written tutorials, setup instructions, and key concept explanations automatically.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="glass p-8 rounded-3xl hover:bg-gray-800/50 transition duration-300">
                    <div class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        💾
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Download & Run</h3>
                    <p class="text-gray-400 leading-relaxed">
                        Download the entire project as a ZIP file, complete with dependencies and environment configuration.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-12 pt-full mt-auto relative top-[800px] md:top-[600px]">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-500">
            <p>&copy; {{ date('Y') }} YouExtractor. All rights reserved.</p>
        </div>
    </footer>
    <!-- Floating Donate Button -->
    <a href="https://buymeacoffee.com/omogo" target="_blank" 
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 rounded-full shadow-lg font-semibold transition-all hover:scale-105">
        ☕ Buy me a coffee
    </a>
</body>
</html>
