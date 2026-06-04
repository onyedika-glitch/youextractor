<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Code Extractor - Learn Programming Faster</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <!-- Highlight.js for Syntax Highlighting -->
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
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-content {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
            padding: var(--theme-spacing-4) 0;
        }

        @media (min-width: 640px) {
            .header-content {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                height: 80px;
                padding: 0;
            }
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

        .user-actions {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-4);
            flex-wrap: wrap;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            background: rgba(30, 41, 59, 0.4);
            padding: var(--theme-spacing-2) var(--theme-spacing-4);
            border-radius: var(--theme-radius-full);
            border: 1px solid var(--ds-border-subtle);
        }

        .avatar-img {
            width: 24px;
            height: 24px;
            border-radius: var(--theme-radius-full);
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .avatar-initial {
            width: 24px;
            height: 24px;
            border-radius: var(--theme-radius-full);
            background: var(--ds-color-brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--theme-neutral-0);
            font-size: var(--theme-font-size-xs);
        }

        .username {
            font-size: var(--theme-font-size-sm);
            font-weight: var(--theme-font-weight-medium);
            color: var(--theme-neutral-300);
        }

        /* Main layout */
        main {
            flex: 1;
            padding: var(--theme-spacing-12) 0;
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-12);
        }

        .hero-title-section {
            text-align: center;
            max-width: 768px;
            margin: 0 auto;
        }

        .hero-title {
            margin: 0 0 var(--theme-spacing-4);
        }

        .hero-desc {
            color: var(--ds-text-secondary);
            margin: 0;
        }

        /* Extraction Card Form */
        .extraction-form-wrapper {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
        }

        @media (min-width: 768px) {
            .extraction-form-wrapper {
                flex-direction: row;
                align-items: flex-end;
            }
        }

        .input-container-flex {
            flex: 1;
            width: 100%;
        }

        .btn-container-flex {
            width: 100%;
        }

        @media (min-width: 768px) {
            .btn-container-flex {
                width: auto;
            }
        }

        /* Tabs and Details */
        .tabs-container {
            background: var(--ds-surface-glass);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-2xl);
            overflow: hidden;
            box-shadow: var(--theme-shadow-xl);
            margin-top: var(--theme-spacing-6);
        }

        .tabs-list {
            display: flex;
            border-bottom: 1px solid var(--ds-border-subtle);
            overflow-x: auto;
            background: rgba(0, 0, 0, 0.15);
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--ds-text-secondary);
            font-family: var(--theme-font-sans);
            font-weight: var(--theme-font-weight-semibold);
            font-size: var(--theme-font-size-sm);
            padding: var(--theme-spacing-4) var(--theme-spacing-6);
            cursor: pointer;
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-2);
            white-space: nowrap;
        }

        .tab-btn:hover {
            color: var(--ds-text-primary);
            background: rgba(255, 255, 255, 0.02);
        }

        .tab-btn.tab-active {
            border-bottom: 2px solid var(--ds-color-brand);
            color: var(--ds-text-brand);
        }

        .tab-content-panel {
            padding: var(--theme-spacing-6);
        }

        .hidden {
            display: none !important;
        }

        /* Error and loading states */
        .error-banner {
            margin-top: var(--theme-spacing-6);
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--theme-spacing-4);
            padding: var(--theme-spacing-8) 0;
            text-align: center;
        }

        .spinner-wrapper {
            position: relative;
            width: 56px;
            height: 56px;
        }

        .spinner-bg {
            width: 56px;
            height: 56px;
            border: 4px solid rgba(168, 85, 247, 0.2);
            border-radius: var(--theme-radius-full);
        }

        .spinner-fg {
            width: 56px;
            height: 56px;
            border: 4px solid var(--ds-color-brand);
            border-top-color: transparent;
            border-radius: var(--theme-radius-full);
            animation: spin 0.8s linear infinite;
            position: absolute;
            top: 0;
            left: 0;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-title {
            color: var(--ds-color-brand-subtle);
            margin: 0 0 var(--theme-spacing-1);
        }

        .loading-subtitle {
            color: var(--ds-text-muted);
            margin: 0;
        }

        /* Feature grid styling */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: var(--theme-spacing-6);
        }

        .feature-icon-box {
            width: 40px;
            height: 40px;
            border-radius: var(--theme-radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: var(--theme-spacing-4);
        }

        .icon-brand { background: rgba(168, 85, 247, 0.1); }
        .icon-accent { background: rgba(236, 72, 153, 0.1); }
        .icon-electric { background: rgba(6, 182, 212, 0.1); }
        .icon-success { background: rgba(34, 197, 94, 0.1); }

        .feature-item-title {
            color: var(--ds-text-primary);
            margin: 0 0 var(--theme-spacing-2);
        }

        .feature-item-desc {
            color: var(--ds-text-secondary);
            margin: 0;
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

        .result-actions {
            display: flex;
            flex-wrap: wrap;
            gap: var(--theme-spacing-3);
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
            justify-content: justify;
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

        /* General spacing helpers */
        .space-y-6 > * + * { margin-top: var(--theme-spacing-6); }
        .space-y-4 > * + * { margin-top: var(--theme-spacing-4); }
        .space-y-3 > * + * { margin-top: var(--theme-spacing-3); }
        
        .grid-half {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--theme-spacing-4);
        }

        .guide-outcomes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--theme-spacing-3);
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .outcome-item {
            display: flex;
            align-items: flex-start;
            gap: var(--theme-spacing-2);
            color: var(--ds-text-secondary);
            font-size: var(--theme-font-size-sm);
        }

        /* Footer styling */
        footer {
            background: rgba(6, 11, 24, 0.8);
            border-top: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-8) 0;
            margin-top: var(--theme-spacing-12);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header>
            <div class="container header-content">
                <a href="{{ route('landing') }}" class="logo">
                    <i class="ph ph-film-strip" style="color: var(--ds-text-brand); font-size: 1.75rem;"></i>
                    <span class="ds-type-heading-sm" style="margin: 0;">YouExtractor</span>
                </a>
                
                <div class="user-actions">
                    @auth
                        <div class="user-profile">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" class="avatar-img" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="avatar-initial">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <span class="username">{{ Auth::user()->name }}</span>
                        </div>
                        
                        <a href="/videos" style="text-decoration: none;">
                            <ds-button label="My Library" variant="ghost" size="sm" icon="books"></ds-button>
                        </a>
                        
                        <form action="/logout" method="POST" style="display: inline;" id="logoutForm">
                            @csrf
                            <ds-button type="submit" label="Sign Out" variant="secondary" size="sm" icon="sign-out"></ds-button>
                        </form>
                    @else
                        <div class="user-actions">
                            <a href="/signin" style="text-decoration: none;">
                                <ds-button label="Sign In" variant="ghost" size="sm"></ds-button>
                            </a>
                            <a href="/signup" style="text-decoration: none;">
                                <ds-button label="Get Started" variant="primary" size="sm"></ds-button>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container">
            <!-- Hero Title -->
            <div class="hero-title-section">
                <h1 class="ds-type-display-sm hero-title">Learn Faster, Code Smarter</h1>
                <p class="ds-type-body-lg hero-desc">
                    Paste any programming tutorial URL and get all the code snippets, 
                    <span style="color: var(--ds-text-brand)">complete setup guides</span>, 
                    <span style="color: var(--ds-text-accent)">IDE recommendations</span>, and 
                    <span style="color: var(--ds-text-electric)">step-by-step instructions</span>.
                </p>
            </div>

            <!-- Input Section -->
            <ds-card variant="glass-accent" padding="lg">
                <form id="videoForm">
                    <div class="extraction-form-wrapper">
                        <div class="input-container-flex">
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
                        <div class="btn-container-flex">
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
                <div id="error" class="error-banner hidden">
                    <span class="ds-badge-error" style="width: 100%; justify-content: center; padding: var(--theme-spacing-3) 0;">
                        <i class="ph ph-warning-circle" style="margin-right: var(--theme-spacing-2); font-size: 1rem;"></i>
                        <span id="errorText"></span>
                    </span>
                </div>

                <!-- Loading State -->
                <div id="loading" class="hidden">
                    <div class="loading-container">
                        <div class="spinner-wrapper">
                            <div class="spinner-bg"></div>
                            <div class="spinner-fg"></div>
                        </div>
                        <div>
                            <h3 class="ds-type-heading-sm loading-title" id="loadingText">Extracting video information...</h3>
                            <p class="ds-type-body-sm loading-subtitle">Generating tutorial guide, IDE recommendations, and code files...</p>
                        </div>
                    </div>
                </div>
            </ds-card>

            <!-- Results Section -->
            <div id="results"></div>

            <!-- Features Grid (shown when empty/default state) -->
            <div id="features" class="features-grid">
                <ds-card variant="glass" padding="md">
                    <div class="feature-icon-box icon-brand">
                        <i class="ph ph-book-open" style="color: var(--ds-text-brand); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-item-title">Tutorial Guide</h3>
                    <p class="ds-type-body-sm feature-item-desc">Get a complete explanation of what the video teaches and key concepts.</p>
                </ds-card>
                
                <ds-card variant="glass" padding="md">
                    <div class="feature-icon-box icon-accent">
                        <i class="ph ph-laptop" style="color: var(--ds-text-accent); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-item-title">IDE Recommendations</h3>
                    <p class="ds-type-body-sm feature-item-desc">Best IDE for the tech stack with extensions and download links.</p>
                </ds-card>

                <ds-card variant="glass" padding="md">
                    <div class="feature-icon-box icon-electric">
                        <i class="ph ph-wrench" style="color: var(--ds-text-electric); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-item-title">Setup & Run Guide</h3>
                    <p class="ds-type-body-sm feature-item-desc">Step-by-step instructions to set up and run the code.</p>
                </ds-card>

                <ds-card variant="glass" padding="md">
                    <div class="feature-icon-box icon-success">
                        <i class="ph ph-download-simple" style="color: var(--ds-color-success-subtle); font-size: 1.25rem;"></i>
                    </div>
                    <h3 class="ds-type-heading-sm feature-item-title">Download Ready</h3>
                    <p class="ds-type-body-sm feature-item-desc">Get a ZIP file with all code, organized by project structure.</p>
                </ds-card>
            </div>
        </main>

        <!-- Footer -->
        <footer>
            <div class="container" style="text-align: center; color: var(--ds-text-muted); font-size: var(--theme-font-size-sm);">
                <p>YouTube Code Extractor &bull; Built for developers who learn by watching</p>
            </div>
        </footer>
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>

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

                if (data.data && (data.data.extraction_status === 'pending' || data.data.extraction_status === 'processing')) {
                    const completedVideo = await pollExtractionStatus(data.data.id);
                    displayResults(completedVideo);
                } else {
                    displayResults(data.data);
                }
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

        async function pollExtractionStatus(videoId) {
            return new Promise((resolve, reject) => {
                const pollInterval = setInterval(async () => {
                    try {
                        const response = await fetch(`/api/videos/${videoId}/status`);
                        if (!response.ok) {
                            throw new Error('Failed to fetch extraction status');
                        }
                        const resData = await response.json();
                        if (!resData.success) {
                            throw new Error(resData.error || 'Status check failed');
                        }
                        
                        if (resData.status === 'completed') {
                            clearInterval(pollInterval);
                            resolve(resData.data);
                        } else if (resData.status === 'failed') {
                            clearInterval(pollInterval);
                            reject(new Error(resData.error || 'Extraction failed'));
                        }
                    } catch (error) {
                        clearInterval(pollInterval);
                        reject(error);
                    }
                }, 3000);
            });
        }

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
                    stackBadges += `<span class="ds-badge-brand" style="margin-right: var(--theme-spacing-2);">${stack.primary}</span>`;
                }
                if (stack.frameworks && stack.frameworks.length > 0) {
                    stack.frameworks.forEach(fw => {
                        stackBadges += `<span class="ds-badge-electric" style="margin-right: var(--theme-spacing-2);">${fw}</span>`;
                    });
                }
            }

            const html = `
                <div class="space-y-6" style="animation: fadeIn 0.4s var(--theme-ease-out);">
                    <!-- Video Info Card -->
                    <ds-card variant="glass-accent" padding="lg">
                        <div class="result-info-header">
                            <div style="flex: 1;">
                                <h3 class="ds-type-heading-md text-white" style="word-break: break-word; margin: 0;">${escapeHtml(video.title)}</h3>
                                <div class="result-meta">
                                    ${stackBadges}
                                    ${hasCode ? `<span class="ds-badge-success">${video.code_snippets.length} files</span>` : ''}
                                </div>
                                <div class="result-actions">
                                    <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" style="text-decoration: none;">
                                        <ds-button label="Watch on YouTube" variant="ghost" size="sm" icon="youtube-logo"></ds-button>
                                    </a>
                                    ${hasCode ? `
                                        <a href="/api/videos/${video.id}/download" style="text-decoration: none;">
                                            <ds-button label="Download All Code" variant="ghost" size="sm" icon="download-simple"></ds-button>
                                        </a>
                                    ` : ''}
                                </div>
                            </div>
                            ${hasCode ? `
                            <div style="width: 100%; max-width: 200px;">
                                <a href="/api/videos/${video.id}/download" style="text-decoration: none; display: block; width: 100%;">
                                    <ds-button label="Download ZIP" variant="glow" size="lg" icon="file-zip" style="width: 100%;"></ds-button>
                                </a>
                            </div>` : ''}
                        </div>
                    </ds-card>

                    <!-- Tabs Container -->
                    <div class="tabs-container">
                        <!-- Tab Bar -->
                        <div class="tabs-list">
                            <button onclick="showTab('overview', '${video.id}')" id="tab-overview" class="tab-btn tab-active">
                                <i class="ph ph-book-open"></i> Tutorial
                            </button>
                            <button onclick="showTab('ide', '${video.id}')" id="tab-ide" class="tab-btn">
                                <i class="ph ph-laptop"></i> IDE
                            </button>
                            <button onclick="showTab('setup', '${video.id}')" id="tab-setup" class="tab-btn">
                                <i class="ph ph-wrench"></i> Setup
                            </button>
                            <button onclick="showTab('run', '${video.id}')" id="tab-run" class="tab-btn">
                                <i class="ph ph-play"></i> Run
                            </button>
                            <button onclick="showTab('code', '${video.id}')" id="tab-code" class="tab-btn">
                                <i class="ph ph-folder-open"></i> Code (${video.code_snippets?.length || 0})
                            </button>
                        </div>

                        <!-- Tab Content -->
                        <div class="tab-content-panel">
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
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                            <i class="ph ph-book-open"></i> Overview
                        </h3>
                        <p class="ds-type-body-md text-gray-300" style="line-height: var(--theme-line-height-relaxed); whitespace-pre-wrap; margin: 0;">${escapeHtml(guide.overview)}</p>
                    </div>
                `;
            }

            if (guide.key_concepts && guide.key_concepts.length > 0) {
                html += `
                    <div class="space-y-4" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                            <i class="ph ph-brain"></i> Key Concepts
                        </h3>
                        <div class="grid-half">
                            ${guide.key_concepts.map(concept => `
                                <ds-card variant="glass" padding="sm">
                                    <h4 class="font-bold text-white" style="margin: 0 0 var(--theme-spacing-2);">${escapeHtml(concept.concept)}</h4>
                                    <p class="ds-type-body-sm text-gray-400" style="margin: 0;">${escapeHtml(concept.explanation)}</p>
                                </ds-card>
                            `).join('')}
                        </div>
                    </div>
                `;
            }

            if (guide.learning_outcomes && guide.learning_outcomes.length > 0) {
                html += `
                    <div class="space-y-3" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                            <i class="ph ph-target"></i> What You'll Learn
                        </h3>
                        <ul class="guide-outcomes">
                            ${guide.learning_outcomes.map(outcome => `
                                <li class="outcome-item">
                                    <i class="ph ph-check-circle" style="color: var(--ds-color-success); font-size: 1rem; margin-top: 2px;"></i>
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
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-accent); margin: 0;">
                            <i class="ph ph-star"></i> Recommended IDE
                        </h3>
                        <ds-card variant="glow" padding="lg">
                            <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
                                <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                    <h4 class="ds-type-heading-md text-white" style="margin: 0;">${escapeHtml(ide.primary.name)}</h4>
                                    <p class="ds-type-body-sm text-gray-300" style="margin: 0;">${escapeHtml(ide.primary.reason)}</p>
                                </div>
                                <div style="width: auto; align-self: flex-start;">
                                    <a href="${escapeHtml(ide.primary.download_url)}" target="_blank" style="text-decoration: none;">
                                        <ds-button label="Download" variant="primary" size="sm" icon="download-simple"></ds-button>
                                    </a>
                                </div>
                            </div>
                            ${ide.primary.extensions && ide.primary.extensions.length > 0 ? `
                                <div style="margin-top: var(--theme-spacing-5); border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: var(--theme-spacing-4);">
                                    <p class="ds-type-label-md text-pink-300" style="margin: 0 0 var(--theme-spacing-2);">Recommended Extensions:</p>
                                    <div style="display: flex; flex-wrap: wrap; gap: var(--theme-spacing-2);">
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
                    <div class="space-y-4" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-accent); margin: 0;">
                            <i class="ph ph-shuffle"></i> Alternatives
                        </h3>
                        <div class="grid-half">
                            ${ide.alternatives.map(alt => `
                                <ds-card variant="glass" padding="md">
                                    <div style="display: flex; align-items: start; justify-content: space-between; gap: var(--theme-spacing-4);">
                                        <h4 class="font-bold text-white" style="margin: 0;">${escapeHtml(alt.name)}</h4>
                                        <a href="${escapeHtml(alt.download_url)}" target="_blank" style="color: var(--ds-text-accent); text-decoration: none; font-size: var(--theme-font-size-xs); font-weight: 600; display: flex; align-items: center; gap: 2px;">
                                            Download <i class="ph ph-arrow-square-out"></i>
                                        </a>
                                    </div>
                                    <p class="ds-type-body-sm text-gray-400" style="margin: var(--theme-spacing-2) 0 0;">${escapeHtml(alt.reason)}</p>
                                    ${alt.extensions && alt.extensions.length > 0 ? `
                                        <div style="margin-top: var(--theme-spacing-3); display: flex; flex-wrap: wrap; gap: var(--theme-spacing-2); padding-top: var(--theme-spacing-2); border-top: 1px solid rgba(255, 255, 255, 0.05);">
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
                    <div class="space-y-3" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-accent); margin: 0;">
                            <i class="ph ph-graduation-cap"></i> Required Knowledge
                        </h3>
                        <ul class="guide-outcomes">
                            ${prerequisites.knowledge.map(k => `
                                <li class="outcome-item">
                                    <i class="ph ph-book-bookmark" style="color: var(--ds-text-accent); font-size: 1rem; margin-top: 2px;"></i>
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
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-electric); margin: 0;">
                            <i class="ph ph-cube"></i> Prerequisites
                        </h3>
                        <div class="grid-half">
                            ${prerequisites.software.map(sw => `
                                <ds-card variant="glass" padding="md">
                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: var(--theme-spacing-4);">
                                        <div>
                                            <h4 class="font-bold text-white" style="margin: 0;">${escapeHtml(sw.name)}</h4>
                                            <p class="ds-type-body-sm text-gray-400" style="margin: var(--theme-spacing-1) 0 0;">${escapeHtml(sw.purpose)}</p>
                                        </div>
                                        <a href="${escapeHtml(sw.download_url)}" target="_blank" style="text-decoration: none;">
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
                    <div class="space-y-4" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-electric); margin: 0;">
                            <i class="ph ph-steps"></i> Setup Steps
                        </h3>
                        <div class="space-y-4">
                            ${setupGuide.steps.map(step => `
                                <ds-card variant="glass" padding="md">
                                    <div style="display: flex; align-items: center; gap: var(--theme-spacing-3); margin-bottom: var(--theme-spacing-3);">
                                        <span style="width: 28px; height: 28px; background: var(--ds-color-electric); border-radius: var(--theme-radius-full); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--theme-neutral-1000); font-size: var(--theme-font-size-sm);">${step.step}</span>
                                        <h4 class="font-bold text-white" style="margin: 0;">${escapeHtml(step.title)}</h4>
                                    </div>
                                    <p class="ds-type-body-sm text-gray-300" style="margin: 0 0 var(--theme-spacing-3);">${escapeHtml(step.explanation)}</p>
                                    ${step.commands && step.commands.length > 0 ? `
                                        <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                            ${step.commands.map(cmd => `
                                                <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                                    <code class="text-green-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                                    <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy"></ds-button>
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
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-color-success); margin: 0;">
                            <i class="ph ph-terminal"></i> Development Mode
                        </h3>
                        <ds-card variant="glow-electric" padding="md">
                            <p class="ds-type-body-sm text-gray-300" style="margin: 0 0 var(--theme-spacing-3);">${escapeHtml(runGuide.development.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); margin-bottom: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.development.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-green-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy"></ds-button>
                                    </div>
                                `).join('')}
                            </div>
                            ${runGuide.development.access_url ? `
                                <p style="font-size: var(--theme-font-size-xs); color: var(--ds-text-muted); margin: 0;">Access local server at: <a href="${escapeHtml(runGuide.development.access_url)}" target="_blank" style="color: var(--ds-text-brand); text-decoration: underline;">${escapeHtml(runGuide.development.access_url)}</a></p>
                            ` : ''}
                        </ds-card>
                    </div>
                `;
            }

            if (runGuide.production) {
                html += `
                    <div class="space-y-3" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-brand); margin: 0;">
                            <i class="ph ph-rocket"></i> Production Build
                        </h3>
                        <ds-card variant="glass-accent" padding="md">
                            <p class="ds-type-body-sm text-gray-300" style="margin: 0 0 var(--theme-spacing-3);">${escapeHtml(runGuide.production.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.production.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-purple-400 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy"></ds-button>
                                    </div>
                                `).join('')}
                            </div>
                        </ds-card>
                    </div>
                `;
            }

            if (runGuide.docker) {
                html += `
                    <div class="space-y-3" style="padding-top: var(--theme-spacing-4);">
                        <h3 class="ds-type-heading-sm flex items-center gap-2" style="color: var(--ds-text-electric); margin: 0;">
                            <i class="ph ph-package"></i> Docker Container
                        </h3>
                        <ds-card variant="glass" padding="md">
                            <p class="ds-type-body-sm text-gray-300" style="margin: 0 0 var(--theme-spacing-3);">${escapeHtml(runGuide.docker.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.docker.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-cyan-450 font-mono text-sm">${escapeHtml(cmd)}</code>
                                        <ds-button onclick="copyToClipboard('${escapeHtml(cmd).replace(/'/g, "\\'")}')" label="Copy" variant="ghost" size="sm" icon="copy"></ds-button>
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
                        <div class="code-snippet-card">
                            <div class="code-card-header">
                                <div class="code-meta-left">
                                    <i class="ph ph-file-code" style="color: var(--theme-yellow-500); font-size: 1.25rem;"></i>
                                    <span class="code-path">${escapeHtml(file.path || file.filename)}</span>
                                    <span class="ds-badge-electric">${escapeHtml(file.language)}</span>
                                </div>
                                <ds-button onclick="copyCode(${idx})" label="Copy Code" variant="ghost" size="sm" icon="copy"></ds-button>
                            </div>
                            <pre class="code-pre"><code id="code-${idx}" class="language-${escapeHtml(file.language)}">${escapeHtml(file.code)}</code></pre>
                            ${file.description ? `
                                <div class="code-description-footer">
                                    <i class="ph ph-info" style="margin-right: 4px;"></i> ${escapeHtml(file.description)}
                                </div>
                            ` : ''}
                        </div>
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
</body>
</html>
