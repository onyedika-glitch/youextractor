<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Code Extractor - Learn Programming Faster</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Tailwind CSS (paired with Design System) -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/style.css">

    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github-dark.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        body { 
            font-family: var(--theme-font-sans); 
            background: var(--ds-surface-base);
            color: var(--ds-text-primary);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn { animation: fadeIn 0.4s var(--theme-ease-out); }
        .tab-active { 
            border-bottom: 2px solid var(--ds-color-brand); 
            color: var(--ds-text-brand) !important; 
        }
        .tab-btn {
            color: var(--ds-text-secondary);
            font-family: var(--theme-font-sans);
            font-weight: var(--theme-font-weight-semibold);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
        }
        .tab-btn:hover {
            color: var(--ds-text-primary);
            background: rgba(255, 255, 255, 0.02);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-black/30 backdrop-blur-md border-b border-purple-500/10 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 hover:opacity-90 transition">
                    <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                    <span class="text-xl font-bold tracking-tight text-white">YouExtractor</span>
                </a>
                
                <div class="flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3 bg-gray-800/40 px-3 py-1.5 rounded-full border border-gray-700/30">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="w-6 h-6 rounded-full border border-purple-500/30" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="w-6 h-6 rounded-full bg-purple-600 flex items-center justify-center font-bold text-white text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="text-sm font-medium text-gray-300">{{ Auth::user()->name }}</span>
                        </div>
                        
                        <a href="/videos">
                            <ds-button label="My Library" variant="ghost" size="sm" icon="books"></ds-button>
                        </a>
                        
                        <form action="/logout" method="POST" class="inline" id="logoutForm">
                            @csrf
                            <ds-button type="submit" label="Sign Out" variant="secondary" size="sm" icon="sign-out"></ds-button>
                        </form>
                    @else
                        <div class="flex items-center gap-3">
                            <a href="/signin">
                                <ds-button label="Sign In" variant="ghost" size="sm"></ds-button>
                            </a>
                            <a href="/signup">
                                <ds-button label="Get Started" variant="primary" size="sm"></ds-button>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 space-y-12">
            <!-- Hero Title -->
            <div class="text-center space-y-4 max-w-3xl mx-auto">
                <h1 class="ds-type-display-sm text-white">Learn Faster, Code Smarter</h1>
                <p class="ds-type-body-lg text-gray-400">
                    Paste any programming tutorial URL and get all the code snippets, 
                    <span style="color: var(--ds-text-brand)">complete setup guides</span>, 
                    <span style="color: var(--ds-text-accent)">IDE recommendations</span>, and 
                    <span style="color: var(--ds-text-electric)">step-by-step instructions</span>.
                </p>
            </div>

            <!-- Input Section -->
            <ds-card variant="glass-accent" padding="lg" class="shadow-2xl">
                <form id="videoForm" class="space-y-6">
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <ds-input 
                                type="text" 
                                id="youtubeUrl" 
                                label="YouTube Video URL"
                                placeholder="https://www.youtube.com/watch?v=..."
                                icon="link"
                                size="lg"
                                required>
                            </ds-input>
                        </div>
                        <div class="w-full md:w-auto">
                            <ds-button 
                                type="submit" 
                                id="submitBtn"
                                label="Extract & Learn"
                                variant="gradient"
                                size="lg"
                                icon="rocket-launch"
                                class="w-full">
                            </ds-button>
                        </div>
                    </div>
                </form>

                <!-- Error Message banner -->
                <div id="error" class="mt-6 hidden">
                    <span class="ds-badge-error w-full justify-center py-3">
                        <i class="ph ph-warning-circle mr-2 text-base"></i>
                        <span id="errorText"></span>
                    </span>
                </div>

                <!-- Loading State -->
                <div id="loading" class="mt-8 hidden">
                    <div class="flex flex-col items-center gap-4 py-8">
                        <div class="relative">
                            <div class="w-14 h-14 border-4 border-purple-500/20 rounded-full"></div>
                            <div class="w-14 h-14 border-4 border-purple-500 border-t-transparent rounded-full animate-spin absolute top-0"></div>
                        </div>
                        <div class="text-center space-y-1.5">
                            <p class="ds-type-heading-sm text-purple-300" id="loadingText">Extracting video information...</p>
                            <p class="ds-type-body-sm text-gray-500">Generating tutorial guide, IDE recommendations, and code files...</p>
                        </div>
                    </div>
                </div>
            </ds-card>

            <!-- Results Section -->
            <div id="results" class="space-y-6"></div>

            <!-- Features Grid (shown when empty/default state) -->
            <div id="features" class="grid md:grid-cols-4 gap-6">
                <ds-card variant="glass" padding="md">
                    <div class="w-10 h-10 bg-purple-500/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph ph-book-open" style="color: var(--ds-text-brand); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-2">Tutorial Guide</h3>
                    <p class="ds-type-body-sm text-gray-400">Get a complete explanation of what the video teaches and key concepts.</p>
                </ds-card>
                
                <ds-card variant="glass" padding="md">
                    <div class="w-10 h-10 bg-pink-500/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph ph-laptop" style="color: var(--ds-text-accent); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-2">IDE Recommendations</h3>
                    <p class="ds-type-body-sm text-gray-400">Best IDE for the tech stack with extensions and download links.</p>
                </ds-card>

                <ds-card variant="glass" padding="md">
                    <div class="w-10 h-10 bg-cyan-500/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph ph-wrench" style="color: var(--ds-text-electric); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-2">Setup & Run Guide</h3>
                    <p class="ds-type-body-sm text-gray-400">Step-by-step instructions to set up and run the code.</p>
                </ds-card>

                <ds-card variant="glass" padding="md">
                    <div class="w-10 h-10 bg-green-500/10 rounded-lg flex items-center justify-center mb-4">
                        <i class="ph ph-download-simple" style="color: var(--ds-text-success-subtle); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm text-white mb-2">Download Ready</h3>
                    <p class="ds-type-body-sm text-gray-400">Get a ZIP file with all code, organized by project structure.</p>
                </ds-card>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-black/30 border-t border-purple-500/10 py-8 mt-12">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-500 text-sm">
                <p>YouTube Code Extractor &bull; Built for developers who learn by watching</p>
            </div>
        </footer>
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js"></script>

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

        // Proxy logout submission
        const logoutForm = document.getElementById('logoutForm');
        if (logoutForm) {
            logoutForm.querySelector('ds-button').addEventListener('click', (e) => {
                e.preventDefault();
                logoutForm.submit();
            });
        }

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

        // Check for URL query parameter (for Chrome Extension)
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const url = urlParams.get('url');
            if (url) {
                youtubeUrl.value = url;
                // Auto-trigger extraction if valid URL
                if (url.includes('youtube.com/') || url.includes('youtu.be/')) {
                    submitBtn.click();
                }
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            errorDiv.classList.add('hidden');
            resultsContainer.innerHTML = '';
            featuresSection.classList.add('hidden');
            loadingDiv.classList.remove('hidden');
            
            submitBtn.loading = true;
            youtubeUrl.disabled = true;
            youtubeUrl.removeAttribute('error');

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
                submitBtn.loading = false;
                youtubeUrl.disabled = false;
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
                    stackBadges += `<span class="ds-badge-brand mr-2">${stack.primary}</span>`;
                }
                if (stack.frameworks && stack.frameworks.length > 0) {
                    stack.frameworks.forEach(fw => {
                        stackBadges += `<span class="ds-badge-electric mr-2">${fw}</span>`;
                    });
                }
            }

            const html = `
                <div class="space-y-6 animate-fadeIn">
                    <!-- Video Info Card -->
                    <ds-card variant="glass-accent" padding="lg" class="shadow-lg">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                            <div class="flex-1 space-y-4">
                                <h3 class="ds-type-heading-md text-white break-words">${escapeHtml(video.title)}</h3>
                                <div class="flex flex-wrap gap-2">
                                    ${stackBadges}
                                    ${hasCode ? `<span class="ds-badge-success">${video.code_snippets.length} files</span>` : ''}
                                </div>
                                <div class="flex flex-wrap gap-4 text-sm font-medium pt-2">
                                    <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank">
                                        <ds-button label="Watch on YouTube" variant="ghost" size="sm" icon="youtube-logo"></ds-button>
                                    </a>
                                    ${hasCode ? `
                                        <a href="/api/videos/${video.id}/download">
                                            <ds-button label="Download All Code" variant="ghost" size="sm" icon="download-simple"></ds-button>
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                            ${hasCode ? `
                            <div class="w-full md:w-auto">
                                <a href="/api/videos/${video.id}/download" class="block w-full">
                                    <ds-button label="Download ZIP" variant="glow" size="lg" icon="file-zip" class="w-full"></ds-button>
                                </a>
                            </div>` : ''}
                        </div>
                    </ds-card>

                    <!-- Tabs Container -->
                    <div class="ds-surface-glass border border-gray-700/50 overflow-hidden shadow-xl rounded-2xl">
                        <!-- Tab Bar -->
                        <div class="flex border-b border-gray-700/50 overflow-x-auto bg-black/10">
                            <button onclick="showTab('overview', '${video.id}')" id="tab-overview" class="px-6 py-4 tab-btn tab-active whitespace-nowrap text-sm">
                                <i class="ph ph-book-open mr-1.5"></i> Tutorial
                            </button>
                            <button onclick="showTab('ide', '${video.id}')" id="tab-ide" class="px-6 py-4 tab-btn whitespace-nowrap text-sm">
                                <i class="ph ph-laptop mr-1.5"></i> IDE
                            </button>
                            <button onclick="showTab('setup', '${video.id}')" id="tab-setup" class="px-6 py-4 tab-btn whitespace-nowrap text-sm">
                                <i class="ph ph-wrench mr-1.5"></i> Setup
                            </button>
                            <button onclick="showTab('run', '${video.id}')" id="tab-run" class="px-6 py-4 tab-btn whitespace-nowrap text-sm">
                                <i class="ph ph-play mr-1.5"></i> Run
                            </button>
                            <button onclick="showTab('code', '${video.id}')" id="tab-code" class="px-6 py-4 tab-btn whitespace-nowrap text-sm">
                                <i class="ph ph-folder-open mr-1.5"></i> Code (${video.code_snippets?.length || 0})
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div class="p-6">
                            <!-- Overview Tab -->
                            <div id="content-overview" class="tab-content space-y-6">
                                ${renderTutorialGuide(tutorialGuide)}
                            </div>

                            <!-- IDE Tab -->
                            <div id="content-ide" class="tab-content hidden space-y-6">
                                ${renderIDERecommendations(ideRec, prerequisites)}
                            </div>

                            <!-- Setup Tab -->
                            <div id="content-setup" class="tab-content hidden space-y-6">
                                ${renderSetupGuide(setupGuide, prerequisites)}
                            </div>

                            <!-- Run Tab -->
                            <div id="content-run" class="tab-content hidden space-y-6">
                                ${renderRunGuide(runGuide)}
                            </div>

                            <!-- Code Tab -->
                            <div id="content-code" class="tab-content hidden space-y-6">
                                ${renderCodeFiles(video.code_snippets, video.id)}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            resultsContainer.innerHTML = html;
            
            // Proxy nested downloads inside resultsContainer
            resultsContainer.querySelectorAll('ds-button').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const link = btn.closest('a');
                    if (link) {
                        e.preventDefault();
                        window.open(link.href, link.target || '_self');
                    }
                });
            });

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
                    <div class="space-y-3">
                        <h3 class="ds-type-heading-sm text-purple-400 flex items-center gap-2">
                            <i class="ph ph-book-open"></i> Overview
                        </h3>
                        <p class="ds-type-body-md text-gray-300 leading-relaxed whitespace-pre-wrap">${escapeHtml(guide.overview)}</p>
                    </div>
                `;
            }

            if (guide.key_concepts && guide.key_concepts.length > 0) {
                html += `
                    <div class="space-y-4 pt-4">
                        <h3 class="ds-type-heading-sm text-purple-400 flex items-center gap-2">
                            <i class="ph ph-brain"></i> Key Concepts
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            ${guide.key_concepts.map(concept => `
                                <ds-card variant="glass" padding="sm" class="border border-gray-700/40">
                                    <h4 class="font-bold text-white mb-1.5">${escapeHtml(concept.concept)}</h4>
                                    <p class="ds-type-body-sm text-gray-450">${escapeHtml(concept.explanation)}</p>
                                </ds-card>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (guide.learning_outcomes && guide.learning_outcomes.length > 0) {
                html += `
                    <div class="space-y-3 pt-4">
                        <h3 class="ds-type-heading-sm text-purple-400 flex items-center gap-2">
                            <i class="ph ph-target"></i> What You'll Learn
                        </h3>
                        <ul class="grid md:grid-cols-2 gap-2.5">
                            ${guide.learning_outcomes.map(outcome => `
                                <li class="flex items-start gap-2 text-gray-300 text-sm">
                                    <i class="ph ph-check-circle text-green-400 text-base mt-0.5"></i>
                                    <span>${escapeHtml(outcome)}</span>
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
                    <div class="space-y-4">
                        <h3 class="ds-type-heading-sm text-pink-400 flex items-center gap-2">
                            <i class="ph ph-star"></i> Recommended IDE
                        </h3>
                        <ds-card variant="glow" padding="lg" class="shadow-lg">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                <div class="space-y-2">
                                    <h4 class="ds-type-heading-md text-white">${escapeHtml(ide.primary.name)}</h4>
                                    <p class="ds-type-body-sm text-gray-350">${escapeHtml(ide.primary.reason)}</p>
                                </div>
                                <div class="w-full sm:w-auto">
                                    <a href="${escapeHtml(ide.primary.download_url)}" target="_blank" class="block w-full">
                                        <ds-button label="Download" variant="primary" size="sm" icon="download-simple" class="w-full"></ds-button>
                                    </a>
                                </div>
                            </div>
                            ${ide.primary.extensions && ide.primary.extensions.length > 0 ? `
                                <div class="mt-5 border-t border-purple-500/20 pt-4">
                                    <p class="ds-type-label-md text-pink-300 mb-2">Recommended Extensions:</p>
                                    <div class="flex flex-wrap gap-2">
                                        ${ide.primary.extensions.map(ext => `
                                            <span class="ds-badge-brand">${escapeHtml(ext)}</span>
                                        `).join('')}
                                    </div>
                                </div>
                            ` : ''}
                        </ds-card>
                    </div>
                `;
            }

            if (ide.alternatives && ide.alternatives.length > 0) {
                html += `
                    <div class="space-y-4 pt-4">
                        <h3 class="ds-type-heading-sm text-pink-400 flex items-center gap-2">
                            <i class="ph ph-shuffle"></i> Alternatives
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            ${ide.alternatives.map(alt => `
                                <ds-card variant="glass" padding="md" class="border border-gray-700/40">
                                    <div class="flex items-start justify-between gap-4">
                                        <h4 class="font-bold text-white">${escapeHtml(alt.name)}</h4>
                                        <a href="${escapeHtml(alt.download_url)}" target="_blank" class="text-pink-400 hover:text-pink-350 text-xs font-semibold flex items-center gap-0.5">
                                            Download <i class="ph ph-arrow-square-out"></i>
                                        </a>
                                    </div>
                                    <p class="ds-type-body-sm text-gray-400 mt-2">${escapeHtml(alt.reason)}</p>
                                    ${alt.extensions && alt.extensions.length > 0 ? `
                                        <div class="mt-3 flex flex-wrap gap-1.5 pt-2 border-t border-gray-700/30">
                                            ${alt.extensions.map(ext => `
                                                <span class="ds-badge-accent">${escapeHtml(ext)}</span>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </ds-card>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (prerequisites && prerequisites.knowledge && prerequisites.knowledge.length > 0) {
                html += `
                    <div class="space-y-3 pt-4">
                        <h3 class="ds-type-heading-sm text-pink-400 flex items-center gap-2">
                            <i class="ph ph-graduation-cap"></i> Required Knowledge
                        </h3>
                        <ul class="grid md:grid-cols-2 gap-2">
                            ${prerequisites.knowledge.map(k => `
                                <li class="flex items-center gap-2 text-gray-300 text-sm">
                                    <i class="ph ph-book-bookmark text-pink-400 text-base"></i>
                                    <span>${escapeHtml(k)}</span>
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
                    <div class="space-y-4">
                        <h3 class="ds-type-heading-sm text-cyan-400 flex items-center gap-2">
                            <i class="ph ph-cube"></i> Prerequisites
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            ${prerequisites.software.map(sw => `
                                <ds-card variant="glass" padding="md" class="border border-gray-700/40">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h4 class="font-bold text-white">${escapeHtml(sw.name)}</h4>
                                            <p class="ds-type-body-sm text-gray-400 mt-1">${escapeHtml(sw.purpose)}</p>
                                        </div>
                                        <a href="${escapeHtml(sw.download_url)}" target="_blank">
                                            <ds-button label="Get" variant="secondary" size="sm" icon="arrow-square-out"></ds-button>
                                        </a>
                                    </div>
                                </ds-card>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (setupGuide && setupGuide.steps && setupGuide.steps.length > 0) {
                html += `
                    <div class="space-y-4 pt-4">
                        <h3 class="ds-type-heading-sm text-cyan-400 flex items-center gap-2">
                            <i class="ph ph-steps"></i> Setup Steps
                        </h3>
                        <div class="space-y-4">
                            ${setupGuide.steps.map(step => `
                                <ds-card variant="glass" padding="md" class="border border-gray-700/40">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="w-7 h-7 bg-cyan-600 rounded-full flex items-center justify-center font-bold text-white text-sm">${step.step}</span>
                                        <h4 class="font-bold text-white">${escapeHtml(step.title)}</h4>
                                    </div>
                                    <p class="ds-type-body-sm text-gray-300 mb-3">${escapeHtml(step.explanation)}</p>
                                    ${step.commands && step.commands.length > 0 ? `
                                        <div class="ds-surface-inset p-3.5 space-y-2">
                                            ${step.commands.map(cmd => `
                                                <div class="flex items-center justify-between group py-1">
                                                    <code class="text-green-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                                    <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy" class="opacity-0 group-hover:opacity-100 transition"></ds-button>
                                                </div>
                                            `).join('')}
                                        </div>
                                    ` : ''}
                                </ds-card>
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
                    <div class="space-y-3">
                        <h3 class="ds-type-heading-sm text-green-400 flex items-center gap-2">
                            <i class="ph ph-terminal"></i> Development Mode
                        </h3>
                        <ds-card variant="glow-electric" padding="md" class="border border-green-500/20">
                            <p class="ds-type-body-sm text-gray-300 mb-3">${escapeHtml(runGuide.development.explanation)}</p>
                            <div class="ds-surface-inset p-3 mb-3">
                                ${runGuide.development.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-green-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy" class="opacity-0 group-hover:opacity-100 transition"></ds-button>
                                    </div>
                                `).join('')}
                            </div>
                            ${runGuide.development.access_url ? `
                                <p class="text-xs text-gray-400">Access local server at: <a href="${escapeHtml(runGuide.development.access_url)}" target="_blank" class="text-purple-400 hover:text-purple-300 underline">${escapeHtml(runGuide.development.access_url)}</a></p>
                            ` : ''}
                        </ds-card>
                    </div>
                `;
            }

            if (runGuide.production) {
                html += `
                    <div class="space-y-3 pt-4">
                        <h3 class="ds-type-heading-sm text-purple-450 flex items-center gap-2">
                            <i class="ph ph-rocket"></i> Production Build
                        </h3>
                        <ds-card variant="glass-accent" padding="md" class="border border-purple-500/20">
                            <p class="ds-type-body-sm text-gray-300 mb-3">${escapeHtml(runGuide.production.explanation)}</p>
                            <div class="ds-surface-inset p-3">
                                ${runGuide.production.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-purple-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy" class="opacity-0 group-hover:opacity-100 transition"></ds-button>
                                    </div>
                                `).join('')}
                            </div>
                        </ds-card>
                    </div>
                `;
            }

            if (runGuide.docker) {
                html += `
                    <div class="space-y-3 pt-4">
                        <h3 class="ds-type-heading-sm text-cyan-450 flex items-center gap-2">
                            <i class="ph ph-package"></i> Docker Container
                        </h3>
                        <ds-card variant="glass" padding="md" class="border border-cyan-500/20">
                            <p class="ds-type-body-sm text-gray-300 mb-3">${escapeHtml(runGuide.docker.explanation)}</p>
                            <div class="ds-surface-inset p-3">
                                ${runGuide.docker.commands.map(cmd => `
                                    <div class="flex items-center justify-between group py-1">
                                        <code class="text-cyan-450 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy" class="opacity-0 group-hover:opacity-100 transition"></ds-button>
                                    </div>
                                `).join('')}
                            </div>
                        </ds-card>
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
                <div class="space-y-5">
                    ${files.map((file, idx) => `
                        <ds-card variant="glow-electric" padding="none" class="shadow-lg border border-cyan-500/10 overflow-hidden">
                            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-800" style="background: rgba(0,0,0,0.15)">
                                <div class="flex items-center gap-2.5">
                                    <i class="ph ph-file-code text-yellow-500 text-lg"></i>
                                    <span class="font-mono text-sm text-white">${escapeHtml(file.path || file.filename)}</span>
                                    <span class="ds-badge-electric">${escapeHtml(file.language)}</span>
                                </div>
                                <ds-button onclick="copyCode(${idx})" label="Copy Code" variant="ghost" size="sm" icon="copy"></ds-button>
                            </div>
                            <pre class="p-5 overflow-x-auto text-sm max-h-96" style="background: transparent;"><code id="code-${idx}" class="language-${escapeHtml(file.language)}">${escapeHtml(file.code)}</code></pre>
                            ${file.description ? `
                                <div class="px-5 py-3 border-t border-gray-800 text-xs text-gray-400" style="background: rgba(0,0,0,0.1)">
                                    <i class="ph ph-info mr-1"></i> ${escapeHtml(file.description)}
                                </div>
                            ` : ''}
                        </ds-card>
                    `).join('')}
                </div>
            `;
        }

        function showTab(tabName, videoId) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('tab-active'));
            
            document.getElementById(`content-${tabName}`).classList.remove('hidden');
            document.getElementById(`tab-${tabName}`).classList.add('tab-active');
            
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

        function showError(message) {
            youtubeUrl.error = message;
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
       class="fixed bottom-6 right-6 z-50 flex items-center gap-2 px-4 py-3 bg-[#FFDD00] hover:bg-[#ffea47] text-gray-900 rounded-full shadow-lg font-semibold transition-all hover:scale-105">
        ☕ Buy me a coffee
    </a>
</body>
</html>
