<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Video Extractor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <h1 class="text-3xl font-bold">🎥 YouTube Tech Video Explainer</h1>
                <p class="text-gray-400 mt-2">Extract, transcribe, and explain tech videos with AI</p>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
            <!-- Input Section -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8 border border-gray-700">
                <h2 class="text-2xl font-bold mb-4">Extract Video</h2>
                
                <form id="videoForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">YouTube URL</label>
                        <input 
                            type="url" 
                            id="youtubeUrl" 
                            placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            required>
                    </div>
                    
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition">
                            <span id="submitText">Extract & Explain</span>
                            <span id="spinner" class="hidden ml-2">⏳</span>
                        </button>
                        
                        <a href="/videos" 
                           class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded font-semibold transition">
                            View All Videos
                        </a>
                    </div>
                </form>

                <!-- Error Message -->
                <div id="error" class="mt-4 p-4 bg-red-900 border border-red-700 rounded hidden text-red-200">
                    <strong>Error:</strong> <span id="errorText"></span>
                </div>

                <!-- Loading State -->
                <div id="loading" class="mt-4 hidden">
                    <div class="flex items-center gap-3 text-blue-400">
                        <div class="animate-spin">⏳</div>
                        <span>Extracting video information...</span>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="results" class="space-y-6"></div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 mt-8">
            <div class="max-w-6xl mx-auto px-4 py-6 text-center text-gray-400 text-sm">
                <p>YouTube Video Extractor & Explainer • Powered by OpenAI & Laravel</p>
            </div>
        </footer>
    </div>

    <script>
        const form = document.getElementById('videoForm');
        const youtubeUrl = document.getElementById('youtubeUrl');
        const resultsContainer = document.getElementById('results');
        const errorDiv = document.getElementById('error');
        const errorText = document.getElementById('errorText');
        const loadingDiv = document.getElementById('loading');
        const submitBtn = document.querySelector('button[type="submit"]');
        const submitText = document.getElementById('submitText');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Reset UI
            errorDiv.classList.add('hidden');
            resultsContainer.innerHTML = '';
            loadingDiv.classList.remove('hidden');
            submitBtn.disabled = true;
            submitText.textContent = 'Processing...';
            spinner.classList.remove('hidden');

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
            } finally {
                loadingDiv.classList.add('hidden');
                submitBtn.disabled = false;
                submitText.textContent = 'Extract & Explain';
                spinner.classList.add('hidden');
            }
        });

        function displayResults(video) {
            const duration = formatDuration(video.duration);
            const publishDate = new Date(video.published_at).toLocaleDateString();

            let codeSnippetsHtml = '';
            if (video.code_snippets && video.code_snippets.length > 0) {
                codeSnippetsHtml = `
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-3">📝 Code Snippets</h4>
                        <div class="space-y-3">
                            ${video.code_snippets.map((snippet, idx) => `
                                <pre class="bg-gray-900 p-4 rounded overflow-x-auto"><code>${escapeHtml(snippet)}</code></pre>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            const html = `
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 animate-fadeIn">
                    <div class="flex gap-4 mb-4">
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold mb-2">${escapeHtml(video.title)}</h3>
                            <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                                <span>📺 Duration: ${duration}</span>
                                <span>📅 Published: ${publishDate}</span>
                                <span>🔗 <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" class="text-blue-400 hover:underline">View on YouTube</a></span>
                            </div>
                        </div>
                    </div>

                    ${video.summary ? `
                        <div class="bg-blue-900 bg-opacity-30 border border-blue-700 p-4 rounded mb-6">
                            <h4 class="font-semibold text-blue-300 mb-2">✨ Summary</h4>
                            <p>${escapeHtml(video.summary)}</p>
                        </div>
                    ` : ''}

                    ${video.description ? `
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold mb-2">📋 Description</h4>
                            <p class="text-gray-300">${escapeHtml(video.description)}</p>
                        </div>
                    ` : ''}

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-3">🤖 AI Explanation</h4>
                        <div class="bg-gray-700 p-4 rounded text-gray-200 whitespace-pre-wrap">
                            ${escapeHtml(video.explanation)}
                        </div>
                    </div>

                    ${codeSnippetsHtml}

                    ${video.transcript && !video.transcript.includes('not available') ? `
                        <details class="mt-6">
                            <summary class="cursor-pointer font-semibold text-gray-300 hover:text-white">📄 Full Transcript</summary>
                            <div class="bg-gray-700 p-4 rounded mt-3 text-sm text-gray-300 max-h-64 overflow-y-auto">
                                ${escapeHtml(video.transcript)}
                            </div>
                        </details>
                    ` : ''}
                </div>
            `;

            resultsContainer.innerHTML = html;
        }

        function showError(message) {
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function formatDuration(seconds) {
            if (!seconds) return 'N/A';
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) {
                return `${hours}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${minutes}:${String(secs).padStart(2, '0')}`;
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
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .animate-fadeIn {
                animation: fadeIn 0.3s ease-in-out;
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
