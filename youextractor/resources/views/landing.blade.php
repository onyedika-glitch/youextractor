<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YouExtractor - Learn Programming Faster</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Tailwind for utilities (paired with Design System) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
        }
        @keyframes float { 
            0% { transform: translateY(0px); } 
            50% { transform: translateY(-10px); } 
            100% { transform: translateY(0px); } 
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        
        /* Blur blobs */
        .blob-purple {
            background: radial-gradient(circle, rgba(168, 85, 247, 0.15) 0%, rgba(10, 16, 34, 0) 70%);
        }
        .blob-pink {
            background: radial-gradient(circle, rgba(236, 72, 153, 0.15) 0%, rgba(10, 16, 34, 0) 70%);
        }
    </style>
</head>
<body class="overflow-x-hidden min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="absolute w-full z-50 top-0">
        <div class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                <span class="text-xl font-bold tracking-tight text-white">YouExtractor</span>
            </a>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-gray-400 hover:text-white transition">Features</a>
                <a href="https://buymeacoffee.com/omogo" target="_blank" class="text-gray-400 hover:text-white transition">Donate</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('signin') }}">
                    <ds-button label="Sign In" variant="ghost" size="sm"></ds-button>
                </a>
                <a href="{{ route('signup') }}">
                    <ds-button label="Get Started" variant="primary" size="sm"></ds-button>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center pt-24 relative overflow-hidden">
        <!-- Background Ambient Glow Blobs -->
        <div class="absolute top-0 -left-40 w-[600px] h-[600px] blob-purple rounded-full filter blur-3xl opacity-60"></div>
        <div class="absolute top-20 -right-40 w-[600px] h-[600px] blob-pink rounded-full filter blur-3xl opacity-60"></div>
        
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-16 items-center relative z-10 w-full">
            <div class="space-y-6">
                <div>
                    <span class="ds-badge-brand">✨ AI-Powered Visual Learning</span>
                </div>
                <h1 class="ds-type-display-md text-white tracking-tight">
                    Turn YouTube Videos into <span style="background: var(--ds-gradient-brand); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Real Code</span>
                </h1>
                <p class="ds-type-body-lg text-gray-400 max-w-lg">
                    Stop pausing and typing. Instantly extract working code projects, tutorials, and setup guides from any programming video with one click.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <a href="{{ route('signup') }}" class="inline-block">
                        <ds-button label="Try It Free" variant="gradient" size="lg" icon="arrow-right" icon-position="right"></ds-button>
                    </a>
                    <a href="#features" class="inline-block">
                        <ds-button label="Explore Features" variant="secondary" size="lg" icon="compass"></ds-button>
                    </a>
                </div>
            </div>
            
            <div class="relative lg:h-[500px] flex items-center justify-center animate-float">
                <div class="absolute inset-0 bg-purple-500/5 rounded-3xl blur-3xl transform rotate-6"></div>
                <ds-card variant="glow-electric" padding="sm" class="w-full max-w-lg shadow-2xl">
                    <!-- Code Editor Mockup -->
                    <div class="flex items-center justify-between border-b border-gray-700/50 pb-3 mb-4">
                        <div class="flex gap-1.5">
                            <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                            <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                        </div>
                        <div class="ds-type-code-sm text-gray-500">App.tsx — Generated by YouExtractor</div>
                    </div>
                    <div class="space-y-2.5 font-mono text-sm">
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">1</span> <span><span style="color: var(--ds-text-brand)">import</span> React <span style="color: var(--ds-text-brand)">from</span> <span style="color: var(--ds-color-success-subtle)">'react'</span>;</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">2</span> <span><span style="color: var(--ds-text-brand)">import</span> { useState } <span style="color: var(--ds-text-brand)">from</span> <span style="color: var(--ds-color-success-subtle)">'react'</span>;</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">3</span> <span></span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">4</span> <span><span style="color: var(--ds-text-electric)">export default</span> <span style="color: var(--ds-text-brand)">function</span> <span style="color: var(--ds-color-warning-subtle)">App</span>() {</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">5</span> <span>    <span style="color: var(--ds-text-brand)">const</span> [count, setCount] = useState(0);</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">6</span> <span>    <span style="color: var(--ds-text-muted)">// Extracted from 12:45 of tutorial</span></span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">7</span> <span>    <span style="color: var(--ds-text-brand)">return</span> (</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">8</span> <span>        &lt;<span style="color: var(--ds-color-error-subtle)">div</span> className=<span style="color: var(--ds-color-success-subtle)">"app"</span>&gt;</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">9</span> <span>            &lt;<span style="color: var(--ds-color-error-subtle)">h1</span>&gt;Hello World&lt;/<span style="color: var(--ds-color-error-subtle)">h1</span>&gt;</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">10</span> <span>        &lt;/<span style="color: var(--ds-color-error-subtle)">div</span>&gt;</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">11</span> <span>    );</span></div>
                        <div class="flex gap-4"><span class="text-gray-600 select-none w-4 text-right">12</span> <span>}</span></div>
                    </div>
                </ds-card>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 space-y-4">
                <h2 class="ds-type-heading-lg text-white">Why YouExtractor?</h2>
                <p class="ds-type-body-lg text-gray-400 max-w-2xl mx-auto">Everything you need to learn from video tutorials efficiently.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <ds-card variant="glass" interactive padding="lg" class="h-full">
                    <div class="w-12 h-12 bg-purple-500/10 border border-purple-500/20 rounded-xl flex items-center justify-center mb-6">
                        <i class="ph ph-lightning" style="color: var(--ds-text-brand); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-3">Instant Code Extraction</h3>
                    <p class="ds-type-body-md text-gray-400">
                        Don't manually copy code from paused videos. We generate working file structures instantly.
                    </p>
                </ds-card>

                <!-- Feature 2 -->
                <ds-card variant="glass" interactive padding="lg" class="h-full">
                    <div class="w-12 h-12 bg-pink-500/10 border border-pink-500/20 rounded-xl flex items-center justify-center mb-6">
                        <i class="ph ph-book-open" style="color: var(--ds-text-accent); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-3">Detailed Guides</h3>
                    <p class="ds-type-body-md text-gray-400">
                        Get comprehensive written tutorials, setup instructions, and key concept explanations automatically.
                    </p>
                </ds-card>

                <!-- Feature 3 -->
                <ds-card variant="glass" interactive padding="lg" class="h-full">
                    <div class="w-12 h-12 bg-cyan-500/10 border border-cyan-500/20 rounded-xl flex items-center justify-center mb-6">
                        <i class="ph ph-download-simple" style="color: var(--ds-text-electric); font-size: 1.5rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-3">Download & Run</h3>
                    <p class="ds-type-body-md text-gray-400">
                        Download the entire project as a ZIP file, complete with dependencies and environment configuration.
                    </p>
                </ds-card>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black/40 border-t border-purple-500/10 py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4 text-center text-gray-500 text-sm">
            <p>&copy; {{ date('Y') }} YouExtractor. All rights reserved.</p>
            <p class="flex items-center gap-1">
                Built for developers who learn by watching <i class="ph ph-heart" style="color: var(--ds-text-accent)"></i>
            </p>
        </div>
    </footer>

    <!-- Floating Donate Button -->
    <a href="https://buymeacoffee.com/omogo" target="_blank" 
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 bg-[#FFDD00] hover:bg-[#ffea47] text-gray-900 rounded-full shadow-lg font-semibold transition-all hover:scale-105">
        ☕ Buy me a coffee
    </a>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js"></script>
</body>
</html>
