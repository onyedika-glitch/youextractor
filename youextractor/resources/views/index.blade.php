<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Code Extractor - Extract Code from Programming Tutorials</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 text-white min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-black/30 backdrop-blur-sm border-b border-purple-500/20">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            🎬 YouTube Code Extractor
                        </h1>
                        <p class="text-gray-400 mt-1">Extract code from programming tutorials instantly</p>
                    </div>
                    <a href="/videos" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition flex items-center gap-2">
                        📚 My Extractions
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold mb-4">Learn Faster, Code Smarter</h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Paste any programming tutorial URL and get all the code snippets 
                    organized by tech stack, ready to download.
                </p>
            </div>

            <!-- Input Section -->
            <div class="bg-gray-800/50 backdrop-blur rounded-2xl p-8 mb-8 border border-purple-500/30 shadow-2xl">
                <form id="videoForm" class="space-y-6">
                    <div>
                        <label class="block text-lg font-medium mb-3">YouTube Video URL</label>
                        <div class="flex gap-4">
                            <input 
                                type="text" 
                                id="youtubeUrl" 
                                placeholder="https://www.youtube.com/watch?v=..."
                                class="flex-1 px-5 py-4 bg-gray-900 border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 text-lg"
                                required>
                            <button 
                                type="submit" 
                                id="submitBtn"
                                class="px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 rounded-xl font-bold text-lg transition transform hover:scale-105 flex items-center gap-2">
                                <span id="submitText">🚀 Extract Code</span>
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
                            <p class="text-sm text-gray-400 mt-1">This may take a moment for AI code extraction</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="results" class="space-y-6"></div>

            <!-- Features Section -->
            <div id="features" class="grid md:grid-cols-3 gap-6 mt-12">
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">🔍</div>
                    <h3 class="text-xl font-bold mb-2">Smart Detection</h3>
                    <p class="text-gray-400">AI-powered extraction that identifies programming languages and frameworks automatically.</p>
                </div>
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">📁</div>
                    <h3 class="text-xl font-bold mb-2">Organized Files</h3>
                    <p class="text-gray-400">Code is organized into proper file structure with correct extensions and paths.</p>
                </div>
                <div class="bg-gray-800/30 rounded-xl p-6 border border-gray-700">
                    <div class="text-3xl mb-3">⬇️</div>
                    <h3 class="text-xl font-bold mb-2">Download Ready</h3>
                    <p class="text-gray-400">Get a ZIP file with all code, README, setup instructions, and dependencies.</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-black/30 border-t border-purple-500/20 mt-12">
            <div class="max-w-6xl mx-auto px-4 py-6 text-center text-gray-400">
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
            'Analyzing video transcript...',
            'Detecting programming languages...',
            'Extracting code snippets...',
            'Organizing files by tech stack...',
            'Almost done...'
        ];

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Reset UI
            errorDiv.classList.add('hidden');
            resultsContainer.innerHTML = '';
            featuresSection.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            submitBtn.disabled = true;
            submitText.textContent = 'Extracting...';
            spinner.classList.remove('hidden');

            // Cycle through loading messages
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

                const data = await response.json();

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
                submitText.textContent = '🚀 Extract Code';
                spinner.classList.add('hidden');
            }
        });

        function displayResults(video) {
            const hasCode = video.code_snippets && video.code_snippets.length > 0;
            const stack = video.tech_stack;

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

            let codeFilesHtml = '';
            if (hasCode) {
                codeFilesHtml = `
                    <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xl font-bold flex items-center gap-2">
                                📁 Extracted Files (${video.code_snippets.length})
                            </h4>
                            <a href="/api/videos/${video.id}/download" 
                               class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 rounded-lg font-bold transition flex items-center gap-2">
                                ⬇️ Download ZIP
                            </a>
                        </div>
                        <div class="space-y-4">
                            ${video.code_snippets.map((file, idx) => `
                                <div class="bg-gray-900 rounded-lg overflow-hidden">
                                    <div class="flex items-center justify-between px-4 py-2 bg-gray-800 border-b border-gray-700">
                                        <div class="flex items-center gap-2">
                                            <span class="text-yellow-400">📄</span>
                                            <span class="font-mono text-sm">${file.path || file.filename}</span>
                                            <span class="px-2 py-0.5 bg-gray-700 rounded text-xs">${file.language}</span>
                                        </div>
                                        <button onclick="copyCode(${idx})" class="text-gray-400 hover:text-white text-sm">
                                            📋 Copy
                                        </button>
                                    </div>
                                    <pre class="p-4 overflow-x-auto text-sm"><code id="code-${idx}" class="language-${file.language}">${escapeHtml(file.code)}</code></pre>
                                    ${file.description ? `<div class="px-4 py-2 bg-gray-800/50 text-sm text-gray-400 border-t border-gray-700">${file.description}</div>` : ''}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            const html = `
                <div class="space-y-6 animate-fadeIn">
                    <!-- Video Info Card -->
                    <div class="bg-gray-800/50 rounded-xl p-6 border border-purple-500/30">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold mb-2">${escapeHtml(video.title)}</h3>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    ${stackBadges}
                                    ${hasCode ? `<span class="px-3 py-1 bg-green-600 rounded-full text-sm">${video.code_snippets.length} files extracted</span>` : '<span class="px-3 py-1 bg-yellow-600 rounded-full text-sm">No code detected</span>'}
                                </div>
                                <div class="flex gap-4 text-sm text-gray-400">
                                    <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" class="flex items-center gap-1 hover:text-purple-400">
                                        🔗 Watch on YouTube
                                    </a>
                                    ${hasCode ? `
                                        <a href="/api/videos/${video.id}/download" class="flex items-center gap-1 hover:text-green-400">
                                            ⬇️ Download Code
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Explanation -->
                    <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700">
                        <h4 class="text-xl font-bold mb-4">📖 Summary</h4>
                        <div class="prose prose-invert max-w-none">
                            <div class="text-gray-300 whitespace-pre-wrap">${escapeHtml(video.explanation)}</div>
                        </div>
                    </div>

                    ${video.setup_instructions ? `
                        <div class="bg-gray-800/50 rounded-xl p-6 border border-gray-700">
                            <h4 class="text-xl font-bold mb-4">🛠️ Setup Instructions</h4>
                            <pre class="bg-gray-900 p-4 rounded-lg overflow-x-auto"><code class="language-bash">${escapeHtml(video.setup_instructions)}</code></pre>
                        </div>
                    ` : ''}

                    ${codeFilesHtml}
                </div>
            `;

            resultsContainer.innerHTML = html;
            
            // Apply syntax highlighting
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
        }

        function copyCode(idx) {
            const codeEl = document.getElementById(`code-${idx}`);
            navigator.clipboard.writeText(codeEl.textContent).then(() => {
                // Show toast or feedback
                alert('Code copied to clipboard!');
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

        // Add fadeIn animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fadeIn { animation: fadeIn 0.5s ease-out; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
