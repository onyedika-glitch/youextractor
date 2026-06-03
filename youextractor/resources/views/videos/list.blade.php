<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Videos - YouTube Extractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Tailwind CSS (paired with Design System) -->
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
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.4s var(--theme-ease-out); }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-black/30 backdrop-blur-md border-b border-purple-500/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                    <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                    <span class="text-xl font-bold tracking-tight text-white">YouExtractor</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="/">
                        <ds-button label="Extract New" variant="primary" size="sm" icon="plus"></ds-button>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-5xl mx-auto w-full px-6 py-12 space-y-8">
            <div class="space-y-2">
                <h1 class="ds-type-heading-lg text-white">📚 Extracted Videos</h1>
                <p class="ds-type-body-sm text-gray-400">All your extracted and explained video tutorials</p>
            </div>

            <!-- Search Bar -->
            <div>
                <ds-input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Search videos by title or content..."
                    icon="magnifying-glass"
                    size="md">
                </ds-input>
            </div>

            <!-- Videos Grid -->
            <div id="videosContainer" class="grid md:grid-cols-2 gap-6">
                <div class="col-span-2 text-center text-gray-400 py-12">
                    <div class="animate-spin text-3xl mb-4"><i class="ph ph-spinner"></i></div>
                    <p>Loading library...</p>
                </div>
            </div>

            <!-- Empty / No Results State -->
            <div id="noResults" class="hidden text-center py-16 max-w-md mx-auto">
                <ds-card variant="glass" padding="lg" class="space-y-6">
                    <div class="w-16 h-16 bg-purple-500/10 rounded-full flex items-center justify-center mx-auto">
                        <i class="ph ph-folder-open text-3xl" style="color: var(--ds-text-brand);"></i>
                    </div>
                    <div class="space-y-2">
                        <h3 class="ds-type-heading-sm text-white">No videos found</h3>
                        <p class="ds-type-body-sm text-gray-400">Try searching for another keyword or extract a new programming tutorial to start building your library.</p>
                    </div>
                    <div class="pt-2">
                        <a href="/">
                            <ds-button label="Extract a Video" variant="gradient" size="md" icon="rocket-launch"></ds-button>
                        </a>
                    </div>
                </ds-card>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-black/30 border-t border-purple-500/10 py-8 mt-12">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-500 text-sm">
                <p>YouTube Video Extractor & Explainer &bull; Powered by AI & Laravel</p>
            </div>
        </footer>
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js"></script>

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
                
                // Handle paginated response
                allVideos = data.data || data || [];
                displayVideos(allVideos);
            } catch (error) {
                videosContainer.innerHTML = `
                    <div class="col-span-2 text-center text-red-400 py-12">
                        <i class="ph ph-warning-circle text-3xl mb-3"></i>
                        <p>Failed to load videos: ${error.message}</p>
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
                <ds-card variant="glass" interactive padding="md" class="animate-fadeIn h-full" onclick="viewVideo(${video.id})">
                    <div class="flex flex-col h-full justify-between gap-4">
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-start gap-4">
                                <h3 class="ds-type-heading-sm text-white font-bold line-clamp-2" style="font-family: var(--theme-font-sans);">${escapeHtml(video.title)}</h3>
                                <span class="ds-badge-electric shrink-0">
                                    ${formatDuration(video.duration)}
                                </span>
                            </div>
        
                            ${video.summary ? `
                                <p class="ds-type-body-sm text-gray-400 line-clamp-2">${escapeHtml(video.summary)}</p>
                            ` : ''}
                        </div>
    
                        <div class="flex justify-between items-center text-xs text-gray-500 pt-3 border-t border-gray-700/30">
                            <span class="flex items-center gap-1">
                                <i class="ph ph-calendar"></i> ${new Date(video.extracted_at || video.created_at).toLocaleDateString()}
                            </span>
                            <span class="text-purple-400 font-semibold flex items-center gap-1 hover:text-purple-300">
                                View Details <i class="ph ph-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </ds-card>
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
                displayVideos(data.data || data || []);
            } catch (error) {
                console.error('Search error:', error);
            }
        });

        function viewVideo(videoId) {
            window.location.href = `/videos/${videoId}`;
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

        // Load videos when page loads
        loadVideos();
    </script>
</body>
</html>
