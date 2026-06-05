<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details - YouTube Extractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <!-- Highlight.js for Code Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

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
            background: rgba(10, 16, 34, 0.3);
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

        .header-actions {
            display: flex;
            gap: var(--theme-spacing-3);
        }

        /* Main Section */
        main {
            flex: 1;
            padding: var(--theme-spacing-12) 0;
        }

        .space-y-8 > * + * {
            margin-top: var(--theme-spacing-8);
        }

        .space-y-4 > * + * {
            margin-top: var(--theme-spacing-4);
        }

        /* Result details card */
        .result-info-header {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
        }

        @media (min-width: 768px) {
            .result-info-header {
                flex-direction: row;
                align-items: flex-start;
                justify-content: space-between;
            }
        }

        .result-meta {
            display: flex;
            flex-wrap: wrap;
            gap: var(--theme-spacing-2);
            margin-top: var(--theme-spacing-3);
            margin-bottom: var(--theme-spacing-4);
        }

        /* Code view cards */
        .code-snippet-card {
            border: 1px solid rgba(6, 182, 212, 0.1);
            border-radius: var(--theme-radius-2xl);
            background: var(--ds-surface-card);
            box-shadow: var(--theme-shadow-lg);
            overflow: hidden;
            margin-bottom: var(--theme-spacing-5);
        }

        .code-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--theme-spacing-3) var(--theme-spacing-5);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.15);
        }

        .code-meta-left {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
        }

        .code-path {
            font-family: var(--theme-font-mono);
            font-size: var(--theme-font-size-sm);
            color: var(--theme-neutral-0);
        }

        .code-pre {
            margin: 0;
            padding: var(--theme-spacing-5);
            overflow-x: auto;
            font-size: var(--theme-font-size-sm);
            background: transparent;
        }

        .code-description-footer {
            padding: var(--theme-spacing-3) var(--theme-spacing-5);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            background: rgba(0, 0, 0, 0.1);
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
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

        /* Footer */
        footer {
            background: rgba(6, 11, 24, 0.8);
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
                <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                <span class="ds-type-heading-sm" style="margin: 0;">YouExtractor</span>
            </a>
            <div class="header-actions">
                <a href="{{ route('dashboard') }}" style="text-decoration: none;">
                    <ds-button label="Back to Dashboard" variant="primary" size="sm" icon="arrow-left"></ds-button>
                </a>
                <a href="/videos" style="text-decoration: none;">
                    <ds-button label="All Videos" variant="secondary" size="sm" icon="books"></ds-button>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <div id="videoContent" class="space-y-8">
            <div class="loading-state">
                <i class="ph ph-spinner spin-icon"></i>
                <p>Loading video details...</p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container" style="text-align: center; color: var(--ds-text-muted); font-size: var(--theme-font-size-sm);">
            <p>YouTube Video Extractor &bull; Powered by AI & Laravel</p>
        </div>
    </footer>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>

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
                    <div style="text-align: center; color: var(--ds-color-error); padding: var(--theme-spacing-12) 0; max-width: 440px; margin: 0 auto; display: flex; flex-direction: column; gap: var(--theme-spacing-6);">
                        <i class="ph ph-warning-circle text-4xl" style="font-size: 2.5rem;"></i>
                        <p>Error: ${error.message}</p>
                        <div>
                            <a href="/videos" style="text-decoration: none;">
                                <ds-button label="Back to Videos" variant="primary" icon="arrow-left"></ds-button>
                            </a>
                        </div>
                    </div>
                `;
            }
        }

        function displayVideo(video) {
            const duration = formatDuration(video.duration);
            const publishDate = new Date(video.published_at || video.extracted_at).toLocaleDateString();

            let codeSnippetsHtml = '';
            if (video.code_snippets && video.code_snippets.length > 0) {
                codeSnippetsHtml = `
                    <div class="space-y-4">
                        <h4 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-electric); margin: 0;">
                            <i class="ph ph-file-code"></i> Code Snippets
                        </h4>
                        <div class="space-y-4">
                            ${video.code_snippets.map((snippet, idx) => {
                                const isObj = typeof snippet === 'object' && snippet !== null;
                                const filename = isObj ? (snippet.path || snippet.filename || `file_${idx + 1}`) : `snippet_${idx + 1}`;
                                const language = isObj ? (snippet.language || 'code') : 'code';
                                const code = isObj ? (snippet.code || '') : (typeof snippet === 'string' ? snippet : '');
                                const desc = isObj ? (snippet.description || '') : '';
                                
                                return `
                                    <div class="code-snippet-card">
                                        <div class="code-card-header">
                                            <div class="code-meta-left">
                                                <i class="ph ph-code" style="color: var(--theme-yellow-500); font-size: 1.25rem;"></i>
                                                <span class="code-path">${escapeHtml(filename)}</span>
                                                <span class="ds-badge-electric">${escapeHtml(language)}</span>
                                            </div>
                                            <ds-button onclick="copyCode(${idx})" label="Copy Code" variant="ghost" size="sm" icon="copy"></ds-button>
                                        </div>
                                        <pre class="code-pre"><code id="code-${idx}" class="language-${escapeHtml(language)}">${escapeHtml(code)}</code></pre>
                                        ${desc ? `
                                            <div class="code-description-footer">
                                                <i class="ph ph-info" style="margin-right: 4px;"></i> ${escapeHtml(desc)}
                                            </div>
                                        ` : ''}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }

            videoContent.innerHTML = `
                <div class="space-y-8" style="animation: fadeIn 0.4s var(--theme-ease-out);">
                    <a href="/videos" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; color: var(--ds-text-secondary); font-size: var(--theme-font-size-sm); margin-bottom: var(--theme-spacing-1); transition: color 0.2s;" onmouseover="this.style.color='var(--ds-text-brand)'" onmouseout="this.style.color='var(--ds-text-secondary)'">
                        <i class="ph ph-arrow-left"></i> Back to Videos
                    </a>
                    <!-- Video Info Card -->
                    <ds-card variant="glass-accent" padding="lg">
                        <div class="result-info-header">
                            <div style="flex: 1;">
                                <h2 class="ds-type-heading-md text-white" style="margin: 0;">${escapeHtml(video.title)}</h2>
                                <div class="result-meta">
                                    <span class="ds-badge-brand" style="display: inline-flex; align-items: center; gap: 6px;"><i class="ph ph-clock"></i> Duration: ${duration}</span>
                                    <span class="ds-badge-electric" style="display: inline-flex; align-items: center; gap: 6px;"><i class="ph ph-calendar"></i> Published: ${publishDate}</span>
                                </div>
                            </div>
                            <div>
                                <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" style="text-decoration: none;">
                                    <ds-button label="Watch on YouTube" variant="secondary" size="md" icon="youtube-logo"></ds-button>
                                </a>
                            </div>
                        </div>
                    </ds-card>

                    <!-- Summary Alert/Callout -->
                    ${video.summary ? `
                        <ds-card variant="glass" padding="md">
                            <div class="space-y-2">
                                <h4 class="font-semibold flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                                    <i class="ph ph-sparkle" style="font-size: 1.25rem;"></i> AI Summary
                                </h4>
                                <p class="ds-type-body-sm text-gray-300" style="line-height: var(--theme-line-height-relaxed); margin: 0;">${escapeHtml(video.summary)}</p>
                            </div>
                        </ds-card>
                    ` : ''}

                    <!-- AI Explanation -->
                    <div class="space-y-4">
                        <h4 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                            <i class="ph ph-robot"></i> AI Explanation & Concepts
                        </h4>
                        <ds-card variant="glass" padding="lg">
                            <div class="ds-type-body-md text-gray-200" style="white-space: pre-wrap; line-height: var(--theme-line-height-relaxed);">
                                ${escapeHtml(video.explanation)}
                            </div>
                        </ds-card>
                    </div>

                    <!-- Code Snippets -->
                    ${codeSnippetsHtml}

                    <!-- Description -->
                    ${video.description ? `
                        <div class="space-y-4">
                            <h4 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-muted); margin: 0;">
                                <i class="ph ph-info"></i> Video Description
                            </h4>
                            <ds-card variant="default" padding="md">
                                <p class="ds-type-body-sm text-gray-400" style="white-space: pre-wrap; margin: 0;">${escapeHtml(video.description)}</p>
                            </ds-card>
                        </div>
                    ` : ''}

                    <!-- Transcript -->
                    ${video.transcript && !video.transcript.includes('not available') ? `
                        <div class="space-y-4">
                            <h4 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-muted); margin: 0;">
                                <i class="ph ph-file-text"></i> Video Transcript
                            </h4>
                            <ds-card variant="default" padding="none">
                                <div class="ds-type-body-sm text-gray-400" style="padding: var(--theme-spacing-5); max-height: 384px; overflow-y: auto; white-space: pre-wrap; line-height: var(--theme-line-height-relaxed); background: rgba(0,0,0,0.1);">
                                    ${escapeHtml(video.transcript)}
                                </div>
                            </ds-card>
                        </div>
                    ` : ''}
                </div>
            `;

            // Apply syntax highlighting
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });
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

        function copyCode(idx) {
            const codeEl = document.getElementById(`code-${idx}`);
            copyToClipboard(codeEl.textContent);
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2.5 rounded-xl shadow-2xl z-50 flex items-center gap-2 border border-green-500';
                toast.style.animation = 'fadeIn 0.4s var(--theme-ease-out)';
                toast.style.position = 'fixed';
                toast.style.bottom = '24px';
                toast.style.right = '24px';
                toast.innerHTML = '<i class="ph ph-check-circle text-lg"></i> Copied to clipboard!';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.4s ease';
                    setTimeout(() => toast.remove(), 400);
                }, 2000);
            });
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
