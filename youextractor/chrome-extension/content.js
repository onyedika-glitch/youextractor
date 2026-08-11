// YouExtractor — content script
//
// Injects a Phantom/MetaMask-style side panel docked to the RIGHT edge of the
// browser, on YouTube pages. The panel handles the whole flow in-place:
//   sign in / create account (forms inside the panel)  ->  one-click extract
//   -> live progress  ->  full results (code files, summary, ZIP download).
// The user never leaves the YouTube tab. All API calls go through
// background.js, which uses the user's youextractor.me session cookie.
//
// NOTE: innerHTML is not used for interactive elements — YouTube's page CSP
// blocks inline handlers, so every control is wired with addEventListener.

(() => {
    if (window.__youextractorInjected) return;
    window.__youextractorInjected = true;

    const APP_BASE = 'https://youextractor.me'; // Update if self-hosted

    const PROGRAMMING_KEYWORDS = [
        'javascript', 'typescript', 'python', 'react', 'vue', 'angular', 'node', 'nodejs',
        'tutorial', 'code', 'coding', 'programming', 'build', 'api', 'css', 'html', 'java',
        'php', 'rust', 'flutter', 'swift', 'kotlin', 'docker', 'aws', 'machine learning',
        'web dev', 'developer', 'sql', 'database', 'git', 'linux', 'backend', 'frontend',
        'fullstack', 'full-stack', 'algorithm', 'data structure', 'spring', 'django', 'flask',
        'laravel', 'rails', 'express', 'next.js', 'nuxt', 'svelte', 'tailwind', 'bootstrap',
        'graphql', 'redis', 'mongodb', 'postgres', 'c++', 'c#', 'golang', 'go ', 'c# ',
        'terraform', 'kubernetes', 'k8s', 'serverless', 'microservice', 'gamedev', 'unity',
        'unreal', 'selenium', 'automation', 'scraping', 'machine', 'neural', 'gpt', 'llm'
    ];

    // ------------------------------------------------------------------
    // State
    // ------------------------------------------------------------------
    let videoId = null;
    let videoTitle = '';
    let panelOpen = false;
    let authPollTimer = null;
    let statusPollTimer = null;
    let oauthPollTimer = null;
    let oauthWindow = null;
    let currentUser = null; // { name, email, avatar }
    let currentLastUsed = null; // 'google' | 'github' | 'email' | null

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------
    function getVideoId() {
        const path = location.pathname;
        const m = path.match(/^\/(?:watch|shorts|embed|live|v)\/([^/?#]+)/);
        if (m && /^[a-zA-Z0-9_-]{11}$/.test(m[1])) return m[1];
        const v = new URLSearchParams(location.search).get('v');
        if (v && /^[a-zA-Z0-9_-]{11}$/.test(v)) return v;
        return null;
    }

    function getVideoTitle() {
        const el = document.querySelector('h1.ytd-watch-metadata yt-formatted-string, h1.style-scope.ytd-watch-metadata');
        if (el && el.textContent.trim()) return el.textContent.trim();
        return document.title.replace(/\s*-\s*YouTube\s*$/, '').trim();
    }

    function isProgrammingVideo(title) {
        const t = (title || '').toLowerCase();
        return PROGRAMMING_KEYWORDS.some((k) => t.includes(k));
    }

    function sendMessage(msg) {
        return new Promise((resolve) => {
            try {
                chrome.runtime.sendMessage(msg, (res) => {
                    if (chrome.runtime.lastError) resolve({ ok: false, error: chrome.runtime.lastError.message });
                    else resolve(res || { ok: false, error: 'No response from extension' });
                });
            } catch (err) {
                resolve({ ok: false, error: err.message });
            }
        });
    }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    // Lightweight, dependency-free syntax highlighting (safe under page CSP).
    // Single-pass tokenizer: strings & comments are matched before keywords,
    // so "//" inside a string or a keyword inside a string stay untouched.
    const TOKEN_RE = /("(?:[^"\\]|\\.)*"|'(?:[^'\\]|\\.)*'|`(?:[^`\\]|\\.)*`|\/\/[^\n]*|\/\*[\s\S]*?\*\/|#[ \t][^\n]*|--[ \t][^\n]*|\b\d+(?:\.\d+)?\b|\b(const|let|var|function|return|if|else|for|while|do|class|import|from|export|default|async|await|new|try|catch|finally|throw|switch|case|break|continue|typeof|instanceof|of|in|is|public|private|protected|static|void|int|float|double|char|long|short|bool|boolean|true|false|null|undefined|NaN|def|print|lambda|pass|self|None|True|False|and|or|not|package|require|module|interface|extends|implements|enum|goto|struct|type|func|select|defer|range|map|chan|go|mut|use|impl|trait|fn|match|ref|move|String|Int|Float|Bool|Vec|Option|Some|end|begin|rescue|ensure|puts|gets|include|attr_reader|elif|yield|global|nonlocal|del|with|as|assert|raise|except|finally|ifdef|endif|include_once|require_once|namespace|using|sizeof|printf|scanf|signed|unsigned|volatile|extern)\b)/g;

    function highlightCode(code, language) {
        const escaped = escapeHtml(code);
        return escaped.replace(TOKEN_RE, (m) => {
            const c = m[0];
            if (c === '"' || c === "'" || c === '`') return `<span class="yex-tok-string">${m}</span>`;
            if (c === '/' || c === '#' || c === '-') return `<span class="yex-tok-comment">${m}</span>`;
            if (c >= '0' && c <= '9') return `<span class="yex-tok-number">${m}</span>`;
            return `<span class="yex-tok-keyword">${m}</span>`;
        });
    }

    function fileExt(name) {
        const i = (name || '').lastIndexOf('.');
        return i >= 0 ? name.slice(i + 1) : '';
    }

    function fileColor(ext) {
        const map = {
            js: '#f7df1e', ts: '#3178c6', jsx: '#61dafb', tsx: '#61dafb', py: '#3776ab',
            html: '#e34f26', css: '#2965f1', scss: '#cd6799', json: '#f5a623', md: '#94a3b8',
            php: '#777bb4', java: '#e76f00', rb: '#cc342d', go: '#00add8', rs: '#dea584',
            c: '#5b8fb9', cpp: '#00599c', h: '#5b8fb9', cs: '#68217a', sh: '#4eaa25',
            bash: '#4eaa25', yml: '#cb171e', yaml: '#cb171e', sql: '#e38c00', xml: '#e34f26',
            vue: '#41b883', swift: '#f05138', kt: '#7f52ff', dart: '#0175c2'
        };
        return map[ext] || '#8b98a5';
    }

    // ------------------------------------------------------------------
    // Shadow DOM UI scaffold
    // ------------------------------------------------------------------
    const host = document.createElement('div');
    host.id = 'youextractor-root';
    const shadow = host.attachShadow({ mode: 'open' });

    const style = document.createElement('style');
    style.textContent = `
        :host { all: initial; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        /* Floating trigger button */
        .yex-fab {
            position: fixed;
            right: 20px;
            bottom: 24px;
            z-index: 2147483645;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #14b8a6;
            color: #fff;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: -0.2px;
            padding: 10px 16px;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.15);
            box-shadow: 0 6px 22px rgba(20,184,166,0.45), 0 2px 6px rgba(0,0,0,0.3);
            cursor: pointer;
            user-select: none;
            transition: transform 160ms ease, box-shadow 160ms ease;
            animation: yex-fab-in 300ms cubic-bezier(.2,.9,.3,1.2) both;
        }
        .yex-fab:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(20,184,166,0.6); }
        .yex-fab:active { transform: translateY(0) scale(0.97); }
        .yex-fab .yex-bolt { font-size: 15px; line-height: 1; }
        @keyframes yex-fab-in { from { opacity: 0; transform: translateY(14px) scale(0.9); } to { opacity: 1; transform: none; } }

        /* Dimmed backdrop — makes the panel read as a browser side panel */
        .yex-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 2147483646;
        }

        /* The side panel — full height, docked right (Phantom/MetaMask style) */
        .yex-panel {
            position: fixed;
            top: 0;
            right: 0;
            width: 420px;
            max-width: 100vw;
            height: 100vh;
            height: 100dvh;
            z-index: 2147483647;
            display: flex;
            flex-direction: column;
            background: #0c0d10;
            color: #fafafa;
            border-left: 1px solid rgba(255,255,255,0.09);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            box-shadow: -14px 0 50px rgba(0,0,0,0.5);
            animation: yex-slide-in 260ms cubic-bezier(.2,.9,.3,1) both;
        }
        @keyframes yex-slide-in { from { transform: translateX(100%); } to { transform: none; } }

        .yex-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            background: rgba(255,255,255,0.02);
            flex-shrink: 0;
        }
        .yex-logo {
            width: 24px; height: 24px; border-radius: 6px; object-fit: cover;
            border: 1px solid rgba(20,184,166,0.35);
        }
        .yex-title { font-size: 15px; font-weight: 700; color: #fff; letter-spacing: -0.2px; }
        .yex-badge {
            font-size: 9px; font-weight: 600; color: #2dd4bf;
            background: rgba(20,184,166,0.14); border-radius: 999px; padding: 1px 7px;
        }
        .yex-user { display: flex; align-items: center; gap: 7px; margin-left: auto; min-width: 0; }
        .yex-avatar {
            width: 24px; height: 24px; border-radius: 50%; object-fit: cover;
            border: 1px solid rgba(20,184,166,0.35); flex-shrink: 0;
        }
        .yex-avatar-fallback {
            width: 24px; height: 24px; border-radius: 50%; background: #14b8a6;
            color: #fff; font-size: 11px; font-weight: 700; display: none;
            align-items: center; justify-content: center; flex-shrink: 0;
        }
        .yex-user-name {
            font-size: 11px; color: #d4d4d8; max-width: 110px;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600;
        }
        .yex-signout {
            background: transparent; border: 1px solid rgba(255,255,255,0.12);
            color: #a1a1aa; font-size: 10px; padding: 2px 9px; border-radius: 999px;
            cursor: pointer; font-family: inherit; flex-shrink: 0;
        }
        .yex-signout:hover { color: #fca5a5; border-color: rgba(239,68,68,0.4); }
        .yex-close {
            background: transparent; border: none; color: #9ca3af;
            font-size: 16px; line-height: 1; cursor: pointer; padding: 4px 6px; border-radius: 6px;
            flex-shrink: 0;
        }
        .yex-close:hover { color: #fff; background: rgba(255,255,255,0.08); }

        .yex-body { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; min-height: 0; }

        .yex-view { display: none; flex-direction: column; gap: 12px; }
        .yex-view.active { display: flex; }

        .yex-spinner-wrap { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 34px 0; text-align: center; }
        .yex-spinner {
            width: 36px; height: 36px; border-radius: 50%;
            border: 3px solid rgba(20,184,166,0.15); border-top-color: #14b8a6;
            animation: yex-spin 0.8s linear infinite;
        }
        @keyframes yex-spin { to { transform: rotate(360deg); } }
        .yex-spinner-text { font-size: 12px; color: #a1a1aa; max-width: 300px; }

        .yex-hint { font-size: 13px; color: #d4d4d8; line-height: 1.5; }
        .yex-hint b { color: #fff; }
        .yex-pitch { font-size: 12px; color: #a1a1aa; line-height: 1.55; }

        /* ---- In-panel auth ---- */
        .yex-auth-tabs { display: flex; background: #18181b; border-radius: 10px; padding: 3px; gap: 3px; }
        .yex-auth-tab {
            flex: 1; background: transparent; border: none; color: #a1a1aa;
            padding: 9px 8px; border-radius: 8px; font-size: 12.5px; font-weight: 600;
            cursor: pointer; font-family: inherit; transition: all 140ms ease;
        }
        .yex-auth-tab.active { background: #27272a; color: #fff; }
        .yex-auth-form { display: none; flex-direction: column; gap: 8px; }
        .yex-auth-form.active { display: flex; }
        .yex-auth-form label { font-size: 11px; color: #a1a1aa; font-weight: 600; margin-top: 4px; }
        .yex-auth-form input {
            background: #18181b; border: 1px solid rgba(255,255,255,0.1);
            color: #fafafa; border-radius: 9px; padding: 10px 12px; font-size: 13px;
            font-family: inherit; outline: none; width: 100%;
        }
        .yex-auth-form input:focus { border-color: #14b8a6; }
        .yex-auth-form input::placeholder { color: #52525b; }
        .yex-auth-error {
            color: #fca5a5; font-size: 12px; line-height: 1.45;
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
            border-radius: 8px; padding: 8px 10px; display: none;
        }
        .yex-auth-error.show { display: block; }
        .yex-auth-divider { display: flex; align-items: center; gap: 10px; color: #52525b; font-size: 10.5px; margin: 4px 0; }
        .yex-auth-divider::before, .yex-auth-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08); }
        .yex-social-row { display: flex; gap: 8px; }
        .yex-social-row .yex-btn { flex: 1; padding: 10px 8px; font-size: 12px; }
        .yex-oauth-btn { display: flex; align-items: center; justify-content: center; gap: 7px; }
        .yex-oauth-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
        .yex-oauth-google { background: #ffffff; color: #1f1f1f; }
        .yex-oauth-google:hover:not(:disabled) { background: #f1f1f1; }
        .yex-oauth-github { background: #24292f; color: #fff; border: 1px solid rgba(255,255,255,0.12); }
        .yex-oauth-github:hover:not(:disabled) { background: #2d333b; }
        .yex-oauth-note { font-size: 11px; color: #2dd4bf; text-align: center; min-height: 14px; }

        .yex-video-card {
            background: #18181b; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px; padding: 12px 14px;
        }
        .yex-video-title { font-size: 12.5px; font-weight: 600; color: #f4f4f5; line-height: 1.35; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .yex-video-meta { font-size: 10px; color: #71717a; margin-top: 3px; }
        .yex-tutorial-badge {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 9.5px; font-weight: 600; color: #22c55e;
            background: rgba(34,197,94,0.12); border-radius: 999px; padding: 2px 8px; margin-top: 8px;
        }

        .yex-btn {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            width: 100%; padding: 11px 14px; border-radius: 10px;
            font-family: inherit; font-size: 13px; font-weight: 700; cursor: pointer;
            border: none; transition: all 150ms ease; color: #fff; position: relative;
        }
        .yex-btn:disabled { opacity: 0.55; cursor: not-allowed; }
        .yex-btn-primary { background: #14b8a6; box-shadow: 0 2px 10px rgba(20,184,166,0.35); }
        .yex-btn-primary:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(20,184,166,0.5); }
        .yex-btn-secondary { background: #27272a; border: 1px solid rgba(255,255,255,0.08); }
        .yex-btn-secondary:hover:not(:disabled) { background: #323236; }
        .yex-btn-ghost { background: transparent; border: 1px solid rgba(255,255,255,0.12); color: #d4d4d8; font-weight: 600; }
        .yex-btn-ghost:hover:not(:disabled) { background: rgba(255,255,255,0.05); }

        .yex-last-used {
            position: absolute; top: -7px; right: 10px; z-index: 1;
            background: #14b8a6; color: #06231f; font-size: 8.5px; font-weight: 800;
            letter-spacing: 0.3px; text-transform: uppercase;
            padding: 1px 7px; border-radius: 999px; pointer-events: none;
        }

        .yex-remember { display: flex; align-items: center; gap: 7px; font-size: 11.5px; color: #a1a1aa; cursor: pointer; user-select: none; }
        .yex-remember input { width: auto; accent-color: #14b8a6; cursor: pointer; }

        .yex-status { font-size: 10.5px; text-align: center; color: #71717a; }

        .yex-error-box {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
            color: #fca5a5; font-size: 12px; padding: 10px 12px; border-radius: 10px; line-height: 1.5;
        }

        /* Progress */
        .yex-progress-track { width: 100%; height: 6px; border-radius: 999px; background: rgba(255,255,255,0.07); overflow: hidden; }
        .yex-progress-bar { height: 100%; width: 0; border-radius: 999px; background: linear-gradient(90deg, #14b8a6, #2dd4bf); transition: width 500ms ease; }

        /* Results */
        .yex-result-title { font-size: 13.5px; font-weight: 700; color: #fff; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .yex-summary { font-size: 12px; color: #a1a1aa; line-height: 1.55; max-height: 90px; overflow-y: auto; }
        .yex-stack-row { display: flex; flex-wrap: wrap; gap: 5px; align-items: center; }
        .yex-stack-chip { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 999px; }
        .yex-stack-primary { background: rgba(20,184,166,0.15); color: #2dd4bf; }
        .yex-stack-fw { background: rgba(56,189,248,0.13); color: #7dd3fc; }
        .yex-file-count { font-size: 10.5px; color: #71717a; }

        .yex-files { display: flex; flex-direction: column; gap: 4px; max-height: 160px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; padding: 6px; }
        .yex-file-row {
            display: flex; align-items: center; gap: 8px; padding: 6px 8px; border-radius: 7px;
            cursor: pointer; font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 11px;
            color: #d4d4d8; background: transparent; border: none; text-align: left; width: 100%;
        }
        .yex-file-row:hover { background: rgba(255,255,255,0.05); }
        .yex-file-row.active { background: rgba(20,184,166,0.12); color: #2dd4bf; }
        .yex-file-dot { width: 8px; height: 8px; border-radius: 3px; flex-shrink: 0; }
        .yex-file-name { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .yex-file-ext { font-size: 9px; opacity: 0.55; flex-shrink: 0; }

        .yex-code-viewer {
            background: #101114; border: 1px solid rgba(255,255,255,0.07); border-radius: 10px; overflow: hidden;
        }
        .yex-code-head {
            display: flex; align-items: center; justify-content: space-between; gap: 8px;
            padding: 7px 10px; background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.07);
            font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 10.5px; color: #9ca3af;
        }
        .yex-code-path { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .yex-code-copy { background: transparent; border: 1px solid rgba(255,255,255,0.1); color: #a1a1aa; font-size: 10px; padding: 2px 8px; border-radius: 6px; cursor: pointer; flex-shrink: 0; }
        .yex-code-copy:hover { color: #fff; border-color: rgba(20,184,166,0.5); }
        .yex-code-pre {
            margin: 0; padding: 10px; overflow: auto; max-height: 260px;
            font-family: 'JetBrains Mono', ui-monospace, monospace; font-size: 11px; line-height: 1.55;
            color: #e4e4e7; white-space: pre; tab-size: 4;
        }
        .yex-tok-comment { color: #6b7280; font-style: italic; }
        .yex-tok-string { color: #a5d6a7; }
        .yex-tok-keyword { color: #c084fc; }
        .yex-tok-number { color: #fbbf24; }

        .yex-actions { display: flex; flex-direction: column; gap: 7px; }
        .yex-row { display: flex; gap: 7px; }
        .yex-row .yex-btn { flex: 1; }
        .yex-note { font-size: 10px; color: #52525b; text-align: center; line-height: 1.5; }

        .yex-footer {
            flex-shrink: 0; padding: 10px 16px; border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 10px; color: #52525b; text-align: center;
        }
        .yex-footer a { color: #71717a; text-decoration: none; }
        .yex-footer a:hover { color: #2dd4bf; }
    `;

    // --- Trigger button ---
    const fab = document.createElement('button');
    fab.className = 'yex-fab';
    fab.type = 'button';
    fab.innerHTML = '<span class="yex-bolt">⚡</span><span>Extract Code</span>';

    // --- Backdrop ---
    const backdrop = document.createElement('div');
    backdrop.className = 'yex-backdrop';
    backdrop.hidden = true;

    // --- Panel ---
    const panel = document.createElement('div');
    panel.className = 'yex-panel';
    panel.hidden = true;

    // Header
    const header = document.createElement('div');
    header.className = 'yex-header';
    header.innerHTML = `
        <img class="yex-logo" alt="YouExtractor" src="${APP_BASE}/img/youextractor-logo.jpg">
        <span class="yex-title">YouExtractor</span>
        <span class="yex-badge">FREE</span>`;

    const userChip = document.createElement('div');
    userChip.className = 'yex-user';
    userChip.style.display = 'none';
    const avatarImg = document.createElement('img');
    avatarImg.className = 'yex-avatar';
    avatarImg.alt = '';
    avatarImg.hidden = true;
    const avatarFallback = document.createElement('span');
    avatarFallback.className = 'yex-avatar-fallback';
    // Inline onerror is blocked by YouTube's CSP, so wire it here.
    avatarImg.addEventListener('error', () => {
        avatarImg.hidden = true;
        avatarFallback.style.display = 'flex';
    });
    const userName = document.createElement('span');
    userName.className = 'yex-user-name';
    const signOutBtn = document.createElement('button');
    signOutBtn.type = 'button';
    signOutBtn.className = 'yex-signout';
    signOutBtn.textContent = 'Sign out';
    signOutBtn.addEventListener('click', handleLogout);
    userChip.append(avatarImg, avatarFallback, userName, signOutBtn);
    header.appendChild(userChip);

    const closeBtn = document.createElement('button');
    closeBtn.className = 'yex-close';
    closeBtn.type = 'button';
    closeBtn.textContent = '✕';
    closeBtn.title = 'Close';
    closeBtn.addEventListener('click', closePanel);
    header.appendChild(closeBtn);

    // Views
    const viewAuth = document.createElement('div');
    viewAuth.className = 'yex-view';
    viewAuth.innerHTML = `
        <div class="yex-hint">Turn any <b>YouTube coding tutorial</b> into a complete codebase — right here on the video page.</div>
        <div class="yex-social-row">
            <button type="button" class="yex-btn yex-oauth-btn yex-oauth-google" data-yex-oauth="google">
                <span class="yex-last-used" data-yex-last="google" style="display:none;">Last used</span>
                <svg viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                <span>Google</span>
            </button>
            <button type="button" class="yex-btn yex-oauth-btn yex-oauth-github" data-yex-oauth="github">
                <span class="yex-last-used" data-yex-last="github" style="display:none;">Last used</span>
                <svg viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27s1.36.09 2 .27c1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/></svg>
                <span>GitHub</span>
            </button>
        </div>
        <div class="yex-oauth-note"></div>
        <div class="yex-auth-divider">or with email</div>
        <div class="yex-auth-tabs">
            <button type="button" class="yex-auth-tab active" data-yex-tab="signin">Sign In</button>
            <button type="button" class="yex-auth-tab" data-yex-tab="signup">Sign Up</button>
        </div>

        <form class="yex-auth-form active" data-yex-form="signin" novalidate>
            <label for="yex-si-email">Email</label>
            <input id="yex-si-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
            <label for="yex-si-password">Password</label>
            <input id="yex-si-password" type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
            <label class="yex-remember">
                <input id="yex-si-remember" type="checkbox" name="remember"> Remember me
            </label>
            <button type="submit" class="yex-btn yex-btn-primary">
                <span class="yex-last-used" data-yex-last="email" style="display:none;">Last used</span>
                <span class="yex-btn-label">Sign In</span>
            </button>
            <div class="yex-auth-error"></div>
        </form>

        <form class="yex-auth-form" data-yex-form="signup" novalidate>
            <label for="yex-su-name">Name</label>
            <input id="yex-su-name" type="text" name="name" placeholder="Ada Lovelace" autocomplete="name" required>
            <label for="yex-su-email">Email</label>
            <input id="yex-su-email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
            <label for="yex-su-password">Password</label>
            <input id="yex-su-password" type="password" name="password" placeholder="Minimum 8 characters" autocomplete="new-password" required>
            <label for="yex-su-confirm">Confirm password</label>
            <input id="yex-su-confirm" type="password" name="password_confirmation" placeholder="Repeat password" autocomplete="new-password" required>
            <button type="submit" class="yex-btn yex-btn-primary">Create Free Account</button>
            <div class="yex-auth-error"></div>
            <div class="yex-note">Free forever · No credit card required</div>
        </form>`;

    const viewReady = document.createElement('div');
    viewReady.className = 'yex-view';
    viewReady.innerHTML = `
        <div class="yex-video-card">
            <div class="yex-video-title"></div>
            <div class="yex-video-meta">Ready to turn into full project + guide</div>
            <div class="yex-tutorial-badge" style="display:none;">✓ Tutorial detected — extractable</div>
        </div>`;

    const viewExtracting = document.createElement('div');
    viewExtracting.className = 'yex-view';
    viewExtracting.innerHTML = `
        <div class="yex-spinner-wrap">
            <div class="yex-spinner"></div>
            <div class="yex-spinner-text">Starting extraction…</div>
        </div>
        <div class="yex-progress-track"><div class="yex-progress-bar"></div></div>
        <div class="yex-status">Preparing AI extraction</div>`;

    const viewDone = document.createElement('div');
    viewDone.className = 'yex-view';

    const viewError = document.createElement('div');
    viewError.className = 'yex-view';
    viewError.innerHTML = `<div class="yex-error-box"></div>`;

    const body = document.createElement('div');
    body.className = 'yex-body';
    body.append(viewAuth, viewReady, viewExtracting, viewDone, viewError);

    const footer = document.createElement('div');
    footer.className = 'yex-footer';
    footer.innerHTML = `Powered by <a href="${APP_BASE}" target="_blank" rel="noopener">youextractor.me</a> — extract code from any tutorial`;

    panel.append(header, body, footer);
    shadow.append(style, fab, backdrop, panel);

    // ------------------------------------------------------------------
    // View switching
    // ------------------------------------------------------------------
    const VIEWS = { auth: viewAuth, ready: viewReady, extracting: viewExtracting, done: viewDone, error: viewError };

    function showView(name) {
        Object.entries(VIEWS).forEach(([key, el]) => el.classList.toggle('active', key === name));
    }

    function showError(message) {
        const box = viewError.querySelector('.yex-error-box');
        box.textContent = message;
        showView('error');
    }

    // ------------------------------------------------------------------
    // Open / close
    // ------------------------------------------------------------------
    fab.addEventListener('click', () => {
        if (panelOpen) closePanel();
        else openPanel();
    });
    backdrop.addEventListener('click', closePanel);

    function openPanel() {
        if (!videoId) return;
        panelOpen = true;
        panel.hidden = false;
        backdrop.hidden = false;
        fab.style.display = 'none';
        syncReadyView();
        refreshAuth();
    }

    function closePanel() {
        panelOpen = false;
        panel.hidden = true;
        backdrop.hidden = true;
        fab.style.display = videoId ? 'flex' : 'none';
        clearTimeout(authPollTimer);
        clearTimeout(statusPollTimer);
        clearInterval(oauthPollTimer);
        oauthPollTimer = null;
        oauthWindow = null;
    }

    function syncReadyView() {
        const titleEl = viewReady.querySelector('.yex-video-title');
        const badge = viewReady.querySelector('.yex-tutorial-badge');
        titleEl.textContent = videoTitle || 'Untitled video';
        badge.style.display = isProgrammingVideo(videoTitle) ? '' : 'none';
    }

    // ------------------------------------------------------------------
    // Auth
    // ------------------------------------------------------------------
    function updateHeader() {
        if (currentUser) {
            userName.textContent = currentUser.name || currentUser.email || 'Signed in';
            userChip.style.display = 'flex';
            if (currentUser.avatar) {
                avatarImg.src = currentUser.avatar;
                avatarImg.hidden = false;
                avatarFallback.style.display = 'none';
            } else {
                avatarImg.hidden = true;
                avatarImg.removeAttribute('src');
                avatarFallback.style.display = 'flex';
                avatarFallback.textContent = (currentUser.name || '?').trim().charAt(0).toUpperCase() || '?';
            }
        } else {
            userChip.style.display = 'none';
        }
    }

    /** Toggle the "Last used" badge on the matching auth entry point. */
    function showLastUsed(method) {
        viewAuth.querySelectorAll('[data-yex-last]').forEach((el) => {
            el.style.display = method && el.dataset.yexLast === method ? '' : 'none';
        });
    }

    async function refreshAuth() {
        const res = await sendMessage({ type: 'checkAuth' });
        if (!panelOpen) return;
        currentLastUsed = res.lastUsed || null;
        showLastUsed(currentLastUsed);
        if (res.ok && res.authenticated) {
            currentUser = { name: res.name, email: res.email, avatar: res.avatar };
            updateHeader();
            stopAuthPoll();
            syncReadyView();
            showView('ready');
        } else {
            currentUser = null;
            updateHeader();
            showView('auth');
            startAuthPoll();
        }
    }

    function startAuthPoll() {
        stopAuthPoll();
        authPollTimer = setInterval(async () => {
            const res = await sendMessage({ type: 'checkAuth' });
            if (!panelOpen) { stopAuthPoll(); return; }
            if (res.lastUsed) { currentLastUsed = res.lastUsed; showLastUsed(currentLastUsed); }
            if (res.ok && res.authenticated) {
                stopAuthPoll();
                currentUser = { name: res.name, email: res.email, avatar: res.avatar };
                updateHeader();
                syncReadyView();
                showView('ready');
            }
        }, 4000);
    }

    function stopAuthPoll() {
        clearInterval(authPollTimer);
        authPollTimer = null;
    }
    function showAuthError(form, message) {
        const box = form.querySelector('.yex-auth-error');
        box.textContent = message;
        box.classList.add('show');
    }

    function clearAuthError(form) {
        const box = form.querySelector('.yex-auth-error');
        box.textContent = '';
        box.classList.remove('show');
    }

    // Auth tab switching
    viewAuth.querySelectorAll('.yex-auth-tab').forEach((tab) => {
        tab.addEventListener('click', () => {
            viewAuth.querySelectorAll('.yex-auth-tab').forEach((t) => t.classList.toggle('active', t === tab));
            viewAuth.querySelectorAll('.yex-auth-form').forEach((f) => f.classList.toggle('active', f.dataset.yexForm === tab.dataset.yexTab));
        });
    });

    // Google / GitHub OAuth — runs in a popup on the website, then the panel
    // fast-polls /api/me and closes the popup once the session is live.
    // The YouTube tab never navigates.
    const oauthNote = viewAuth.querySelector('.yex-oauth-note');
    const oauthButtons = viewAuth.querySelectorAll('[data-yex-oauth]');

    function startOAuth(provider) {
        const url = APP_BASE + (provider === 'github' ? '/auth/github' : '/auth/google');
        oauthNote.textContent = `Opening ${provider === 'github' ? 'GitHub' : 'Google'} sign-in…`;
        oauthButtons.forEach((b) => { b.disabled = true; });

        const w = window.open(url, 'youextractor_oauth', 'width=520,height=680,popup=yes,scrollbars=yes');
        if (!w) {
            // Pop-up was blocked — fall back to a plain new tab; the normal
            // auth poll picks the login up automatically.
            oauthNote.textContent = 'Pop-up blocked — signed in on a new tab? The panel will detect it.';
            oauthButtons.forEach((b) => { b.disabled = false; });
            window.open(url, '_blank');
            return;
        }

        oauthWindow = w;
        stopAuthPoll();
        clearTimeout(oauthPollTimer);
        oauthPollTimer = setInterval(async () => {
            if (!panelOpen) { clearInterval(oauthPollTimer); oauthWindow = null; oauthButtons.forEach((b) => { b.disabled = false; }); return; }
            // User closed the popup without finishing — drop back to normal polling.
            if (oauthWindow && oauthWindow.closed) {
                clearInterval(oauthPollTimer);
                oauthWindow = null;
                oauthNote.textContent = '';
                oauthButtons.forEach((b) => { b.disabled = false; });
                startAuthPoll();
                return;
            }
            const res = await sendMessage({ type: 'checkAuth' });
            if (!panelOpen) return;
            if (res.ok && res.authenticated) {
                clearInterval(oauthPollTimer);
                oauthWindow = null;
                oauthButtons.forEach((b) => { b.disabled = false; });
                oauthNote.textContent = '';
                try { if (w && !w.closed) w.close(); } catch (err) { /* popup already gone */ }
                currentUser = { name: res.name, email: res.email, avatar: res.avatar };
                currentLastUsed = res.lastUsed || (provider === 'github' ? 'github' : 'google');
                updateHeader();
                stopAuthPoll();
                syncReadyView();
                showView('ready');
            }
        }, 1500);
    }

    oauthButtons.forEach((btn) => {
        btn.addEventListener('click', () => startOAuth(btn.dataset.yexOauth));
    });

    // Sign In form
    const signinForm = viewAuth.querySelector('[data-yex-form="signin"]');
    signinForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearAuthError(signinForm);
        const btn = signinForm.querySelector('button[type="submit"]');
        const email = signinForm.querySelector('#yex-si-email').value.trim();
        const password = signinForm.querySelector('#yex-si-password').value;
        if (!email || !password) {
            showAuthError(signinForm, 'Please enter your email and password.');
            return;
        }
        btn.disabled = true;
        const label = btn.querySelector('.yex-btn-label') || btn;
        const original = label.textContent;
        label.textContent = 'Signing in…';
        const remember = signinForm.querySelector('#yex-si-remember').checked;
        const res = await sendMessage({ type: 'login', email, password, remember });
        btn.disabled = false;
        label.textContent = original;
        if (!panelOpen) return;
        if (res.ok) {
            currentUser = { name: res.name, email: res.email, avatar: res.avatar };
            currentLastUsed = 'email';
            updateHeader();
            stopAuthPoll();
            syncReadyView();
            showView('ready');
        } else {
            showAuthError(signinForm, res.error || 'Sign in failed. Please try again.');
        }
    });

    // Sign Up form
    const signupForm = viewAuth.querySelector('[data-yex-form="signup"]');
    signupForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearAuthError(signupForm);
        const btn = signupForm.querySelector('button[type="submit"]');
        const name = signupForm.querySelector('#yex-su-name').value.trim();
        const email = signupForm.querySelector('#yex-su-email').value.trim();
        const password = signupForm.querySelector('#yex-su-password').value;
        const confirm = signupForm.querySelector('#yex-su-confirm').value;
        if (!name || !email || !password) {
            showAuthError(signupForm, 'Please fill in all fields.');
            return;
        }
        if (password.length < 8) {
            showAuthError(signupForm, 'Password must be at least 8 characters.');
            return;
        }
        if (password !== confirm) {
            showAuthError(signupForm, 'Passwords do not match.');
            return;
        }
        btn.disabled = true;
        const original = btn.textContent;
        btn.textContent = 'Creating account…';
        const res = await sendMessage({ type: 'register', name, email, password, passwordConfirmation: confirm });
        btn.disabled = false;
        btn.textContent = original;
        if (!panelOpen) return;
        if (res.ok) {
            currentUser = { name: res.name, email: res.email, avatar: res.avatar };
            currentLastUsed = 'email';
            updateHeader();
            stopAuthPoll();
            syncReadyView();
            showView('ready');
        } else {
            showAuthError(signupForm, res.error || 'Could not create your account. Please try again.');
        }
    });

    async function handleLogout() {
        await sendMessage({ type: 'logout' });
        currentUser = null;
        updateHeader();
        clearTimeout(statusPollTimer);
        showView('auth');
        startAuthPoll();
    }

    // ------------------------------------------------------------------
    // Extraction
    // ------------------------------------------------------------------
    const extractBtn = document.createElement('button');
    extractBtn.className = 'yex-btn yex-btn-primary';
    extractBtn.type = 'button';
    extractBtn.innerHTML = '<span>🚀</span><span>Extract Code &amp; Guide</span>';
    extractBtn.addEventListener('click', startExtraction);
    viewReady.appendChild(extractBtn);

    const readyNote = document.createElement('div');
    readyNote.className = 'yex-note';
    readyNote.textContent = 'One click → full project + AI guide. Stays right here on YouTube.';
    viewReady.appendChild(readyNote);

    async function startExtraction() {
        if (!videoId) return;
        stopAuthPoll();
        setProgress(0);
        setExtractStatus('Starting extraction…');
        showView('extracting');

        const res = await sendMessage({ type: 'extractVideo', url: location.href });
        if (!panelOpen) return;
        if (!res.ok) {
            if (res.error === 'unauthorized') {
                currentUser = null;
                updateHeader();
                showView('auth');
                startAuthPoll();
                return;
            }
            showError(res.error || 'Extraction failed. Please try again.');
            return;
        }
        const video = res.video;
        if (video && video.extraction_status === 'completed') {
            renderDone(video);
            return;
        }
        if (video && video.id) {
            setExtractStatus('Extraction queued — generating your project…');
            pollStatus(video.id, false);
        } else {
            showError('Unexpected response from the server.');
        }
    }

    function setProgress(pct) {
        const bar = viewExtracting.querySelector('.yex-progress-bar');
        if (bar) bar.style.width = `${Math.max(4, Math.min(96, pct))}%`;
    }

    function setExtractStatus(text) {
        const el = viewExtracting.querySelector('.yex-status');
        const spinnerText = viewExtracting.querySelector('.yex-spinner-text');
        if (el) el.textContent = text;
        if (spinnerText) spinnerText.textContent = text;
    }

    async function pollStatus(videoIdToPoll, retried) {
        clearTimeout(statusPollTimer);
        const res = await sendMessage({ type: 'videoStatus', id: videoIdToPoll });
        if (!panelOpen) return;
        if (!res.ok) {
            if (res.error === 'unauthorized') {
                currentUser = null;
                updateHeader();
                showView('auth');
                startAuthPoll();
                return;
            }
            showError(res.error || 'Status check failed.');
            return;
        }
        switch (res.status) {
            case 'completed':
                setProgress(100);
                if (res.video) {
                    renderDone(res.video);
                } else if (!retried) {
                    // Payload can lag one tick behind the status flip — fetch once more.
                    pollStatus(videoIdToPoll, true);
                } else {
                    showError('Extraction completed but results could not be loaded.');
                }
                break;
            case 'failed':
                showError(res.error || 'Extraction failed. The video may not have code, or the AI service hit a quota limit.');
                break;
            default:
                setProgress((res.status === 'processing') ? 65 : 30);
                setExtractStatus(statusMessage(res.status));
                statusPollTimer = setTimeout(() => pollStatus(videoIdToPoll, false), 3000);
        }
    }

    function statusMessage(status) {
        switch (status) {
            case 'pending': return 'Queued — fetching transcript…';
            case 'processing': return 'Analyzing transcript & extracting code files…';
            case 'completed': return 'Done!';
            default: return `Status: ${status}`;
        }
    }

    // ------------------------------------------------------------------
    // Results rendering
    // ------------------------------------------------------------------
    let currentFiles = [];
    let currentFileIndex = 0;

    function renderDone(video) {
        currentFiles = video.code_snippets || [];
        currentFileIndex = 0;

        const stack = video.tech_stack || {};
        let stackChips = '';
        if (stack.primary) stackChips += `<span class="yex-stack-chip yex-stack-primary">${escapeHtml(stack.primary)}</span>`;
        (stack.frameworks || []).forEach((fw) => {
            stackChips += `<span class="yex-stack-chip yex-stack-fw">${escapeHtml(fw)}</span>`;
        });

        viewDone.innerHTML = `
            <div class="yex-result-title"></div>
            <div class="yex-summary"></div>
            <div class="yex-stack-row">${stackChips || ''}<span class="yex-file-count">${currentFiles.length} file${currentFiles.length === 1 ? '' : 's'} extracted</span></div>`;

        viewDone.querySelector('.yex-result-title').textContent = video.title || 'Extraction complete';
        viewDone.querySelector('.yex-summary').textContent =
            video.summary && video.summary !== 'Pending extraction...' ? video.summary : 'Your complete codebase is ready. Open the workspace for the full guide, roadmap and AI tutor.';

        if (currentFiles.length > 0) {
            const list = document.createElement('div');
            list.className = 'yex-files';
            currentFiles.forEach((file, idx) => {
                const name = file.path || file.filename || 'file';
                const ext = fileExt(name);
                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'yex-file-row' + (idx === 0 ? ' active' : '');
                row.innerHTML = `
                    <span class="yex-file-dot" style="background:${fileColor(ext)}"></span>
                    <span class="yex-file-name">${escapeHtml(name)}</span>
                    <span class="yex-file-ext">${escapeHtml(ext.toUpperCase() || '')}</span>`;
                row.addEventListener('click', () => selectFile(idx, list));
                list.appendChild(row);
            });
            viewDone.appendChild(list);

            const viewer = document.createElement('div');
            viewer.className = 'yex-code-viewer';
            viewer.innerHTML = `
                <div class="yex-code-head">
                    <span class="yex-code-path"></span>
                    <button class="yex-code-copy" type="button">Copy</button>
                </div>
                <pre class="yex-code-pre"></pre>`;
            viewDone.appendChild(viewer);

            viewer.querySelector('.yex-code-copy').addEventListener('click', () => {
                const code = currentFiles[currentFileIndex] && (currentFiles[currentFileIndex].code || '');
                navigator.clipboard.writeText(code).then(() => {
                    const b = viewer.querySelector('.yex-code-copy');
                    b.textContent = 'Copied ✓';
                    setTimeout(() => { b.textContent = 'Copy'; }, 1500);
                }).catch(() => {});
            });

            selectFile(0, list, viewer);
        }

        const actions = document.createElement('div');
        actions.className = 'yex-actions';
        const row1 = document.createElement('div');
        row1.className = 'yex-row';
        const openWorkspace = document.createElement('button');
        openWorkspace.type = 'button';
        openWorkspace.className = 'yex-btn yex-btn-primary';
        openWorkspace.textContent = 'Open Full Workspace';
        openWorkspace.addEventListener('click', () => window.open(`${APP_BASE}/dashboard?v=${video.id}`, '_blank'));
        const download = document.createElement('button');
        download.type = 'button';
        download.className = 'yex-btn yex-btn-secondary';
        download.textContent = 'Download ZIP';
        download.addEventListener('click', () => window.open(`${APP_BASE}/api/videos/${video.id}/download`, '_blank'));
        row1.append(openWorkspace, download);

        const reExtract = document.createElement('button');
        reExtract.type = 'button';
        reExtract.className = 'yex-btn yex-btn-ghost';
        reExtract.textContent = '↻ Extract Again';
        reExtract.addEventListener('click', () => {
            syncReadyView();
            showView('ready');
        });
        actions.append(row1, reExtract);
        viewDone.appendChild(actions);

        const note = document.createElement('div');
        note.className = 'yex-note';
        note.textContent = 'The full workspace opens in a new tab — your video keeps playing here.';
        viewDone.appendChild(note);

        showView('done');
    }

    function selectFile(idx, list, viewer) {
        currentFileIndex = idx;
        if (list) {
            list.querySelectorAll('.yex-file-row').forEach((r, i) => r.classList.toggle('active', i === idx));
        }
        if (!viewer) viewer = viewDone.querySelector('.yex-code-viewer');
        if (!viewer) return;
        const file = currentFiles[idx];
        if (!file) return;
        const name = file.path || file.filename || 'file';
        const ext = fileExt(name);
        viewer.querySelector('.yex-code-path').textContent = name;
        const pre = viewer.querySelector('.yex-code-pre');
        pre.innerHTML = highlightCode(file.code || '', ext) || '&nbsp;';
    }

    // ------------------------------------------------------------------
    // Popup coordination
    // ------------------------------------------------------------------
    chrome.runtime.onMessage.addListener((message, _sender, sendResponse) => {
        if (message && message.type === 'openPanel') {
            if (videoId) openPanel();
            sendResponse({ ok: true });
        }
        return false;
    });

    // ------------------------------------------------------------------
    // YouTube SPA navigation handling
    // ------------------------------------------------------------------
    function handleNavigation() {
        const newId = getVideoId();
        if (newId !== videoId) {
            videoId = newId;
            videoTitle = getVideoTitle();
            if (videoId) {
                fab.style.display = panelOpen ? 'none' : 'flex';
                syncReadyView();
                if (panelOpen) {
                    clearTimeout(statusPollTimer);
                    stopAuthPoll();
                    refreshAuth();
                }
            } else {
                fab.style.display = 'none';
                if (panelOpen) closePanel();
            }
        } else if (videoId) {
            const t = getVideoTitle();
            if (t && t !== videoTitle) {
                videoTitle = t;
                syncReadyView();
            }
        }
    }

    document.addEventListener('yt-navigate-finish', handleNavigation);
    window.addEventListener('popstate', handleNavigation);
    setInterval(handleNavigation, 1200);

    // ------------------------------------------------------------------
    // Initial run
    // ------------------------------------------------------------------
    document.body.appendChild(host);
    handleNavigation();
    if (!videoId) fab.style.display = 'none';
})();
