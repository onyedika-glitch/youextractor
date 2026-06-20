<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>All Videos - YouTube Extractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
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
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: var(--theme-container-xl);
            margin: 0 auto;
            padding: 0 var(--theme-spacing-6);
            width: 100%;
            box-sizing: border-box;
        }

        /* Header */
        header {
            background: rgba(24, 24, 27, 0.3);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--ds-border-subtle);
            height: 80px;
            display: flex;
            align-items: center;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            text-decoration: none;
            color: var(--ds-text-primary);
            transition: opacity var(--theme-motion-fast) var(--theme-ease-default);
        }

        .logo:hover {
            opacity: 0.9;
        }

        /* Main Section */
        main.container {
            flex: 1;
            padding: 10vh var(--theme-spacing-6) var(--theme-spacing-24);
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-12);
        }

        .page-header {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
        }

        .page-title {
            margin: 0;
            color: var(--ds-text-primary);
        }

        .page-subtitle {
            color: var(--ds-text-secondary);
            margin: 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: var(--theme-spacing-6);
            margin-bottom: var(--theme-spacing-8);
        }

        /* Grid */
        .videos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: var(--theme-spacing-8);
        }

        .video-card-content {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
            gap: var(--theme-spacing-4);
        }

        .video-card-top {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-3);
        }

        .video-card-title-row {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: var(--theme-spacing-4);
        }

        .video-card-title {
            margin: 0;
            font-size: var(--theme-font-size-md);
            font-weight: var(--theme-font-weight-bold);
            color: var(--ds-text-primary);
            line-clamp: 2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .video-card-desc {
            margin: 0;
            color: var(--ds-text-secondary);
            font-size: var(--theme-font-size-sm);
            line-clamp: 2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .video-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
            padding-top: var(--theme-spacing-3);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .footer-action {
            color: var(--ds-text-brand);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-1);
            cursor: pointer;
        }

        .footer-action:hover {
            color: var(--ds-color-brand-subtle);
        }

        /* Loading / states */
        .loading-state {
            text-align: center;
            color: var(--ds-text-secondary);
            padding: var(--theme-spacing-12) 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--theme-spacing-4);
        }

        .spin-icon {
            animation: spin 1s linear infinite;
            font-size: 2rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .hidden {
            display: none !important;
        }

        /* Footer */
        footer {
            background: rgba(24, 24, 27, 0.8);
            border-top: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-8) 0;
            margin-top: var(--theme-spacing-12);
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container header-content">
            <a href="{{ Auth::check() ? route('dashboard') : route('landing') }}" class="logo">
                <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:26px;height:26px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);">
                <span class="ds-type-heading-sm" style="margin: 0;">YouExtractor</span>
            </a>
            <div>
                <a href="{{ route('dashboard') }}" style="text-decoration: none;">
                    <ds-button label="Back to Dashboard" variant="primary" size="sm" ></ds-button>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div class="page-header">
            <a href="{{ route('dashboard') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: var(--ds-text-secondary); font-size: var(--theme-font-size-sm); margin-bottom: var(--theme-spacing-2); transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-brand)'" onmouseout="this.style.color='var(--ds-text-secondary)'">
                <i class="ph ph-arrow-left"></i> Back to Dashboard
            </a>
            <h1 class="ds-type-heading-lg page-title">Extracted Videos</h1>
            <p class="ds-type-body-sm page-subtitle">All your extracted and explained video tutorials</p>
        </div>

        <!-- Library Stats Grid -->
        <div class="stats-grid" id="statsGrid">
            <ds-card variant="glass" padding="lg">
                <div style="display: flex; align-items: center; gap: var(--theme-spacing-4);">
                    <div style="width: 48px; height: 48px; background: rgba(20, 184, 166, 0.1); border-radius: var(--theme-radius-xl); display: flex; align-items: center; justify-content: center; color: var(--ds-text-brand); font-size: 1.5rem;">
                        <i class="ph ph-books"></i>
                    </div>
                    <div>
                        <div class="ds-type-label-sm" style="color: var(--ds-text-muted);">Library Size</div>
                        <div class="ds-type-heading-md" id="stat-total-videos" style="margin: 0; color: #fff;">0 Videos</div>
                    </div>
                </div>
            </ds-card>

            <ds-card variant="glass" padding="lg">
                <div style="display: flex; align-items: center; gap: var(--theme-spacing-4);">
                    <div style="width: 48px; height: 48px; background: rgba(56, 189, 248, 0.1); border-radius: var(--theme-radius-xl); display: flex; align-items: center; justify-content: center; color: var(--ds-text-electric); font-size: 1.5rem;">
                        <i class="ph ph-code"></i>
                    </div>
                    <div>
                        <div class="ds-type-label-sm" style="color: var(--ds-text-muted);">Total Code Files</div>
                        <div class="ds-type-heading-md" id="stat-total-files" style="margin: 0; color: #fff;">0 Files</div>
                    </div>
                </div>
            </ds-card>

            <ds-card variant="glass" padding="lg">
                <div style="display: flex; align-items: center; gap: var(--theme-spacing-4);">
                    <div style="width: 48px; height: 48px; background: rgba(234, 179, 8, 0.1); border-radius: var(--theme-radius-xl); display: flex; align-items: center; justify-content: center; color: var(--theme-yellow-500); font-size: 1.5rem;">
                        <i class="ph ph-cpu"></i>
                    </div>
                    <div>
                        <div class="ds-type-label-sm" style="color: var(--ds-text-muted);">Primary Tech Stacks</div>
                        <div id="stat-tech-stacks" style="display: flex; flex-wrap: wrap; gap: 4px; margin-top: 4px;">
                            <span class="ds-badge-brand">None</span>
                        </div>
                    </div>
                </div>
            </ds-card>

            <ds-card variant="glass" padding="lg">
                <div style="display: flex; align-items: center; gap: var(--theme-spacing-4);">
                    <div style="width: 48px; height: 48px; background: rgba(34, 197, 94, 0.1); border-radius: var(--theme-radius-xl); display: flex; align-items: center; justify-content: center; color: #22c55e; font-size: 1.5rem;">
                        <i class="ph ph-trend-up"></i>
                    </div>
                    <div>
                        <div class="ds-type-label-sm" style="color: var(--ds-text-muted);">Roadmap Progress</div>
                        <div class="ds-type-heading-md" id="stat-roadmap-progress" style="margin: 0; color: #fff;">0%</div>
                    </div>
                </div>
            </ds-card>
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
        <div id="videosContainer" class="videos-grid">
            <div class="loading-state" style="grid-column: 1 / -1;">
                <i class="ph ph-spinner spin-icon"></i>
                <p>Loading library...</p>
            </div>
        </div>

        <!-- Empty State -->
        <div id="noResults" class="hidden" style="max-width: 440px; margin: 0 auto; width: 100%;">
            <ds-card variant="glass" padding="lg">
                <div style="text-align: center; display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
                    <div style="width: 64px; height: 64px; background: rgba(20, 184, 166, 0.1); border-radius: var(--theme-radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                        <i class="ph ph-folder-open text-3xl" style="color: var(--ds-text-brand); font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h3 class="ds-type-heading-sm text-white" style="margin: 0 0 var(--theme-spacing-2);">No videos found</h3>
                        <p class="ds-type-body-sm text-gray-400" style="margin: 0;">Try searching for another keyword or extract a new programming tutorial to start building your library.</p>
                    </div>
                    <div style="margin-top: var(--theme-spacing-2);">
                        <a href="/" style="text-decoration: none;">
                            <ds-button label="Extract a Video" variant="gradient" size="md" icon="rocket-launch"></ds-button>
                        </a>
                    </div>
                </div>
            </ds-card>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>

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
                updateLibraryStats(allVideos);
            } catch (error) {
                videosContainer.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--ds-color-error); padding: var(--theme-spacing-12) 0;">
                        <i class="ph ph-warning-circle text-3xl" style="font-size: 2rem; margin-bottom: var(--theme-spacing-3);"></i>
                        <p>Failed to load videos: ${error.message}</p>
                    </div>
                `;
            }
        }

        function updateLibraryStats(videos) {
            // 1. Library Size
            document.getElementById('stat-total-videos').textContent = `${videos.length} Video${videos.length === 1 ? '' : 's'}`;

            // 2. Total Code Files & Stacks
            let totalFiles = 0;
            const stacks = new Set();
            videos.forEach(v => {
                const snippets = v.code_snippets || [];
                totalFiles += snippets.length;
                
                // Extract possible tech stacks from tags or description or title
                const titleLower = v.title.toLowerCase();
                if (titleLower.includes('react')) stacks.add('React');
                if (titleLower.includes('vue')) stacks.add('Vue');
                if (titleLower.includes('node')) stacks.add('Node.js');
                if (titleLower.includes('laravel')) stacks.add('Laravel');
                if (titleLower.includes('python')) stacks.add('Python');
                if (titleLower.includes('javascript') || titleLower.includes('js ')) stacks.add('JavaScript');
                if (titleLower.includes('typescript') || titleLower.includes('ts ')) stacks.add('TypeScript');
                if (titleLower.includes('css')) stacks.add('CSS');
                if (titleLower.includes('html')) stacks.add('HTML');
            });
            document.getElementById('stat-total-files').textContent = `${totalFiles} File${totalFiles === 1 ? '' : 's'}`;

            // 3. Render Tech Stack Badges
            const techStacksContainer = document.getElementById('stat-tech-stacks');
            if (stacks.size > 0) {
                techStacksContainer.innerHTML = Array.from(stacks).slice(0, 3).map(stack => `
                    <span class="ds-badge-electric">${escapeHtml(stack)}</span>
                `).join('');
            } else {
                techStacksContainer.innerHTML = `<span class="ds-badge-brand">General</span>`;
            }

            // 4. Roadmap Progress
            let completedItems = 0;
            let totalChecked = 0;
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('roadmap-')) {
                    try {
                        const val = JSON.parse(localStorage.getItem(key));
                        if (typeof val === 'object' && val !== null) {
                            Object.values(val).forEach(isComp => {
                                totalChecked++;
                                if (isComp) completedItems++;
                            });
                        }
                    } catch (e) {}
                }
            }
            const percent = totalChecked > 0 ? Math.round((completedItems / totalChecked) * 100) : 0;
            document.getElementById('stat-roadmap-progress').textContent = `${percent}%`;
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
                <ds-card variant="glass" interactive padding="lg" style="cursor: pointer;" onclick="viewVideo(${video.id})">
                    <div class="video-card-content">
                        <div class="video-card-top">
                            <div class="video-card-title-row">
                                <h3 class="video-card-title">${escapeHtml(video.title)}</h3>
                                <span class="ds-badge-electric shrink-0" style="white-space: nowrap;">
                                    ${formatDuration(video.duration)}
                                </span>
                            </div>
        
                            ${video.summary ? `
                                <p class="video-card-desc">${escapeHtml(video.summary)}</p>
                            ` : ''}
                        </div>
    
                        <div class="video-card-footer">
                            <span style="display: flex; align-items: center; gap: 4px;">
                                <i class="ph ph-calendar"></i> ${new Date(video.extracted_at || video.created_at).toLocaleDateString()}
                            </span>
                            <span class="footer-action">
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
