<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Code Extractor - Extract Code from Programming Tutorials</title>
    <meta name="description" content="Extract code snippets, get AI-powered tutorials, and learn faster from YouTube programming videos. Free and open source.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎬</text></svg>">
    <style>
        .gradient-text {
            background: linear-gradient(135deg, #a855f7 0%, #ec4899 50%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-gradient {
            background: radial-gradient(ellipse at top, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                        radial-gradient(ellipse at bottom right, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
        }
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(139, 92, 246, 0.2);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-900/80 backdrop-blur-lg border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🎬</span>
                    <span class="text-xl font-bold text-purple-400">YouExtractor</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-300 hover:text-white transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-gray-300 hover:text-white transition">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('signin') }}" class="px-4 py-2 text-gray-300 hover:text-white transition">Login</a>
                        <a href="{{ route('signup') }}" class="px-5 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500/10 border border-purple-500/30 rounded-full text-purple-400 text-sm mb-8">
                    <span>✨</span>
                    <span>Powered by Gemini AI</span>
                </div>
                
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight">
                    Learn Coding <span class="gradient-text">10x Faster</span><br>
                    from YouTube Tutorials
                </h1>
                
                <p class="text-xl sm:text-2xl text-gray-400 max-w-3xl mx-auto mb-10">
                    Extract code, get AI-generated tutorials, IDE recommendations, and step-by-step guides from any programming video. Plus, chat with AI about what you're learning!
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-purple-600 hover:bg-purple-700 rounded-xl font-bold text-lg transition transform hover:scale-105 flex items-center justify-center gap-2">
                        🚀 Start Extracting Free
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-xl font-bold text-lg transition flex items-center justify-center gap-2">
                        See How It Works
                    </a>
                </div>

                <!-- Demo Preview -->
                <div class="relative max-w-4xl mx-auto">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl blur-xl opacity-20"></div>
                    <div class="relative bg-gray-800/80 backdrop-blur border border-gray-700 rounded-2xl p-4 sm:p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="ml-4 text-gray-400 text-sm">youextractor.com</span>
                        </div>
                        <div class="bg-gray-900 rounded-xl p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" placeholder="https://youtube.com/watch?v=..." class="flex-1 px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-gray-300" disabled>
                                <button class="px-6 py-3 bg-purple-600 rounded-lg font-semibold" disabled>🚀 Extract</button>
                            </div>
                            <div class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm">
                                <div class="px-3 py-2 bg-gray-800 rounded-lg text-center">📖 Tutorial</div>
                                <div class="px-3 py-2 bg-gray-800 rounded-lg text-center">💻 IDE Setup</div>
                                <div class="px-3 py-2 bg-gray-800 rounded-lg text-center">📁 Code Files</div>
                                <div class="px-3 py-2 bg-gray-800 rounded-lg text-center">💬 AI Chat</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Everything You Need to Learn Faster</h2>
                <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                    Stop pausing videos to copy code. Get everything organized and ready to use.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        📁
                    </div>
                    <h3 class="text-xl font-bold mb-2">Complete Code Extraction</h3>
                    <p class="text-gray-400">
                        Get all code files from the tutorial, properly organized with correct file extensions and structure. Download as ZIP.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-pink-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        📖
                    </div>
                    <h3 class="text-xl font-bold mb-2">AI Tutorial Guide</h3>
                    <p class="text-gray-400">
                        Comprehensive explanations of concepts, key learnings, and step-by-step breakdowns generated by AI.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        💻
                    </div>
                    <h3 class="text-xl font-bold mb-2">IDE Recommendations</h3>
                    <p class="text-gray-400">
                        Get the best IDE/editor for your project with recommended extensions and plugins to boost productivity.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        🛠️
                    </div>
                    <h3 class="text-xl font-bold mb-2">Setup & Run Guides</h3>
                    <p class="text-gray-400">
                        Detailed instructions to install dependencies, configure the project, and run it locally or in production.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        💬
                    </div>
                    <h3 class="text-xl font-bold mb-2">AI Chat Assistant</h3>
                    <p class="text-gray-400">
                        Ask questions about the video content, get clarifications, and dive deeper into concepts with AI chat.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="card-hover bg-gray-800/50 border border-gray-700 rounded-2xl p-6">
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center text-2xl mb-4">
                        📚
                    </div>
                    <h3 class="text-xl font-bold mb-2">Personal Library</h3>
                    <p class="text-gray-400">
                        All your extractions saved in one place. Build your own learning library and access anytime.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-20 bg-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">How It Works</h2>
                <p class="text-xl text-gray-400">Three simple steps to transform any tutorial</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">1</div>
                    <h3 class="text-xl font-bold mb-2">Paste YouTube URL</h3>
                    <p class="text-gray-400">Copy the URL of any programming tutorial video from YouTube</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-pink-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">2</div>
                    <h3 class="text-xl font-bold mb-2">AI Extracts Everything</h3>
                    <p class="text-gray-400">Our AI analyzes the video and generates code, tutorials, and guides</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-600 rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-4">3</div>
                    <h3 class="text-xl font-bold mb-2">Learn & Build</h3>
                    <p class="text-gray-400">Download code, follow guides, and ask AI questions to master the content</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="py-20 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold mb-4">Supports All Major Languages</h2>
                <p class="text-xl text-gray-400">Extract code from tutorials in any programming language</p>
            </div>

            <div class="flex flex-wrap justify-center gap-4">
                @foreach(['JavaScript', 'Python', 'TypeScript', 'Java', 'React', 'Vue', 'Angular', 'Node.js', 'PHP', 'Laravel', 'Go', 'Rust', 'C++', 'C#', 'Swift', 'Kotlin'] as $tech)
                    <span class="px-4 py-2 bg-gray-800 border border-gray-700 rounded-full text-gray-300">{{ $tech }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-purple-900/50 to-pink-900/50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-4">Ready to Learn Faster?</h2>
            <p class="text-xl text-gray-300 mb-8">
                Join developers who are accelerating their learning with AI-powered extraction.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-gray-900 rounded-xl font-bold text-lg transition transform hover:scale-105">
                🚀 Get Started Free
            </a>
            <p class="mt-4 text-gray-400 text-sm">No credit card required • Free forever for personal use</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🎬</span>
                    <span class="text-xl font-bold text-purple-400">YouExtractor</span>
                </div>
                <div class="flex items-center gap-6 text-gray-400">
                    <a href="https://github.com/onyedika-glitch/youextractor" target="_blank" class="hover:text-white transition">GitHub</a>
                    <span>•</span>
                    <span>Open Source & Free</span>
                </div>
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} YouExtractor. MIT License.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>