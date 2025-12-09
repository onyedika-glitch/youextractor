<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Videos - YouTube Extractor</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">📚 Extracted Videos</h1>
                        <p class="text-gray-400 mt-2">All your extracted and explained videos</p>
                    </div>
                    <a href="/" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition">
                        ➕ Extract New
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-6xl mx-auto w-full px-4 py-8">
            <!-- Search Bar -->
            <div class="mb-8">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search videos by title or content..."
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
            </div>

            <!-- Videos Grid -->
            <div id="videosContainer" class="space-y-4">
                <div class="text-center text-gray-400 py-8">
                    <div class="animate-spin text-2xl mb-4">⏳</div>
                    <p>Loading videos...</p>
                </div>
            </div>

            <!-- No Results -->
            <div id="noResults" class="hidden text-center text-gray-400 py-8">
                <p class="text-lg">No videos found</p>
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
        let allVideos = [];
        const videosContainer = document.getElementById('videosContainer');
        const noResults = document.getElementById('noResults');
        const searchInput = document.getElementById('searchInput');

        // Load videos on page load
        async function loadVideos() {
            try {
                const response = await fetch('/api/videos');
                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Failed to load videos');
                }

                allVideos = data.data || [];
                displayVideos(allVideos);
            } catch (error) {
                videosContainer.innerHTML = `
                    <div class="text-center text-red-400 py-8">
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            }
        }

        function displayVideos(videos) {
            if (videos.length === 0) {
                videosContainer.classList.add('hidden');
                noResults.classList.remove('hidden');
                return;
            }

            videosContainer.classList.remove('hidden');
            noResults.classList.add('hidden');

            videosContainer.innerHTML = videos.map(video => `
                <a href="#" class="video-card block bg-gray-800 rounded-lg p-6 border border-gray-700 hover:border-blue-500 hover:bg-gray-750 transition cursor-pointer" onclick="viewVideo(${video.id}); return false;">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-xl font-bold flex-1">${escapeHtml(video.title)}</h3>
                        <span class="text-xs bg-blue-600 px-3 py-1 rounded-full ml-4">
                            ${formatDuration(video.duration)}
                        </span>
                    </div>

                    ${video.summary ? `
                        <p class="text-gray-300 mb-4 line-clamp-2">${escapeHtml(video.summary)}</p>
                    ` : ''}

                    <div class="flex justify-between items-center text-sm text-gray-400">
                        <span>📅 ${new Date(video.extracted_at).toLocaleDateString()}</span>
                        <span class="text-blue-400 hover:text-blue-300">View Details →</span>
                    </div>
                </a>
            `).join('');
        }

        searchInput.addEventListener('input', async (e) => {
            const query = e.target.value.trim();
            
            if (!query) {
                displayVideos(allVideos);
                return;
            }

            try {
                const response = await fetch(`/api/videos/search?q=${encodeURIComponent(query)}`);
                const data = await response.json();
                
                if (data.success) {
                    displayVideos(data.data || []);
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        });

        function viewVideo(videoId) {
            // In a full app, you'd navigate to a detail page
            // For now, show alert
            const video = allVideos.find(v => v.id === videoId);
            if (video) {
                alert(`Video: ${video.title}\n\n${video.explanation}`);
            }
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
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Load videos when page loads
        loadVideos();
    </script>
</body>
</html>
