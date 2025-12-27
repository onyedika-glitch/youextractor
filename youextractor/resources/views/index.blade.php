<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Code Extractor - Learn Programming Faster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        .tab-active { border-bottom: 3px solid #a855f7; color: #a855f7; }
        .prose h1, .prose h2, .prose h3 { color: white; }
        .prose p { color: #d1d5db; }
    </style>
</head>
<body class="gradient-bg text-white min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-black/30 backdrop-blur-sm border-b border-purple-500/20">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-purple-400">
                            🎬 YouTube Code Extractor
                        </h1>
                        <p class="text-gray-400 mt-1 text-sm sm:text-base">Extract code + Get complete tutorials from programming videos</p>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                        <div class="flex items-center gap-3">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="w-8 h-8 rounded-full border border-purple-500/50" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center font-bold text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="hidden sm:inline text-sm font-medium text-gray-300">{{ Auth::user()->name }}</span>
                        </div>
                        
                        <a href="/videos" class="px-3 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                            📚 My Library
                        </a>
                        
                        <form action="/logout" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg text-sm font-semibold transition">
                                Sign Out
                            </button>
                        </form>
                        @else
                        <div class="flex items-center gap-4">
                            <a href="/signin" class="text-gray-300 hover:text-white font-medium transition">Sign In</a>
                            <a href="/signup" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-bold transition">
                                Get Started
                            </a>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl mx-auto w-full px-4 py-8">
            <!-- Hero Section -->
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="text-2xl sm:text-4xl font-bold mb-4">Learn Faster, Code Smarter</h2>
                <p class="text-base sm:text-xl text-gray-300 max-w-3xl mx-auto px-2">
                    Paste any programming tutorial URL and get all the code snippets, 
                    <span class="text-purple-400 font-semibold">complete setup guides</span>, 
                    <span class="text-pink-400 font-semibold">IDE recommendations</span>, and 
                    <span class="text-green-400 font-semibold">step-by-step instructions</span>.
                </p>
            </div>

            <!-- Input Section -->
            <div class="bg-gray-800/50 backdrop-blur rounded-2xl p-8 mb-8 border border-purple-500/30 shadow-2xl">
                <form id="videoForm" class="space-y-4 sm:space-y-6">
                    <div>
                        <label class="block text-base sm:text-lg font-medium mb-3">YouTube Video URL</label>
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <input 
                                type="text" 
                                id="youtubeUrl" 
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="w-full sm:flex-1 px-4 sm:px-5 py-3 sm:py-4 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-base sm:text-lg"
                                required>
                            <button 
                                type="submit" 
                                id="submitBtn"
                                class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-purple-600 hover:bg-purple-700 rounded-xl font-bold text-base sm:text-lg transition transform hover:scale-105 flex items-center justify-center gap-2">
                                <span id="submitText">🚀 Extract & Learn</span>
                                <span id="spinner" class="hidden">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Error Message -->
                <div id="error" class="mt-4 p-4 bg-red-900/50 border border-red-500 rounded-xl hidden">
                    <div class="flex items-center gap-2 text-red-300">
                        <span>❌</span>
                        <span id="errorText"></span>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="loading" class="mt-6 hidden">
                    <div class="flex flex-col items-center gap-4 py-8">
                        <div class="relative">
                            <div class="w-16 h-16 border-4 border-purple-500/30 rounded-full"></div>
                            <div class="w-16 h-16 border-4 border-purple-500 border-t-transparent rounded-full animate-spin absolute top-0"></div>
                        </div>
                        <div class="text-center">
                            <p class="text-lg font-semibold text-purple-300" id="loadingText">Extracting video information...</p>
                            <p class="text-sm text-gray-400 mt-1">Generating tutorial guide, IDE recommendations, and code files...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="results" class="space-y-6"></div>

            <!-- Features Section -->
            <div id="features" class="grid md:grid-cols-4 gap-6 mt-12">
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">📖</div>
                    <h3 class="text-xl font-bold mb-2">Tutorial Guide</h3>
                    <p class="text-gray-400">Get a complete explanation of what the video teaches and key concepts.</p>
                </div>
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">💻</div>
                    <h3 class="text-xl font-bold mb-2">IDE Recommendations</h3>
                    <p class="text-gray-400">Best IDE for the tech stack with extensions and download links.</p>
                </div>
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">🛠️</div>
                    <h3 class="text-xl font-bold mb-2">Setup & Run Guide</h3>
                    <p class="text-gray-400">Step-by-step instructions to set up and run the code.</p>
                </div>
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">⬇️</div>
                    <h3 class="text-xl font-bold mb-2">Download Ready</h3>
                    <p class="text-gray-400">Get a ZIP file with all code, organized by project structure.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-black/30 border-t border-purple-500/20 mt-12">
            <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-400">
                <p>YouTube Code Extractor • Built for developers who learn by watching</p>
            </div>
        </footer>
    </div>

    <script>
        const form = document.getElementById('videoForm');
        const youtubeUrl = document.getElementById('youtubeUrl');
        const resultsContainer = document.getElementById('results');
        const featuresSection = document.getElementById('features');
        const errorDiv = document.getElementById('error');
        const errorText = document.getElementById('errorText');
        const loadingDiv = document.getElementById('loading');
        const loadingText = document.getElementById('loadingText');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const spinner = document.getElementById('spinner');

        const loadingMessages = [
            'Fetching video information...',
            'Analyzing video content...',
            'Generating tutorial guide...',
            'Finding best IDE for this stack...',
            'Creating setup instructions...',
            'Extracting code snippets...',
            'Organizing files by tech stack...',
            'Almost done...'
        ];

        let currentTab = 'overview';

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            errorDiv.classList.add('hidden');
            resultsContainer.innerHTML = '';
            featuresSection.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            submitBtn.disabled = true;
            submitText.textContent = 'Extracting...';
            spinner.classList.remove('hidden');

            let msgIndex = 0;
            const msgInterval = setInterval(() => {
                loadingText.textContent = loadingMessages[msgIndex % loadingMessages.length];
                msgIndex++;
            }, 2000);

            try {
                const response = await fetch('/api/videos/extract', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ youtube_url: youtubeUrl.value })
                });

                let data;
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    // Identify if it's an HTML error page
                    if (text.trim().startsWith('<')) {
                        console.error('Server returned HTML:', text);
                        throw new Error('Server error (check console for details)');
                    }
                    data = { success: false, error: text || response.statusText };
                }

                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Failed to extract video');
                }

                displayResults(data.data);
                youtubeUrl.value = '';
            } catch (error) {
                showError(error.message);
                featuresSection.classList.remove('hidden');
            } finally {
                clearInterval(msgInterval);
                loadingDiv.classList.add('hidden');
                submitBtn.disabled = false;
                submitText.textContent = '🚀 Extract & Learn';
                spinner.classList.add('hidden');
            }
        });

        function displayResults(video) {
            const hasCode = video.code_snippets && video.code_snippets.length > 0;
            const stack = video.tech_stack;
            const tutorialGuide = video.tutorial_guide;
            const ideRec = video.ide_recommendations;
            const prerequisites = video.prerequisites;
            const setupGuide = video.setup_guide;
            const runGuide = video.run_guide;

            let stackBadges = '';
            if (stack) {
                if (stack.primary) {
                    stackBadges += `<span class="px-3 py-1 bg-purple-600 rounded-full text-sm font-semibold">${stack.primary}</span>`;
                }
                if (stack.frameworks && stack.frameworks.length > 0) {
                    stack.frameworks.forEach(fw => {
                        stackBadges += `<span class="px-3 py-1 bg-blue-600 rounded-full text-sm">${fw}</span>`;
                    });
                }
            }

            const html = `
                <div class="space-y-4 sm:space-y-6 animate-fadeIn">
                    <!-- Video Info Card -->
                    <div class="bg-gray-800/50 rounded-xl p-4 sm:p-6 border border-purple-500/30">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-xl sm:text-2xl font-bold mb-3 break-words">${escapeHtml(video.title)}</h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    ${stackBadges}
                                    ${hasCode ? `<span class="px-3 py-1 bg-green-600 rounded-full text-sm">${video.code_snippets.length} files</span>` : ''}
                                </div>
                                <div class="flex flex-wrap gap-3 sm:gap-4 text-sm">
                                    <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" class="text-purple-400 hover:text-purple-300 flex items-center gap-1">
                                        🔗 Watch on YouTube
                                    </a>
                                    ${hasCode ? `<a href="/api/videos/${video.id}/download" class="text-green-400 hover:text-green-300 flex items-center gap-1">⬇️ Download All Code</a>` : ''}
                                </div>
                            </div>
                            ${hasCode ? `
                            <a href="/api/videos/${video.id}/download" class="w-full sm:w-auto px-4 sm:px-6 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-bold transition flex items-center justify-center gap-2 text-center">
                                ⬇️ Download ZIP
                            </a>` : ''}
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="bg-gray-800/50 rounded-xl border border-gray-700 overflow-hidden">
                        <div class="flex border-b border-gray-700 overflow-x-auto">
                            <button onclick="showTab('overview', '${video.id}')" id="tab-overview" class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hover:bg-gray-700/50 transition tab-active whitespace-nowrap text-sm sm:text-base">
                                📖 Tutorial
                            </button>
                            <button onclick="showTab('ide', '${video.id}')" id="tab-ide" class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hover:bg-gray-700/50 transition whitespace-nowrap text-sm sm:text-base">
                                💻 IDE
                            </button>
                            <button onclick="showTab('setup', '${video.id}')" id="tab-setup" class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hover:bg-gray-700/50 transition whitespace-nowrap text-sm sm:text-base">
                                🛠️ Setup
                            </button>
                            <button onclick="showTab('run', '${video.id}')" id="tab-run" class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hover:bg-gray-700/50 transition whitespace-nowrap text-sm sm:text-base">
                                ▶️ Run
                            </button>
                            <button onclick="showTab('code', '${video.id}')" id="tab-code" class="px-4 sm:px-6 py-3 sm:py-4 font-semibold hover:bg-gray-700/50 transition whitespace-nowrap text-sm sm:text-base">
                                📁 Code (${video.code_snippets?.length || 0})
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div class="p-4 sm:p-6">
                            <!-- Overview Tab -->
                            <div id="content-overview" class="tab-content">
                                ${renderTutorialGuide(tutorialGuide)}
                            </div>

                            <!-- IDE Tab -->
                            <div id="content-ide" class="tab-content hidden">
                                ${renderIDERecommendations(ideRec, prerequisites)}
                            </div>

                            <!-- Setup Tab -->
                            <div id="content-setup" class="tab-content hidden">
                                ${renderSetupGuide(setupGuide, prerequisites)}
                            </div>

                            <!-- Run Tab -->
                            <div id="content-run" class="tab-content hidden">
                                ${renderRunGuide(runGuide)}
                            </div>

                            <!-- Code Tab -->
                            <div id="content-code" class="tab-content hidden">
                                ${renderCodeFiles(video.code_snippets, video.id)}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            resultsContainer.innerHTML = html;
            
            // Apply syntax highlighting
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }

        function renderTutorialGuide(guide) {
            if (!guide) {
                return `<p class="text-gray-400">Tutorial guide is being generated...</p>`;
            }

            let html = '';
            
            if (guide.overview) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">📖 Overview</h3>
                        <p class="text-gray-300 leading-relaxed whitespace-pre-wrap">${escapeHtml(guide.overview)}</p>
                    </div>
                `;
            }

            if (guide.key_concepts && guide.key_concepts.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">🧠 Key Concepts</h3>
                        <div class="space-y-4">
                            ${guide.key_concepts.map(concept => `
                                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700">
                                    <h4 class="font-bold text-white mb-2">${escapeHtml(concept.concept)}</h4>
                                    <p class="text-gray-400">${escapeHtml(concept.explanation)}</p>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (guide.learning_outcomes && guide.learning_outcomes.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">🎯 What You'll Learn</h3>
                        <ul class="space-y-2">
                            ${guide.learning_outcomes.map(outcome => `
                                <li class="flex items-start gap-2">
                                    <span class="text-green-400">✓</span>
                                    <span class="text-gray-300">${escapeHtml(outcome)}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                `;
            }

            return html || '<p class="text-gray-400">No tutorial guide available.</p>';
        }

        function renderIDERecommendations(ide, prerequisites) {
            if (!ide) {
                return `<p class="text-gray-400">IDE recommendations are being generated...</p>`;
            }

            let html = '';

            if (ide.primary) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">⭐ Recommended IDE</h3>
                        <div class="bg-purple-900/50 rounded-xl p-6 border border-purple-500/30">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-2xl font-bold text-white">${escapeHtml(ide.primary.name)}</h4>
                                    <p class="text-gray-300 mt-2">${escapeHtml(ide.primary.reason)}</p>
                                </div>
                                <a href="${escapeHtml(ide.primary.download_url)}" target="_blank" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition">
                                    📥 Download
                                </a>
                            </div>
                            ${ide.primary.extensions && ide.primary.extensions.length > 0 ? `
                                <div class="mt-4">
                                    <p class="text-sm text-gray-400 mb-2">Recommended Extensions:</p>
                                    <div class="flex flex-wrap gap-2">
                                        ${ide.primary.extensions.map(ext => `
                                            <span class="px-3 py-1 bg-gray-800 rounded-full text-sm">${escapeHtml(ext)}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            }

            if (ide.alternatives && ide.alternatives.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">🔄 Alternatives</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            ${ide.alternatives.map(alt => `
                                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700">
                                    <div class="flex items-start justify-between">
                                        <h4 class="font-bold text-white">${escapeHtml(alt.name)}</h4>
                                        <a href="${escapeHtml(alt.download_url)}" target="_blank" class="text-purple-400 hover:text-purple-300 text-sm">
                                            Download →
                                        </a>
                                    </div>
                                    <p class="text-gray-400 text-sm mt-2">${escapeHtml(alt.reason)}</p>
                                    ${alt.extensions && alt.extensions.length > 0 ? `
                                        <div class="mt-3 flex flex-wrap gap-1">
                                            ${alt.extensions.map(ext => `
                                                <span class="px-2 py-0.5 bg-gray-800 rounded text-xs">${escapeHtml(ext)}</span>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (prerequisites && prerequisites.knowledge && prerequisites.knowledge.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">📚 Required Knowledge</h3>
                        <ul class="space-y-2">
                            ${prerequisites.knowledge.map(k => `
                                <li class="flex items-center gap-2">
                                    <span class="text-blue-400">📘</span>
                                    <span class="text-gray-300">${escapeHtml(k)}</span>
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                `;
            }

            return html || '<p class="text-gray-400">No IDE recommendations available.</p>';
        }

        function renderSetupGuide(setupGuide, prerequisites) {
            let html = '';

            if (prerequisites && prerequisites.software && prerequisites.software.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">📦 Prerequisites</h3>
                        <div class="space-y-3">
                            ${prerequisites.software.map(sw => `
                                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700 flex items-center justify-between">
                                    <div>
                                        <h4 class="font-bold text-white">${escapeHtml(sw.name)}</h4>
                                        <p class="text-gray-400 text-sm">${escapeHtml(sw.purpose)}</p>
                                    </div>
                                    <a href="${escapeHtml(sw.download_url)}" target="_blank" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 rounded-lg text-sm font-semibold transition">
                                        Download
                                    </a>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (setupGuide && setupGuide.steps && setupGuide.steps.length > 0) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-purple-400">🛠️ Setup Steps</h3>
                        <div class="space-y-4">
                            ${setupGuide.steps.map(step => `
                                <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-700">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center font-bold">${step.step}</span>
                                        <h4 class="font-bold text-white">${escapeHtml(step.title)}</h4>
                                    </div>
                                    <p class="text-gray-400 mb-3">${escapeHtml(step.explanation)}</p>
                                    ${step.commands && step.commands.length > 0 ? `
                                        <div class="bg-gray-950 rounded-lg p-3">
                                            ${step.commands.map(cmd => `
                                                <div class="flex items-center justify-between group">
                                                    <code class="text-green-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                                    <button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-white transition text-sm">
                                                        📋 Copy
                                                    </button>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            return html || '<p class="text-gray-400">No setup guide available.</p>';
        }

        function renderRunGuide(runGuide) {
            if (!runGuide) {
                return `<p class="text-gray-400">Run guide is being generated...</p>`;
            }

            let html = '';

            if (runGuide.development) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-green-400">🔧 Development Mode</h3>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-green-500/30">
                            <p class="text-gray-300 mb-3">${escapeHtml(runGuide.development.explanation)}</p>
                            <div class="bg-gray-950 rounded-lg p-3 mb-3">
                                ${runGuide.development.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-green-400 font-mono">${escapeHtml(cmd)}</code>
                                        <button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-white transition text-sm">
                                            📋
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                            ${runGuide.development.access_url ? `
                                <p class="text-sm text-gray-400">Access at: <a href="${escapeHtml(runGuide.development.access_url)}" target="_blank" class="text-purple-400 hover:text-purple-300">${escapeHtml(runGuide.development.access_url)}</a></p>
                            ` : ''}
                        </div>
                    </div>
                `;
            }

            if (runGuide.production) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-blue-400">🚀 Production Build</h3>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-blue-500/30">
                            <p class="text-gray-300 mb-3">${escapeHtml(runGuide.production.explanation)}</p>
                            <div class="bg-gray-950 rounded-lg p-3">
                                ${runGuide.production.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-blue-400 font-mono">${escapeHtml(cmd)}</code>
                                        <button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-white transition text-sm">
                                            📋
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            if (runGuide.docker) {
                html += `
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 text-cyan-400">🐳 Docker</h3>
                        <div class="bg-gray-900/50 rounded-lg p-4 border border-cyan-500/30">
                            <p class="text-gray-300 mb-3">${escapeHtml(runGuide.docker.explanation)}</p>
                            <div class="bg-gray-950 rounded-lg p-3">
                                ${runGuide.docker.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-cyan-400 font-mono">${escapeHtml(cmd)}</code>
                                        <button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" class="opacity-0 group-hover:opacity-100 text-gray-500 hover:text-white transition text-sm">
                                            📋
                                        </button>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            return html || '<p class="text-gray-400">No run guide available.</p>';
        }

        function renderCodeFiles(files, videoId) {
            if (!files || files.length === 0) {
                return `<p class="text-gray-400">No code files extracted.</p>`;
            }

            return `
                <div class="space-y-4">
                    ${files.map((file, idx) => `
                        <div class="bg-gray-900 rounded-lg overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-2 bg-gray-800 border-b border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="text-yellow-400">📄</span>
                                    <span class="font-mono text-sm">${escapeHtml(file.path || file.filename)}</span>
                                    <span class="px-2 py-0.5 bg-gray-700 rounded text-xs">${escapeHtml(file.language)}</span>
                                </div>
                                <button onclick="copyCode(${idx})" class="text-gray-400 hover:text-white text-sm flex items-center gap-1">
                                    📋 Copy
                                </button>
                            </div>
                            <pre class="p-4 overflow-x-auto text-sm max-h-96"><code id="code-${idx}" class="language-${escapeHtml(file.language)}">${escapeHtml(file.code)}</code></pre>
                            ${file.description ? `<div class="px-4 py-2 bg-gray-800/50 text-sm text-gray-400 border-t border-gray-700">${escapeHtml(file.description)}</div>` : ''}
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function showTab(tabName, videoId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active from all tabs
            document.querySelectorAll('[id^="tab-"]').forEach(el => el.classList.remove('tab-active'));
            
            // Show selected tab content
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            // Add active to selected tab
            document.getElementById(`tab-${tabName}`).classList.add('tab-active');
            
            // Re-apply syntax highlighting for code tab
            if (tabName === 'code') {
                setTimeout(() => {
                    document.querySelectorAll('pre code').forEach((block) => {
                        hljs.highlightElement(block);
                    });
                }, 100);
            }
        }

        function copyCode(idx) {
            const codeEl = document.getElementById(`code-${idx}`);
            copyToClipboard(codeEl.textContent);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show brief feedback
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                toast.textContent = '✓ Copied to clipboard!';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            });
        }

        function showError(message) {
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
    <!-- Floating Donate Button -->
    <a href="https://buymeacoffee.com/omogo" target="_blank" 
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 bg-yellow-500 hover:bg-yellow-400 text-gray-900 rounded-full shadow-lg font-semibold transition-all hover:scale-105">
        ☕ Buy me a coffee
    </a>
</body>
</html>
