<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Details - YouTube Extractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Tailwind CSS (paired with Design System) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                <div class="flex gap-3">
                    <a href="/">
                        <ds-button label="Extract New" variant="primary" size="sm" icon="plus"></ds-button>
                    </a>
                    <a href="/videos">
                        <ds-button label="All Videos" variant="secondary" size="sm" icon="books"></ds-button>
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-5xl mx-auto w-full px-6 py-12">
            <div id="videoContent" class="space-y-8">
                <div class="text-center text-gray-400 py-12">
                    <div class="animate-spin text-3xl mb-4"><i class="ph ph-spinner"></i></div>
                    <p>Loading video details...</p>
                </div>
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
                    <div class="text-center text-red-400 py-12 max-w-md mx-auto space-y-6">
                        <i class="ph ph-warning-circle text-4xl mb-2"></i>
                        <p>Error: ${error.message}</p>
                        <a href="/videos" class="inline-block">
                            <ds-button label="Back to Videos" variant="primary" icon="arrow-left"></ds-button>
                        </a>
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
                        <h4 class="ds-type-heading-sm text-cyan-450 flex items-center gap-2">
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
                                    <ds-card variant="glow-electric" padding="none" class="shadow-lg border border-cyan-500/10 overflow-hidden">
                                        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-800/80" style="background: rgba(0,0,0,0.15)">
                                            <div class="flex items-center gap-2.5">
                                                <i class="ph ph-code text-yellow-500 text-base"></i>
                                                <span class="font-mono text-sm text-white">${escapeHtml(filename)}</span>
                                                <span class="ds-badge-electric">${escapeHtml(language)}</span>
                                            </div>
                                            <ds-button onclick="copyCode(${idx})" label="Copy Code" variant="ghost" size="sm" icon="copy"></ds-button>
                                        </div>
                                        <pre class="p-5 overflow-x-auto text-sm max-h-96" style="background: transparent;"><code id="code-${idx}" class="language-${escapeHtml(language)}">${escapeHtml(code}</code></pre>
                                        ${desc ? `
                                            <div class="px-5 py-3 border-t border-gray-800 text-xs text-gray-400" style="background: rgba(0,0,0,0.1)">
                                                <i class="ph ph-info mr-1"></i> ${escapeHtml(desc)}
                                            </div>
                                        ` : ''}
                                    </ds-card>
                                `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }

            videoContent.innerHTML = `
                <div class="space-y-8 animate-fadeIn">
                    <!-- Video Info Card -->
                    <ds-card variant="glass-accent" padding="lg" class="shadow-lg">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div class="space-y-4">
                                <h2 class="ds-type-heading-md text-white">${escapeHtml(video.title)}</h2>
                                <div class="flex flex-wrap gap-3.5 text-sm font-medium pt-1">
                                    <span class="ds-badge-brand flex items-center gap-1.5"><i class="ph ph-clock"></i> Duration: ${duration}</span>
                                    <span class="ds-badge-electric flex items-center gap-1.5"><i class="ph ph-calendar"></i> Published: ${publishDate}</span>
                                </div>
                            </div>
                            <div>
                                <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank">
                                    <ds-button label="Watch on YouTube" variant="secondary" size="md" icon="youtube-logo"></ds-button>
                                </a>
                            </div>
                        </div>
                    </ds-card>

                    <!-- Summary Alert/Callout -->
                    ${video.summary ? `
                        <ds-card variant="glass" padding="md" class="border border-purple-500/10">
                            <div class="space-y-2">
                                <h4 class="font-semibold text-purple-300 flex items-center gap-1.5">
                                    <i class="ph ph-sparkle text-lg"></i> AI Summary
                                </h4>
                                <p class="ds-type-body-sm text-gray-300 leading-relaxed">${escapeHtml(video.summary)}</p>
                            </div>
                        </ds-card>
                    ` : ''}

                    <!-- AI Explanation -->
                    <div class="space-y-4">
                        <h4 class="ds-type-heading-sm text-purple-400 flex items-center gap-2">
                            <i class="ph ph-robot"></i> AI Explanation & Concepts
                        </h4>
                        <ds-card variant="glass" padding="lg" class="border border-gray-700/40">
                            <div class="ds-type-body-md text-gray-200 whitespace-pre-wrap leading-relaxed">
                                ${escapeHtml(video.explanation)}
                            </div>
                        </ds-card>
                    </div>

                    <!-- Code Snippets -->
                    ${codeSnippetsHtml}

                    <!-- Description -->
                    ${video.description ? `
                        <div class="space-y-4">
                            <h4 class="ds-type-heading-sm text-gray-400 flex items-center gap-2">
                                <i class="ph ph-info"></i> Video Description
                            </h4>
                            <ds-card variant="default" padding="md" class="border border-gray-800">
                                <p class="ds-type-body-sm text-gray-400 whitespace-pre-wrap">${escapeHtml(video.description)}</p>
                            </ds-card>
                        </div>
                    ` : ''}

                    <!-- Transcript -->
                    ${video.transcript && !video.transcript.includes('not available') ? `
                        <div class="space-y-4">
                            <h4 class="ds-type-heading-sm text-gray-400 flex items-center gap-2">
                                <i class="ph ph-file-text"></i> Video Transcript
                            </h4>
                            <ds-card variant="default" padding="none" class="border border-gray-800 overflow-hidden">
                                <div class="p-5 ds-type-body-sm text-gray-400 max-h-96 overflow-y-auto whitespace-pre-wrap leading-relaxed bg-black/10">
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
                toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2.5 rounded-xl shadow-2xl z-50 flex items-center gap-2 border border-green-500 animate-fadeIn';
                toast.innerHTML = '<i class="ph ph-check-circle text-lg"></i> Copied to clipboard!';
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.add('opacity-0');
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
