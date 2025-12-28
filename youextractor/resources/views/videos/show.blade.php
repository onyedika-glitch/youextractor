<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details - YouTube Extractor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">🎥 Video Details</h1>
                    </div>
                    <div class="flex gap-4">
                        <a href="/" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition">
                            ➕ Extract New
                        </a>
                        <a href="/videos" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded font-semibold transition">
                            📚 All Videos
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
            <div id="videoContent" class="space-y-6">
                <div class="text-center text-gray-400 py-8">
                    <div class="animate-spin text-2xl mb-4">⏳</div>
                    <p>Loading video details...</p>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 mt-8">
            <div class="max-w-6xl mx-auto px-4 py-6 text-center text-gray-400 text-sm">
                <p>YouTube Video Extractor & Explainer • Powered by OpenAI & Laravel</p>
            </div>
        </footer>
    </div>

    <script>
        const videoId = '{{ $video }}';
        const videoContent = document.getElementById('videoContent');

        async function loadVideo() {
            try {
                const response = await fetch(`/api/videos/${videoId}`);
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Failed to load video');
                }

                displayVideo(data.data);
            } catch (error) {
                videoContent.innerHTML = `
                    <div class="text-center text-red-400 py-8">
                        <p>Error: ${error.message}</p>
                        <a href="/videos" class="inline-block mt-4 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition">
                            Back to Videos
                        </a>
                    </div>
                `;
            }
        }

        function displayVideo(video) {
            const duration = formatDuration(video.duration);
            const publishDate = new Date(video.published_at).toLocaleDateString();

            let codeSnippetsHtml = '';
            if (video.code_snippets && video.code_snippets.length > 0) {
                codeSnippetsHtml = `
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h4 class="text-xl font-bold mb-4">📝 Code Snippets</h4>
                        <div class="space-y-3">
                            ${video.code_snippets.map((snippet, idx) => `
                                <pre class="bg-gray-900 p-4 rounded overflow-x-auto"><code>${escapeHtml(snippet)}</code></pre>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            videoContent.innerHTML = `
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h2 class="text-2xl font-bold mb-4">${escapeHtml(video.title)}</h2>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-6">
                        <span>📺 Duration: ${duration}</span>
                        <span>📅 Published: ${publishDate}</span>
                        <span>🔗 <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" class="text-blue-400 hover:underline">View on YouTube</a></span>
                    </div>

                    ${video.summary ? `
                        <div class="bg-blue-900 bg-opacity-30 border border-blue-700 p-4 rounded mb-6">
                            <h4 class="font-semibold text-blue-300 mb-2">✨ Summary</h4>
                            <p>${escapeHtml(video.summary)}</p>
                        </div>
                    ` : ''}
                </div>

                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h4 class="text-xl font-bold mb-4">🤖 AI Explanation</h4>
                    <div class="bg-gray-700 p-4 rounded text-gray-200 whitespace-pre-wrap">
                        ${escapeHtml(video.explanation)}
                    </div>
                </div>

                ${codeSnippetsHtml}

                ${video.description ? `
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h4 class="text-xl font-bold mb-4">📋 Description</h4>
                        <p class="text-gray-300">${escapeHtml(video.description)}</p>
                    </div>
                ` : ''}

                ${video.transcript && !video.transcript.includes('not available') ? `
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h4 class="text-xl font-bold mb-4">📄 Full Transcript</h4>
                        <div class="bg-gray-700 p-4 rounded text-sm text-gray-300 max-h-96 overflow-y-auto">
                            ${escapeHtml(video.transcript)}
                        </div>
                    </div>
                ` : ''}
            `;
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

        loadVideo();
    </script>
</body>
</html>
