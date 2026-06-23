<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <script async src="https://aromatic-caribou-889.convex.site/api/a/am_qYeSPvXGoob8W5b-"></script>
    <title>YouTube Code Extractor - Learn Programming Faster</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css">
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <!-- Highlight.js for Syntax Highlighting -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css">
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
            background: rgba(255, 255, 255, 0.7);
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
            padding: var(--theme-spacing-4) var(--theme-spacing-6);
        }

        @media (min-width: 640px) {
            .header-content {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                height: 80px;
                padding: 0 var(--theme-spacing-6);
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
            background: rgba(244, 244, 245, 0.5);
            padding: var(--theme-spacing-2) var(--theme-spacing-4);
            border-radius: var(--theme-radius-full);
            border: 1px solid var(--ds-border-subtle);
        }

        .avatar-img {
            width: 24px;
            height: 24px;
            border-radius: var(--theme-radius-full);
            border: 1px solid rgba(20, 184, 166, 0.3);
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
            color: var(--ds-text-primary);
        }

        /* Main layout */
        main.container {
            flex: 1;
            padding: var(--theme-spacing-20) var(--theme-spacing-6) var(--theme-spacing-24);
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-12);
        }

        .hero-title-section {
            text-align: center;
            max-width: 768px;
            margin: 0 auto;
            padding-top: 10vh;
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
            border: 4px solid rgba(20, 184, 166, 0.1);
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
            gap: var(--theme-spacing-8);
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

        .icon-brand { background: rgba(20, 184, 166, 0.1); }
        .icon-accent { background: rgba(245, 158, 11, 0.1); }
        .icon-electric { background: rgba(56, 189, 248, 0.1); }
        .icon-success { background: rgba(34, 197, 94, 0.1); }

        .feature-item-title {
            color: var(--ds-text-primary);
            margin: 0 0 var(--theme-spacing-3);
        }

        .feature-item-desc {
            color: var(--ds-text-secondary);
            margin: 0;
            line-height: var(--theme-line-height-relaxed);
        }

        /* Dashboard Stats additions */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--theme-spacing-6);
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-5);
            background: var(--ds-surface-glass);
            border: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-5) var(--theme-spacing-6);
            border-radius: var(--theme-radius-2xl);
            transition: all var(--theme-motion-fast);
        }

        .stat-card:hover {
            border-color: rgba(20, 184, 166, 0.2);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--theme-radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: var(--theme-font-size-xl);
            font-weight: var(--theme-font-weight-bold);
            color: var(--ds-text-primary);
            line-height: 1.2;
        }

        .stat-label {
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-secondary);
            text-transform: uppercase;
            letter-spacing: var(--theme-letter-spacing-wider);
        }

        /* Quick library links styling */
        .grid-half {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--theme-spacing-4);
        }

        /* Library polish + micro animations */
        .grid-half ds-card[interactive] {
            transition: transform var(--theme-motion-fast), box-shadow var(--theme-motion-fast), border-color var(--theme-motion-fast);
            cursor: pointer;
        }
        .grid-half ds-card[interactive]:hover {
            transform: translateY(-3px);
            border-color: rgba(20, 184, 166, 0.25);
            box-shadow: 0 10px 30px -12px rgba(0, 0, 0, 0.35);
        }
        .grid-half ds-card[interactive] .ds-type-heading-sm {
            transition: color var(--theme-motion-fast);
        }
        .grid-half ds-card[interactive]:hover .ds-type-heading-sm {
            color: var(--ds-text-brand);
        }

        .library-card-enter {
            opacity: 0;
            transform: translateY(8px);
            animation: libraryCardIn 280ms var(--theme-ease-out) forwards;
        }

        @keyframes libraryCardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card {
            transition: transform var(--theme-motion-fast), border-color var(--theme-motion-fast);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(20, 184, 166, 0.18);
        }

        /* Workspace Layout - split view */
        .workspace-layout {
            display: grid;
            grid-template-columns: 1fr;
            background: var(--ds-surface-glass);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-2xl);
            overflow: hidden;
            margin-top: var(--theme-spacing-6);
        }

        @media (min-width: 1024px) {
            .workspace-layout {
                grid-template-columns: 280px 1fr;
                min-height: 650px;
            }
        }

        .workspace-sidebar {
            background: rgba(244, 244, 245, 0.7);
            border-bottom: 1px solid var(--ds-border-subtle);
            display: flex;
            flex-direction: column;
            padding: var(--theme-spacing-5);
            gap: var(--theme-spacing-5);
        }

        @media (min-width: 1024px) {
            .workspace-sidebar {
                border-bottom: none;
                border-right: 1px solid var(--ds-border-subtle);
            }
        }

        .workspace-sidebar-header {
            border-bottom: 1px solid var(--ds-border-subtle);
            padding-bottom: var(--theme-spacing-3);
        }

        .workspace-sidebar-header h4 {
            margin: 0;
            color: var(--ds-text-primary);
        }

        .workspace-sidebar-header p {
            margin: var(--theme-spacing-1) 0 0;
            color: var(--ds-text-muted);
        }

        .workspace-nav-list {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            gap: var(--theme-spacing-1);
            padding-bottom: var(--theme-spacing-2);
        }

        @media (min-width: 1024px) {
            .workspace-nav-list {
                flex-direction: column;
                overflow-x: visible;
                padding-bottom: 0;
            }
        }

        .ws-nav-btn {
            background: transparent;
            border: none;
            color: var(--ds-text-secondary);
            font-family: var(--theme-font-sans);
            font-weight: var(--theme-font-weight-semibold);
            font-size: var(--theme-font-size-sm);
            padding: var(--theme-spacing-3) var(--theme-spacing-4);
            cursor: pointer;
            border-radius: var(--theme-radius-xl);
            transition: all var(--theme-motion-fast) var(--theme-ease-default);
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
            white-space: nowrap;
            text-align: left;
            width: 100%;
        }

        .ws-nav-btn:hover {
            color: var(--ds-text-primary);
            background: rgba(0, 0, 0, 0.03);
        }

        .ws-nav-btn.ws-nav-active {
            background: var(--ds-color-brand-muted);
            color: var(--ds-text-brand);
        }

        @media (min-width: 1024px) {
            .ws-nav-btn.ws-nav-active {
                border-left: 3px solid var(--ds-color-brand);
                border-top-left-radius: 0;
                border-bottom-left-radius: 0;
            }
        }

        .workspace-sidebar-footer {
            margin-top: auto;
            padding-top: var(--theme-spacing-4);
            border-top: 1px solid var(--ds-border-subtle);
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-2);
        }

        .workspace-content {
            padding: var(--theme-spacing-6);
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .ws-panel {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
            animation: fadeIn 0.4s var(--theme-ease-out);
            height: 100%;
        }

        /* Checklist Roadmap Styles */
        .roadmap-checklist-container {
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-3);
            margin-top: var(--theme-spacing-2);
        }

        .roadmap-item {
            display: flex;
            align-items: flex-start;
            gap: var(--theme-spacing-3);
            background: rgba(0, 0, 0, 0.01);
            border: 1px solid var(--ds-border-subtle);
            padding: var(--theme-spacing-4);
            border-radius: var(--theme-radius-xl);
            cursor: pointer;
            transition: all var(--theme-motion-fast);
        }

        .roadmap-item:hover {
            background: rgba(0, 0, 0, 0.03);
            border-color: var(--ds-border-default);
        }

        .roadmap-item.completed {
            border-color: rgba(34, 197, 94, 0.2);
            background: rgba(34, 197, 94, 0.02);
        }

        .roadmap-checkbox {
            width: 20px;
            height: 20px;
            border-radius: 6px;
            border: 2px solid var(--ds-border-default);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
            transition: all var(--theme-motion-fast);
            flex-shrink: 0;
        }

        .roadmap-item.completed .roadmap-checkbox {
            border-color: var(--ds-color-success);
            background: var(--ds-color-success);
            color: white;
        }

        .roadmap-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .roadmap-title {
            font-weight: var(--theme-font-weight-semibold);
            color: var(--ds-text-primary);
            font-size: var(--theme-font-size-sm);
            margin: 0;
        }

        .roadmap-item.completed .roadmap-title {
            color: var(--ds-text-muted);
            text-decoration: line-through;
        }

        .roadmap-desc {
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-secondary);
        }

        /* File explorer layout */
        .explorer-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--theme-spacing-4);
            height: 100%;
        }

        @media (min-width: 768px) {
            .explorer-layout {
                grid-template-columns: 240px 1fr;
            }
        }

        .explorer-sidebar {
            background: rgba(0, 0, 0, 0.02);
            border-radius: var(--theme-radius-xl);
            padding: var(--theme-spacing-4);
            border: 1px solid var(--ds-border-subtle);
            max-height: 520px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .explorer-sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--theme-spacing-2);
        }

        .explorer-file-count {
            font-size: 10px;
            padding: 1px 7px;
            background: rgba(0,0,0,0.04);
            border-radius: 999px;
            color: var(--ds-text-muted);
        }

        .explorer-search {
            width: 100%;
            background: rgba(255,255,255,0.8);
            border: 1px solid var(--ds-border-subtle);
            color: var(--ds-text-primary);
            font-size: 11px;
            padding: 5px 8px;
            border-radius: var(--theme-radius-md);
            margin-bottom: var(--theme-spacing-2);
            font-family: var(--theme-font-sans);
        }

        .explorer-search:focus {
            outline: none;
            border-color: var(--ds-border-accent);
            background: rgba(0,0,0,0.4);
        }

        .explorer-file-list {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .explorer-file-btn {
            background: transparent;
            border: none;
            color: var(--ds-text-secondary);
            font-family: var(--theme-font-mono);
            font-size: 11.5px;
            padding: 5px 8px;
            cursor: pointer;
            border-radius: var(--theme-radius-md);
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: all var(--theme-motion-fast);
            width: 100%;
            position: relative;
        }

        .explorer-file-btn::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 0;
            background: var(--ds-color-brand);
            border-radius: 2px;
            transition: width var(--theme-motion-fast);
        }

        .explorer-file-btn:hover {
            color: var(--ds-text-primary);
            background: rgba(0, 0, 0, 0.025);
        }

        .explorer-file-btn:hover::before {
            width: 2.5px;
        }

        .explorer-file-btn.file-active {
            background: rgba(20, 184, 166, 0.12);
            color: var(--ds-text-brand);
            font-weight: 500;
        }

        .explorer-file-btn.file-active::before {
            width: 3px;
            background: var(--ds-color-brand);
        }

        .explorer-file-btn .file-icon {
            opacity: 0.75;
            flex-shrink: 0;
            font-size: 13px;
        }

        .explorer-file-btn.file-active .file-icon {
            opacity: 1;
        }

        .explorer-file-btn .file-name {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .explorer-file-btn .file-ext {
            font-size: 9px;
            opacity: 0.5;
            font-family: var(--theme-font-sans);
            background: rgba(0,0,0,0.04);
            padding: 0 4px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        .explorer-viewer {
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.4);
            border: 1px solid var(--ds-border-subtle);
            border-radius: var(--theme-radius-xl);
            overflow: hidden;
            height: 100%;
        }

        .viewer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--theme-spacing-3) var(--theme-spacing-4);
            background: rgba(244, 244, 245, 0.8);
            border-bottom: 1px solid var(--ds-border-subtle);
        }

        .viewer-meta {
            display: flex;
            align-items: center;
            gap: var(--theme-spacing-3);
        }

        .code-pre {
            margin: 0;
            padding: var(--theme-spacing-5);
            overflow-x: auto;
            font-size: var(--theme-font-size-sm);
            background: transparent;
        }

        .code-path {
            font-family: var(--theme-font-mono);
            font-size: var(--theme-font-size-sm);
            color: var(--ds-text-primary);
        }

        .code-description-footer {
            padding: var(--theme-spacing-3) var(--theme-spacing-5);
            border-top: 1px solid var(--ds-border-subtle);
            background: rgba(244, 244, 245, 0.4);
            font-size: var(--theme-font-size-xs);
            color: var(--ds-text-muted);
        }

        /* Chat Panel */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 480px;
            border: 1px solid var(--ds-border-subtle);
            background: rgba(255, 255, 255, 0.4);
            border-radius: var(--theme-radius-xl);
            overflow: hidden;
        }

        .chat-history {
            flex: 1;
            overflow-y: auto;
            padding: var(--theme-spacing-4);
            display: flex;
            flex-direction: column;
            gap: var(--theme-spacing-4);
        }

        .chat-bubble {
            max-width: 85%;
            padding: var(--theme-spacing-3) var(--theme-spacing-4);
            border-radius: var(--theme-radius-xl);
            line-height: var(--theme-line-height-relaxed);
            font-size: var(--theme-font-size-sm);
            box-sizing: border-box;
        }

        .chat-bubble-user {
            align-self: flex-end;
            background: var(--ds-color-brand-muted);
            color: var(--ds-text-brand);
            border-bottom-right-radius: var(--theme-radius-sm);
            border: 1px solid rgba(20, 184, 166, 0.2);
        }

        .chat-bubble-ai {
            align-self: flex-start;
            background: rgba(255, 255, 255, 0.7);
            color: var(--ds-text-primary);
            border-bottom-left-radius: var(--theme-radius-sm);
            border: 1px solid var(--ds-border-subtle);
        }

        .chat-bubble-ai p {
            margin: 0 0 var(--theme-spacing-2);
        }
        .chat-bubble-ai p:last-child {
            margin-bottom: 0;
        }

        .chat-bubble-ai pre {
            background: rgba(244, 244, 245, 0.8);
            padding: var(--theme-spacing-3);
            border-radius: var(--theme-radius-lg);
            overflow-x: auto;
            margin: var(--theme-spacing-2) 0;
            border: 1px solid var(--ds-border-subtle);
        }

        .chat-bubble-ai code {
            font-family: var(--theme-font-mono);
            font-size: var(--theme-font-size-xs);
        }

        .chat-input-area {
            display: flex;
            gap: var(--theme-spacing-3);
            padding: var(--theme-spacing-4);
            border-top: 1px solid var(--ds-border-subtle);
            background: rgba(244, 244, 245, 0.8);
        }

        .chat-input-wrapper {
            flex: 1;
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: var(--theme-spacing-2) var(--theme-spacing-3);
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            background: var(--ds-text-muted);
            border-radius: 50%;
            animation: typingBounce 1.4s infinite ease-in-out both;
        }

        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }

        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1.0); }
        }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
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

        /* General spacing helpers */
        .space-y-12 > * + * { margin-top: var(--theme-spacing-12); }
        .space-y-10 > * + * { margin-top: var(--theme-spacing-10); }
        .space-y-8 > * + * { margin-top: var(--theme-spacing-8); }
        .space-y-6 > * + * { margin-top: var(--theme-spacing-6); }
        .space-y-4 > * + * { margin-top: var(--theme-spacing-4); }
        .space-y-3 > * + * { margin-top: var(--theme-spacing-3); }

        .hidden {
            display: none !important;
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
            background: rgba(244, 244, 245, 0.9);
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
                    <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width:28px;height:28px;border-radius:5px;object-fit:cover;border:1px solid rgba(20,184,166,0.25);box-shadow:0 1px 2px rgba(0,0,0,0.2);">
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
            <div id="hero-title-section" class="hero-title-section" style="animation: fadeSlideUp 420ms var(--theme-ease-out);">
                <h1 class="ds-type-display-sm hero-title">Learn Faster, Code Smarter</h1>
                <p class="ds-type-body-lg hero-desc">
                    Paste any programming tutorial URL and get all the code snippets, 
                    <span style="color: var(--ds-text-brand)">complete setup guides</span>, 
                    <span style="color: var(--ds-text-accent)">IDE recommendations</span>, and 
                    <span style="color: var(--ds-text-electric)">step-by-step instructions</span>.
                </p>
                <p style="margin-top:4px; font-size:var(--theme-font-size-xs); color:var(--ds-text-muted);">New: AI tutor chat, <strong>one-click GitHub repo creation</strong>, and interactive roadmaps in every workspace.</p>
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
                                variant="primary"
                                size="lg"
    
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

            <!-- Dashboard Stats & Library (Visible when not actively inside workspace) -->
            <div id="dashboardStats" class="space-y-12">
                <!-- Stats Row -->
                <div class="stats-grid" id="statsGrid">
                    <!-- Stat 1 -->
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(20, 184, 166, 0.1); color: var(--ds-text-brand);">
                            <i class="ph ph-film-script"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="stat-total-videos">0</span>
                            <span class="stat-label">Extractions</span>
                        </div>
                    </div>
                    <!-- Stat 2 -->
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(56, 189, 248, 0.1); color: var(--ds-text-electric);">
                            <i class="ph ph-file-code"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="stat-total-files">0</span>
                            <span class="stat-label">Files Generated</span>
                        </div>
                    </div>
                    <!-- Stat 3 -->
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--ds-text-accent);">
                            <i class="ph ph-git-branch"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="stat-total-repos">0</span>
                            <span class="stat-label">GitHub Repos</span>
                        </div>
                    </div>
                    <!-- Stat 4 -->
                    <div class="stat-card">
                        <div class="stat-icon" style="background: rgba(34, 197, 94, 0.1); color: var(--ds-color-success-subtle);">
                            <i class="ph ph-clock-countdown"></i>
                        </div>
                        <div class="stat-info">
                            <span class="stat-number" id="stat-time-saved">0h</span>
                            <span class="stat-label">Est. Time Saved</span>
                        </div>
                    </div>
                </div>

                <!-- Learning Library -->
                <div class="space-y-4">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--theme-spacing-4);">
                        <div>
                            <h2 class="ds-type-heading-md" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="ph ph-books" style="color: var(--ds-text-brand);"></i> Your Learning Library
                            </h2>
                            <p class="ds-type-body-sm" style="margin: 4px 0 0 0; color: var(--ds-text-secondary);">Select any video extraction to open its dedicated interactive workspace.</p>
                        </div>
                        <div style="width: 100%; max-width: 340px;">
                            <ds-input 
                                type="text" 
                                id="librarySearchInput" 
                                placeholder="Search library..."
                                icon="magnifying-glass"
                                size="sm">
                            </ds-input>
                        </div>
                    </div>

                    <!-- Library Grid -->
                    <div id="libraryContainer" class="grid-half">
                        <!-- Loaded dynamically -->
                        <div style="grid-column: 1 / -1; text-align: center; padding: var(--theme-spacing-8) 0; color: var(--ds-text-muted);">
                            <i class="ph ph-spinner spin-icon" style="font-size: 1.5rem; animation: spin 1s linear infinite;"></i>
                            <p style="margin-top: 8px;">Loading your library...</p>
                        </div>
                    </div>
                </div>

                <!-- Features Info Walkthrough (Long Page Feature) -->
                <div class="space-y-6" style="padding-top: var(--theme-spacing-12); border-top: 1px solid var(--ds-border-subtle);">
                    <h3 class="ds-type-heading-sm" style="text-align: center; margin-bottom: var(--theme-spacing-8);">Features Walkthrough</h3>
                    <div class="features-grid">
                        <ds-card variant="glass" padding="lg">
                            <div class="feature-icon-box icon-brand">
                                <i class="ph ph-target" style="color: var(--ds-text-brand); font-size: 1.25rem;"></i>
                            </div>
                            <h3 class="ds-type-heading-sm feature-item-title">Roadmap Checklist</h3>
                            <p class="ds-type-body-sm feature-item-desc">Follow a tailored learning path step-by-step and track your progress locally.</p>
                        </ds-card>
                        
                        <ds-card variant="glass" padding="lg">
                            <div class="feature-icon-box icon-accent">
                                <i class="ph ph-laptop" style="color: var(--ds-text-accent); font-size: 1.25rem;"></i>
                            </div>
                            <h3 class="ds-type-heading-sm feature-item-title">IDE Recommendations</h3>
                            <p class="ds-type-body-sm feature-item-desc">Install prerequisites and configure recommended IDEs and plugins easily.</p>
                        </ds-card>

                        <ds-card variant="glass" padding="lg">
                            <div class="feature-icon-box icon-electric">
                                <i class="ph ph-chat-circle-dots" style="color: var(--ds-text-electric); font-size: 1.25rem;"></i>
                            </div>
                            <h3 class="ds-type-heading-sm feature-item-title">AI Copilot Chat</h3>
                            <p class="ds-type-body-sm feature-item-desc">Ask specific questions about the code, setup, or concepts in real-time.</p>
                        </ds-card>

                        <ds-card variant="glass" padding="lg">
                            <div class="feature-icon-box icon-success">
                                <i class="ph ph-download-simple" style="color: var(--ds-color-success-subtle); font-size: 1.25rem;"></i>
                            </div>
                            <h3 class="ds-type-heading-sm feature-item-title">ZIP & GitHub Export</h3>
                            <p class="ds-type-body-sm feature-item-desc">Download the full project as ZIP, or <strong>one-click push to a new GitHub repo</strong> with README, .gitignore, and structure intact.</p>
                        </ds-card>
                    </div>
                </div>
            </div>

            <!-- Workspace Results Section -->
            <div id="results" class="hidden"></div>
        </main>

        @include('partials.footer')
    </div>

    <!-- Design System Scripts -->
    <script src="/js/youextractor-design-system.js?v=3"></script>

    <script>
        const form = document.getElementById('videoForm');
        const youtubeUrl = document.getElementById('youtubeUrl');
        const resultsContainer = document.getElementById('results');
        const dashboardStats = document.getElementById('dashboardStats');
        const heroTitleSection = document.getElementById('hero-title-section');
        const errorDiv = document.getElementById('error');
        const errorText = document.getElementById('errorText');
        const loadingDiv = document.getElementById('loading');
        const loadingText = document.getElementById('loadingText');
        const submitBtn = document.getElementById('submitBtn');

        // Support prefill from demo CTA / Chrome extension / auth redirect
        (function handlePrefillFromDemo() {
            const params = new URLSearchParams(window.location.search);
            const prefill = params.get('youtube_url') || params.get('url');
            if (prefill && youtubeUrl) {
                youtubeUrl.value = prefill;
                
                // Show a friendly banner above the form
                const banner = document.createElement('div');
                banner.className = 'ds-badge-brand';
                banner.style.cssText = 'margin-bottom:12px; width:100%; justify-content:center; font-size:12px;';
                banner.innerHTML = `<i class="ph ph-magic-wand" style="margin-right:6px;"></i> Demo extraction ready — hit Extract to continue`;
                
                const formWrapper = document.querySelector('#videoForm')?.parentElement || document.querySelector('.extraction-form-wrapper')?.parentElement;
                if (formWrapper && formWrapper.parentElement) {
                    formWrapper.parentElement.insertBefore(banner, formWrapper);
                }
                
                // Focus the button for one-click action
                setTimeout(() => {
                    if (submitBtn) submitBtn.focus();
                }, 650);
                
                // Clean the URL (optional, keep history clean)
                if (window.history && window.history.replaceState) {
                    const cleanUrl = window.location.pathname;
                    window.history.replaceState({}, document.title, cleanUrl);
                }
            }
        })();

        const libraryContainer = document.getElementById('libraryContainer');
        const librarySearchInput = document.getElementById('librarySearchInput');

        // Stats elements
        const statTotalVideos = document.getElementById('stat-total-videos');
        const statTotalFiles = document.getElementById('stat-total-files');
        const statTotalRepos = document.getElementById('stat-total-repos');
        const statTimeSaved = document.getElementById('stat-time-saved');

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

        let allVideos = [];
        let activeVideo = null;
        let selectedExplorerFileIndex = 0;

        // Check for URL query parameter (for Chrome Extension)
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const url = urlParams.get('url');
            if (url) {
                youtubeUrl.value = url;
                if (url.includes('youtube.com/') || url.includes('youtu.be/')) {
                    submitBtn.click();
                }
            }
            loadLibrary();
        });

        // Fetch user library and render stats & library list
        async function loadLibrary() {
            try {
                const response = await fetch('/api/videos');
                const data = await response.json();
                allVideos = data.data || data || [];
                renderStatsAndLibrary(allVideos);
            } catch (error) {
                console.error('Failed to load library:', error);
                libraryContainer.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--ds-color-error); padding: var(--theme-spacing-6) 0;">
                        <i class="ph ph-warning-circle" style="font-size: 1.5rem;"></i>
                        <p style="margin-top: 8px;">Failed to load library: ${error.message}</p>
                    </div>
                `;
            }
        }

        function renderStatsAndLibrary(videos) {
            // 1. Stats calculation
            const totalVideos = videos.length;
            let totalFiles = 0;
            let totalRepos = 0;
            
            videos.forEach(v => {
                totalFiles += v.code_snippets ? v.code_snippets.length : 0;
                if (v.github_repo_url) totalRepos++;
            });

            // 2 hours saved per tutorial on average
            const timeSaved = totalVideos * 2;

            statTotalVideos.textContent = totalVideos;
            statTotalFiles.textContent = totalFiles;
            statTotalRepos.textContent = totalRepos;
            statTimeSaved.textContent = `${timeSaved}h`;

            // 2. Render library items
            renderLibraryItems(videos);
        }

        function renderLibraryItems(videos) {
            if (videos.length === 0) {
                libraryContainer.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; padding: var(--theme-spacing-8) 0; color: var(--ds-text-secondary);">
                        <i class="ph ph-books" style="font-size: 2.5rem; opacity: 0.3;"></i>
                        <p style="margin-top: 12px; font-weight: 500;">No extractions yet</p>
                        <p style="margin-top: 4px; font-size: var(--theme-font-size-sm); color: var(--ds-text-muted);">Paste a YouTube tutorial link above to extract your first project!</p>
                        <button onclick="document.getElementById('youtubeUrl')?.focus(); document.getElementById('youtubeUrl')?.scrollIntoView({behavior:'smooth', block:'center'});" 
                                style="margin-top:10px; background:transparent; border:1px solid var(--ds-border-subtle); color:var(--ds-text-secondary); font-size:11px; padding:4px 10px; border-radius:6px; cursor:pointer;">
                            Go to input →
                        </button>
                    </div>
                `;
                return;
            }

            libraryContainer.innerHTML = videos.map((video, idx) => {
                const stack = video.tech_stack;
                let stackBadge = '';
                if (stack && stack.primary) {
                    stackBadge = `<span class="ds-badge-electric">${escapeHtml(stack.primary)}</span>`;
                }

                const date = new Date(video.extracted_at || video.created_at).toLocaleDateString();

                return `
                    <ds-card variant="glass" interactive padding="lg" onclick="openWorkspace(${video.id})" class="library-card-enter" style="animation-delay: ${Math.min(idx * 35, 220)}ms">
                        <div style="display: flex; flex-direction: column; height: 100%; justify-content: space-between; gap: var(--theme-spacing-4);">
                            <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: var(--theme-spacing-2);">
                                    <h3 class="ds-type-heading-sm" style="font-size: var(--theme-font-size-md); margin: 0; line-clamp: 2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        ${escapeHtml(video.title)}
                                    </h3>
                                </div>
                                <p class="ds-type-body-sm" style="margin: 0; line-clamp: 2; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: var(--ds-text-secondary);">
                                    ${escapeHtml(video.summary || 'AI extracted tutorial.')}
                                </p>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; font-size: var(--theme-font-size-xs); color: var(--ds-text-muted); border-top: 1px solid var(--ds-border-subtle); padding-top: var(--theme-spacing-3);">
                                <span style="display: flex; align-items: center; gap: 4px;">
                                    <i class="ph ph-calendar"></i> ${date}
                                </span>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    ${stackBadge}
                                    ${video.github_repo_url ? `
                                    <a href="${escapeHtml(video.github_repo_url)}" target="_blank" onclick="event.stopImmediatePropagation(); event.stopPropagation();" title="View on GitHub" style="text-decoration:none; display:flex; align-items:center; gap:3px; background:rgba(20,184,166,0.15); color:var(--ds-text-brand); padding:1px 5px; border-radius:999px; font-size:9px; font-weight:600;">
                                        <i class="ph ph-github-logo"></i> <span style="margin-left:1px;">Repo</span>
                                    </a>` : ''}
                                    <span class="ds-badge-brand" style="white-space: nowrap;"><i class="ph ph-arrow-right" style="margin-right: 0;"></i></span>
                                </div>
                            </div>
                        </div>
                    </ds-card>
                `;
            }).join('');
        }

        // Local library filtering
        librarySearchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            if (!query) {
                renderLibraryItems(allVideos);
                return;
            }

            const filtered = allVideos.filter(v => 
                v.title.toLowerCase().includes(query) || 
                (v.summary && v.summary.toLowerCase().includes(query)) ||
                (v.explanation && v.explanation.toLowerCase().includes(query))
            );
            renderLibraryItems(filtered);
        });

        // Open Workspace View
        function openWorkspace(videoId) {
            const video = allVideos.find(v => v.id === videoId);
            if (!video) return;

            activeVideo = video;
            selectedExplorerFileIndex = 0;

            // Hide stats and library
            dashboardStats.classList.add('hidden');
            heroTitleSection.classList.add('hidden');

            // Show and render workspace inside results
            resultsContainer.classList.remove('hidden');
            displayResults(video);
            
            // Apply highlighting
            document.querySelectorAll('pre code').forEach((block) => {
                hljs.highlightElement(block);
            });

            // Smooth scroll to top of workspace
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Close Workspace View and return to Dashboard stats/library
        function closeWorkspace() {
            activeVideo = null;
            resultsContainer.classList.add('hidden');
            dashboardStats.classList.remove('hidden');
            heroTitleSection.classList.remove('hidden');
            loadLibrary(); // Reload list to reflect any new code extractions or github links
        }

        // Extraction Form handler
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            errorDiv.classList.add('hidden');
            resultsContainer.innerHTML = '';
            resultsContainer.classList.add('hidden');
            dashboardStats.classList.add('hidden');
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

                let completedVideo;
                if (data.data && (data.data.extraction_status === 'pending' || data.data.extraction_status === 'processing')) {
                    completedVideo = await pollExtractionStatus(data.data.id);
                } else {
                    completedVideo = data.data;
                }

                // Add to local allVideos
                const index = allVideos.findIndex(v => v.id === completedVideo.id);
                if (index !== -1) {
                    allVideos[index] = completedVideo;
                } else {
                    allVideos.unshift(completedVideo);
                }

                // Open workspace for this extracted video
                youtubeUrl.value = '';
                openWorkspace(completedVideo.id);

            } catch (error) {
                showError(error.message);
                dashboardStats.classList.remove('hidden');
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

        // Render Workspace Results View
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
                    stackBadges += `<span class="ds-badge-brand" style="margin-right: var(--theme-spacing-2);">${escapeHtml(stack.primary)}</span>`;
                }
                if (stack.frameworks && stack.frameworks.length > 0) {
                    stack.frameworks.forEach(fw => {
                        stackBadges += `<span class="ds-badge-electric" style="margin-right: var(--theme-spacing-2);">${escapeHtml(fw)}</span>`;
                    });
                }
            }

            const html = `
                <div class="space-y-6" style="animation: fadeIn 0.4s var(--theme-ease-out);">
                    
                    <!-- Back Button & Workspace Header Card -->
                    <ds-card variant="glass-accent" padding="md">
                        <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
                            <!-- Top action bar -->
                            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--ds-border-subtle); padding-bottom: var(--theme-spacing-3); flex-wrap: wrap; gap: var(--theme-spacing-2);">
                                <ds-button onclick="closeWorkspace()" label="Back to Dashboard" variant="secondary" size="sm" ></ds-button>
                                
                                <div style="display: flex; gap: var(--theme-spacing-2);">
                                    <a href="https://youtube.com/watch?v=${video.youtube_id}" target="_blank" style="text-decoration: none;">
                                        <ds-button label="Watch on YouTube" variant="ghost" size="sm" icon="youtube-logo"></ds-button>
                                    </a>
                                    <form id="reExtractForm" style="display: inline;">
                                        <ds-button onclick="triggerReExtraction('${video.id}')" label="Re-Extract Video" variant="ghost" size="sm" icon="arrows-counter-clockwise"></ds-button>
                                    </form>
                                </div>
                            </div>

                            <!-- Video title and stack -->
                            <div class="result-info-header">
                                <div style="flex: 1;">
                                    <h2 class="ds-type-heading-md" style="margin: 0;">${escapeHtml(video.title)}</h2>
                                    <div class="result-meta">
                                        ${stackBadges}
                                        ${hasCode ? `<span class="ds-badge-success">${video.code_snippets.length} files</span>` : ''}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </ds-card>

                    <!-- GitHub Push Card -->
                    ${hasCode ? `
                    <ds-card variant="glass" padding="lg" style="border: 1px solid rgba(20, 184, 166, 0.15);">
                        <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
                            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: var(--theme-spacing-4); flex-wrap: wrap;">
                                <div style="flex: 1; min-width: 280px;">
                                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                        <i class="ph ph-github-logo" style="font-size: 1.6rem; color: var(--ds-text-accent);"></i>
                                        <h4 class="ds-type-heading-sm" style="margin: 0;">One-Click GitHub Export</h4>
                                        <span class="ds-badge-brand" style="font-size: 9px; padding: 1px 6px;">RECOMMENDED</span>
                                    </div>
                                    <p class="ds-type-body-sm" style="margin: 0 0 8px; color: var(--ds-text-secondary);">
                                        Automatically create a new repository and push your complete project with full structure, README, and .gitignore.
                                    </p>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr)); gap: 4px 12px; font-size: 11px; color: var(--ds-text-secondary);">
                                        <div style="display:flex; align-items:center; gap:4px;"><i class="ph ph-check" style="color:var(--ds-color-success);"></i> Full folder tree</div>
                                        <div style="display:flex; align-items:center; gap:4px;"><i class="ph ph-check" style="color:var(--ds-color-success);"></i> Shareable link</div>
                                        <div style="display:flex; align-items:center; gap:4px;"><i class="ph ph-check" style="color:var(--ds-color-success);"></i> Version history</div>
                                        <div style="display:flex; align-items:center; gap:4px;"><i class="ph ph-check" style="color:var(--ds-color-success);"></i> Easy collaboration</div>
                                    </div>
                                    <div style="margin-top: 8px; font-size: 11px; color: var(--ds-text-muted);">
                                        Needs a <strong>classic Personal Access Token</strong> with <code>repo</code> scope.
                                        <a href="https://github.com/settings/tokens/new?scopes=repo&description=YouExtractor" target="_blank" style="color: var(--ds-text-brand); text-decoration: underline;">Create one here →</a>
                                    </div>
                                </div>
                                ${video.github_repo_url ? `
                                <div style="display: flex; align-items: center; gap: var(--theme-spacing-3); margin-top: 4px;">
                                    <a href="${escapeHtml(video.github_repo_url)}" target="_blank" style="text-decoration: none;">
                                        <ds-button label="View Repo on GitHub" variant="glow" size="md" icon="arrow-square-out"></ds-button>
                                    </a>
                                </div>
                                ` : `
                                <div id="github-push-form" style="display: flex; flex-direction: column; gap: var(--theme-spacing-3); flex: 1.2; max-width: 540px; min-width: 280px; background: rgba(0,0,0,0.02); padding: var(--theme-spacing-4); border-radius: var(--theme-radius-xl); border: 1px solid var(--ds-border-subtle);">
                                    <ds-input id="github-token-input" value="${localStorage.getItem('github_personal_token') || ''}" placeholder="ghp_xxxxxxxxxxxxxxxx" label="GitHub Personal Access Token" size="sm" style="width:100%;"></ds-input>
                                    
                                    <div style="display: flex; align-items: center; gap: 8px; margin-top: -2px; margin-bottom: 2px;">
                                        <input type="checkbox" id="github-remember-token" ${localStorage.getItem('github_personal_token') ? 'checked' : ''} style="accent-color: var(--ds-color-brand); cursor: pointer;">
                                        <label for="github-remember-token" style="font-size: 11px; color: var(--ds-text-secondary); cursor: pointer; user-select: none;">Save token in this browser</label>
                                    </div>

                                    <!-- Expandable repository configuration -->
                                    <details style="margin-top: var(--theme-spacing-1);">
                                        <summary style="font-size: 11px; color: var(--ds-text-brand); cursor: pointer; font-weight: 500; outline: none; user-select: none; margin-bottom: var(--theme-spacing-2);">
                                            Advanced Repository Settings
                                        </summary>
                                        <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-3); padding-top: var(--theme-spacing-2); border-top: 1px dashed var(--ds-border-subtle);">
                                            <ds-input id="github-repo-name" value="${escapeHtml(sanitiseRepoName(video.title))}" placeholder="repository-name" label="Repository Name" size="sm" style="width:100%;"></ds-input>
                                            <ds-input id="github-repo-desc" value="Extracted from YouTube: https://youtu.be/${video.youtube_id} — by YouExtractor" placeholder="Short description" label="Repository Description" size="sm" style="width:100%;"></ds-input>
                                            
                                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                                <label style="font-size: var(--theme-font-size-sm); font-weight: var(--theme-font-weight-medium); color: var(--ds-text-secondary);">Repository Visibility</label>
                                                <select id="github-repo-privacy" style="background: var(--ds-surface-input); border: 1px solid var(--ds-border-input); border-radius: var(--theme-radius-lg); color: var(--ds-text-primary); padding: 8px 10px; font-size: 12px; outline: none; width: 100%; transition: border-color 0.2s; cursor: pointer;">
                                                    <option value="public" selected>Public (Standard)</option>
                                                    <option value="private">Private (Requires repo scope)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </details>

                                    <div style="margin-top: var(--theme-spacing-2);">
                                        <ds-button id="github-push-btn" onclick="pushToGitHub('${video.id}')" label="Create Repo & Push Code" variant="primary" size="md" icon="git-pull-request" style="width:100%;"></ds-button>
                                    </div>
                                </div>
                                `}
                            </div>
                            <div id="github-status-msg" class="hidden text-sm font-mono" style="margin-top: 2px; padding: 6px 10px; background: rgba(0,0,0,0.05); border-radius: var(--theme-radius-md); color: var(--ds-text-primary);"></div>
                        </div>
                    </ds-card>
                    ` : ''}

                    <!-- Two Column Interactive Workspace -->
                    <div class="workspace-layout">
                        <!-- Sidebar Navigation -->
                        <div class="workspace-sidebar">
                            <div class="workspace-sidebar-header">
                                <h4 class="ds-type-heading-sm" style="font-size: var(--theme-font-size-md);">Study Workstation</h4>
                                <p class="ds-type-body-sm" style="color: var(--ds-text-secondary); margin: 0;">Interactive learning tools</p>
                            </div>
                            
                            <div class="workspace-nav-list">
                                <button onclick="switchWorkspaceTab('roadmap')" id="ws-tab-roadmap" class="ws-nav-btn ws-nav-active">
                                    <i class="ph ph-target"></i> Roadmap Checklist
                                </button>
                                <button onclick="switchWorkspaceTab('explorer')" id="ws-tab-explorer" class="ws-nav-btn">
                                    <i class="ph ph-folder-open"></i> File Explorer
                                </button>
                                <button onclick="switchWorkspaceTab('ide')" id="ws-tab-ide" class="ws-nav-btn">
                                    <i class="ph ph-laptop"></i> IDE & Plugins
                                </button>
                                <button onclick="switchWorkspaceTab('run')" id="ws-tab-run" class="ws-nav-btn">
                                    <i class="ph ph-terminal"></i> Run Commands
                                </button>
                                <button onclick="switchWorkspaceTab('copilot')" id="ws-tab-copilot" class="ws-nav-btn">
                                    <i class="ph ph-chat-circle-dots"></i> Ask AI Copilot
                                </button>
                            </div>

                            ${hasCode ? `
                            <div class="workspace-sidebar-footer">
                                <a href="/api/videos/${video.id}/download" style="text-decoration: none; display: block; width: 100%;">
                                    <ds-button label="Download ZIP" variant="glow" size="md" icon="file-zip" style="width: 100%;"></ds-button>
                                </a>
                            </div>` : ''}
                        </div>

                        <!-- Panel Contents -->
                        <div class="workspace-content">
                            
                            <!-- Roadmap Tab Panel -->
                            <div id="ws-content-roadmap" class="ws-panel">
                                <div class="space-y-2">
                                    <h3 class="ds-type-heading-md" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="ph ph-compass" style="color: var(--ds-text-brand);"></i> Guided Learning Roadmap
                                    </h3>
                                    <p class="ds-type-body-sm" style="margin: 0; color: var(--ds-text-secondary);">Follow this curated checklist to master this tutorial. Your progress is saved automatically.</p>
                                </div>
                                <div class="roadmap-checklist-container">
                                    ${renderWorkspaceRoadmap(video)}
                                </div>
                            </div>

                            <!-- File Explorer Tab Panel -->
                            <div id="ws-content-explorer" class="ws-panel hidden">
                                ${video.code_snippets && video.code_snippets.length > 0 ? `
                                <div class="explorer-layout">
                                    <!-- Sidebar listing files -->
                                    <div class="explorer-sidebar">
                                        <div class="explorer-sidebar-header">
                                            <div style="display:flex; align-items:center; gap:6px; color: var(--ds-text-brand); font-weight:600; font-size:12px;">
                                                <i class="ph ph-folder-open"></i> 
                                                <span>Workspace</span>
                                                <span class="explorer-file-count">${video.code_snippets.length}</span>
                                            </div>
                                        </div>

                                        <input 
                                            type="text" 
                                            class="explorer-search" 
                                            placeholder="Filter files..." 
                                            oninput="filterExplorerFiles(this.value)"
                                            id="explorer-search-input"
                                        >

                                        <div class="explorer-file-list" id="explorer-file-list">
                                            ${video.code_snippets.map((file, idx) => {
                                                const fname = file.path || file.filename;
                                                const icon = getFileIcon(fname);
                                                const ext = getFileExt(fname);
                                                return `
                                                <button 
                                                    onclick="selectExplorerFile(${idx})" 
                                                    id="exp-file-btn-${idx}" 
                                                    class="explorer-file-btn ${idx === 0 ? 'file-active' : ''}"
                                                    data-filename="${escapeHtml(fname.toLowerCase())}"
                                                >
                                                    <i class="ph ${icon} file-icon"></i>
                                                    <span class="file-name">${escapeHtml(fname)}</span>
                                                    ${ext ? `<span class="file-ext">${ext}</span>` : ''}
                                                </button>`;
                                            }).join('')}
                                        </div>
                                    </div>

                                    <!-- Code Viewer -->
                                    <div class="explorer-viewer">
                                        <div class="viewer-header">
                                            <div class="viewer-meta">
                                                <span id="active-file-path" class="code-path"></span>
                                                <span id="active-file-lang" class="ds-badge-electric"></span>
                                            </div>
                                            <ds-button id="copy-active-file-btn" label="Copy Code" variant="ghost" size="sm" icon="copy"></ds-button>
                                        </div>
                                        <pre class="code-pre" style="line-height: 1.45;"><code id="active-file-code"></code></pre>
                                        <div id="active-file-desc" class="code-description-footer"></div>
                                    </div>
                                </div>
                                ` : `
                                <div style="text-align: center; padding: var(--theme-spacing-12) 0; color: var(--ds-text-secondary); display: flex; flex-direction: column; align-items: center; gap: var(--theme-spacing-4);">
                                    <i class="ph ph-folder-open" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h4 class="ds-type-heading-sm" style="margin: 0;">No Code Files Extracted</h4>
                                    <p class="ds-type-body-sm" style="max-width: 440px; margin: 0 auto; line-height: 1.6; color: var(--ds-text-secondary);">
                                        This tutorial did not contain extractable project files (common for brief conceptual guides like this 6-minute video), or the AI key configuration encountered a limits/quota issue.
                                    </p>
                                    <p class="ds-type-body-sm" style="max-width: 440px; margin: 0 auto; line-height: 1.6; color: var(--ds-text-secondary);">
                                        You can use the <strong>Ask AI Copilot</strong> tab to ask code questions about Fetch API directly, or paste a longer project-based coding tutorial URL!
                                    </p>
                                </div>
                                `}
                            </div>

                            <!-- IDE Tab Panel -->
                            <div id="ws-content-ide" class="ws-panel hidden">
                                ${renderIDERecommendations(ideRec, prerequisites)}
                            </div>

                            <!-- Setup & Run Tab Panel -->
                            <div id="ws-content-run" class="ws-panel hidden">
                                <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-6);">
                                    <div>
                                        <h3 class="ds-type-heading-md" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                            <i class="ph ph-wrench" style="color: var(--ds-text-brand);"></i> Setup Steps
                                        </h3>
                                        <p class="ds-type-body-sm" style="margin: 4px 0 0 0; color: var(--ds-text-secondary);">Steps required to configure this application locally.</p>
                                    </div>
                                    <div class="space-y-4">
                                        ${renderSetupGuide(setupGuide, prerequisites)}
                                    </div>
                                    <div style="border-top: 1px solid var(--ds-border-subtle); padding-top: var(--theme-spacing-6);">
                                        <h3 class="ds-type-heading-md" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                            <i class="ph ph-play-circle" style="color: var(--ds-text-brand);"></i> Running the Code
                                        </h3>
                                        <p class="ds-type-body-sm" style="margin: 4px 0 0 0; color: var(--ds-text-secondary);">Commands to start the project in development, production, or Docker mode.</p>
                                    </div>
                                    <div class="space-y-4">
                                        ${renderRunGuide(runGuide)}
                                    </div>
                                </div>
                            </div>

                            <!-- Copilot Chat Tab Panel -->
                            <div id="ws-content-copilot" class="ws-panel hidden">
                                <div style="display: flex; flex-direction: column; gap: 2px;">
                                    <h3 class="ds-type-heading-md" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="ph ph-chat-circle-dots" style="color: var(--ds-text-brand);"></i> AI Copilot Chat
                                    </h3>
                                    <p class="ds-type-body-sm" style="margin: 0; color: var(--ds-text-secondary);">Ask questions about the tutorial code structure, prerequisites, setup instructions, or core concepts.</p>
                                </div>
                                <div class="chat-container">
                                    <div class="chat-history" id="chatHistory">
                                        <div class="chat-bubble chat-bubble-ai">
                                            <p>Hi! I am your AI Copilot for this tutorial. I have analyzed the video transcript and code structure.</p>
                                            <p>How can I help you understand or build this project today?</p>
                                        </div>
                                    </div>
                                    <div class="chat-suggested-questions" id="chatSuggestedQuestions" style="display: flex; gap: 8px; flex-wrap: wrap; padding: 10px var(--theme-spacing-4); border-top: 1px solid var(--ds-border-subtle); background: rgba(0,0,0,0.02);">
                                        <button onclick="askSuggested('Explain the project structure')" style="background: rgba(20, 184, 166, 0.05); border: 1px dashed rgba(20, 184, 166, 0.3); color: var(--ds-text-primary); padding: 6px 12px; border-radius: 20px; font-size: 11px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(20, 184, 166, 0.15)'; this.style.borderColor='rgba(20, 184, 166, 0.6)'" onmouseout="this.style.background='rgba(20, 184, 166, 0.05)'; this.style.borderColor='rgba(20, 184, 166, 0.3)'">Explain project structure</button>
                                        <button onclick="askSuggested('What are the prerequisites?')" style="background: rgba(20, 184, 166, 0.05); border: 1px dashed rgba(20, 184, 166, 0.3); color: var(--ds-text-primary); padding: 6px 12px; border-radius: 20px; font-size: 11px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(20, 184, 166, 0.15)'; this.style.borderColor='rgba(20, 184, 166, 0.6)'" onmouseout="this.style.background='rgba(20, 184, 166, 0.05)'; this.style.borderColor='rgba(20, 184, 166, 0.3)'">What are the prerequisites?</button>
                                        <button onclick="askSuggested('How do I run the code?')" style="background: rgba(20, 184, 166, 0.05); border: 1px dashed rgba(20, 184, 166, 0.3); color: var(--ds-text-primary); padding: 6px 12px; border-radius: 20px; font-size: 11px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='rgba(20, 184, 166, 0.15)'; this.style.borderColor='rgba(20, 184, 166, 0.6)'" onmouseout="this.style.background='rgba(20, 184, 166, 0.05)'; this.style.borderColor='rgba(20, 184, 166, 0.3)'">How do I run the code?</button>
                                    </div>
                                    <div class="chat-input-area">
                                        <div class="chat-input-wrapper">
                                            <ds-input id="copilotQuestionInput" placeholder="Ask a question about the code or concepts..." size="md"></ds-input>
                                        </div>
                                        <div>
                                            <ds-button onclick="sendCopilotQuestion()" label="Send" variant="primary" size="md" icon="paper-plane-right"></ds-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            resultsContainer.innerHTML = html;

            // Load initial file in explorer
            updateExplorerFile(0);

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

            // Set up Copilot question enter listener
            setTimeout(() => {
                const chatInput = document.getElementById('copilotQuestionInput');
                if (chatInput) {
                    const field = chatInput.querySelector('input');
                    if (field) {
                        field.addEventListener('keydown', (e) => {
                            if (e.key === 'Enter') {
                                sendCopilotQuestion();
                            }
                        });
                    }
                }
            }, 300);
        }

        // Switch workspace tabs
        function switchWorkspaceTab(tabName) {
            document.querySelectorAll('.ws-panel').forEach(panel => panel.classList.add('hidden'));
            document.querySelectorAll('.ws-nav-btn').forEach(btn => btn.classList.remove('ws-nav-active'));

            document.getElementById(`ws-content-${tabName}`).classList.remove('hidden');
            document.getElementById(`ws-tab-${tabName}`).classList.add('ws-nav-active');

            if (tabName === 'explorer') {
                // Reset filter and show all files
                setTimeout(() => {
                    const search = document.getElementById('explorer-search-input');
                    if (search) search.value = '';

                    const list = document.getElementById('explorer-file-list');
                    if (list) {
                        list.querySelectorAll('.explorer-file-btn').forEach(b => b.style.display = '');
                    }

                    // Re-highlight code
                    document.querySelectorAll('pre code').forEach((block) => {
                        try { hljs.highlightElement(block); } catch(e){}
                    });
                }, 80);
            }
        }

        // Render Roadmap checks
        function renderWorkspaceRoadmap(video) {
            let items = [];
            
            // 1. Prerequisites (software)
            if (video.prerequisites && video.prerequisites.software) {
                video.prerequisites.software.forEach((sw, idx) => {
                    items.push({
                        id: `prereq-sw-${idx}`,
                        title: `Install ${sw.name}`,
                        desc: sw.purpose || 'Required dependency software.'
                    });
                });
            }

            // 2. Setup steps
            if (video.setup_guide && video.setup_guide.steps) {
                video.setup_guide.steps.forEach((step, idx) => {
                    items.push({
                        id: `setup-step-${idx}`,
                        title: `Setup Step ${step.step}: ${step.title}`,
                        desc: step.explanation
                    });
                });
            }

            // 3. Learning outcomes
            if (video.tutorial_guide && video.tutorial_guide.learning_outcomes) {
                video.tutorial_guide.learning_outcomes.forEach((outcome, idx) => {
                    items.push({
                        id: `outcome-${idx}`,
                        title: `Outcome Mastered: ${outcome}`,
                        desc: 'Master the concept and verify it works.'
                    });
                });
            }

            // Fallback if empty
            if (items.length === 0) {
                items.push({
                    id: 'general-scaffold',
                    title: 'Examine Project Files',
                    desc: 'Review the generated code in the file explorer.'
                });
                items.push({
                    id: 'run-project',
                    title: 'Run Application',
                    desc: 'Boot the local development server.'
                });
            }

            // Load saved checklist states
            const savedState = JSON.parse(localStorage.getItem(`roadmap-${video.id}`) || '{}');

            return items.map(item => {
                const isCompleted = savedState[item.id] === true;
                return `
                    <div class="roadmap-item ${isCompleted ? 'completed' : ''}" onclick="toggleRoadmapItem('${video.id}', '${item.id}', this)">
                        <div class="roadmap-checkbox">
                            ${isCompleted ? '<i class="ph ph-check" style="font-size: 0.8rem;"></i>' : ''}
                        </div>
                        <div class="roadmap-text">
                            <h4 class="roadmap-title">${escapeHtml(item.title)}</h4>
                            <span class="roadmap-desc">${escapeHtml(item.desc)}</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function toggleRoadmapItem(videoId, itemId, element) {
            const savedState = JSON.parse(localStorage.getItem(`roadmap-${videoId}`) || '{}');
            const isCompleted = !element.classList.contains('completed');
            
            savedState[itemId] = isCompleted;
            localStorage.setItem(`roadmap-${videoId}`, JSON.stringify(savedState));

            const checkbox = element.querySelector('.roadmap-checkbox');
            if (isCompleted) {
                element.classList.add('completed');
                checkbox.innerHTML = '<i class="ph ph-check" style="font-size: 0.8rem;"></i>';
            } else {
                element.classList.remove('completed');
                checkbox.innerHTML = '';
            }
        }

        // File Explorer Selection handlers
        function selectExplorerFile(idx) {
            document.querySelectorAll('.explorer-file-btn').forEach(btn => btn.classList.remove('file-active'));
            const btn = document.getElementById(`exp-file-btn-${idx}`);
            if (btn) btn.classList.add('file-active');
            updateExplorerFile(idx);
        }

        function filterExplorerFiles(query) {
            const q = (query || '').toLowerCase().trim();
            const list = document.getElementById('explorer-file-list');
            if (!list) return;

            const buttons = list.querySelectorAll('.explorer-file-btn');
            let visibleCount = 0;

            buttons.forEach(btn => {
                const name = btn.getAttribute('data-filename') || '';
                const match = !q || name.includes(q);
                btn.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });
        }

        function updateExplorerFile(idx) {
            if (!activeVideo || !activeVideo.code_snippets || !activeVideo.code_snippets[idx]) return;
            selectedExplorerFileIndex = idx;
            const file = activeVideo.code_snippets[idx];

            const pathEl = document.getElementById('active-file-path');
            if (pathEl) pathEl.textContent = file.path || file.filename;

            const langEl = document.getElementById('active-file-lang');
            if (langEl) langEl.textContent = file.language || '';
            
            const codeEl = document.getElementById('active-file-code');
            if (codeEl) {
                codeEl.className = `language-${file.language || 'plaintext'}`;
                codeEl.textContent = file.code || '';
            }

            const descEl = document.getElementById('active-file-desc');
            if (descEl) {
                if (file.description) {
                    descEl.innerHTML = `<i class="ph ph-info" style="margin-right: 4px;"></i> ${escapeHtml(file.description)}`;
                    descEl.classList.remove('hidden');
                } else {
                    descEl.classList.add('hidden');
                }
            }

            // Apply syntax highlight
            if (codeEl && window.hljs) {
                try { hljs.highlightElement(codeEl); } catch(e){}
            }

            // Re-bind copy button with nice feedback
            const copyBtn = document.getElementById('copy-active-file-btn');
            if (copyBtn) {
                const originalLabel = copyBtn.getAttribute('label') || 'Copy Code';
                copyBtn.onclick = () => {
                    navigator.clipboard.writeText(file.code || '').then(() => {
                        const origText = copyBtn.textContent;
                        copyBtn.innerHTML = `<i class="ph ph-check"></i> Copied!`;
                        setTimeout(() => {
                            if (copyBtn) {
                                copyBtn.innerHTML = origText || originalLabel;
                            }
                        }, 1600);
                    }).catch(() => {
                        // fallback
                        copyToClipboard(file.code || '');
                    });
                };
            }
        }

        // Render IDE recommendation block
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
                                    <h4 class="ds-type-heading-md" style="margin: 0;">${escapeHtml(ide.primary.name)}</h4>
                                    <p class="ds-type-body-sm" style="margin: 0; color: var(--ds-text-secondary);">${escapeHtml(ide.primary.reason)}</p>
                                </div>
                                <div style="width: auto; align-self: flex-start;">
                                    <a href="${escapeHtml(ide.primary.download_url)}" target="_blank" style="text-decoration: none;">
                                        <ds-button label="Download" variant="primary" size="sm" icon="download-simple"></ds-button>
                                    </a>
                                </div>
                            </div>
                            ${ide.primary.extensions && ide.primary.extensions.length > 0 ? `
                                <div style="margin-top: var(--theme-spacing-5); border-top: 1px solid var(--ds-border-subtle); padding-top: var(--theme-spacing-4);">
                                    <p class="ds-type-label-md text-amber-400" style="margin: 0 0 var(--theme-spacing-2);">Recommended Extensions:</p>
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
                                        <h4 class="font-bold" style="margin: 0;">${escapeHtml(alt.name)}</h4>
                                        <a href="${escapeHtml(alt.download_url)}" target="_blank" style="color: var(--ds-text-accent); text-decoration: none; font-size: var(--theme-font-size-xs); font-weight: 600; display: flex; align-items: center; gap: 2px;">
                                            Download <i class="ph ph-arrow-square-out"></i>
                                        </a>
                                    </div>
                                    <p class="ds-type-body-sm" style="margin: var(--theme-spacing-2) 0 0; color: var(--ds-text-secondary);">${escapeHtml(alt.reason)}</p>
                                    ${alt.extensions && alt.extensions.length > 0 ? `
                                        <div style="margin-top: var(--theme-spacing-3); display: flex; flex-wrap: wrap; gap: var(--theme-spacing-2); padding-top: var(--theme-spacing-2); border-top: 1px solid var(--ds-border-subtle);">
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

        // Render setup steps
        function renderSetupGuide(setupGuide, prerequisites) {
            let html = '';

            if (prerequisites && prerequisites.software && prerequisites.software.length > 0) {
                html += `
                    <div class="space-y-4">
                        <h4 class="font-semibold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-primary);">Software Prerequisites</h4>
                        <div class="grid-half">
                            ${prerequisites.software.map(sw => `
                                <ds-card variant="glass" padding="md">
                                    <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: var(--theme-spacing-4);">
                                        <div>
                                            <h4 class="font-bold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-primary);">${escapeHtml(sw.name)}</h4>
                                            <p class="ds-type-body-sm" style="margin: var(--theme-spacing-1) 0 0; color: var(--ds-text-secondary);">${escapeHtml(sw.purpose)}</p>
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
                    <div class="space-y-4">
                        <h4 class="font-semibold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-primary);">Setup Steps</h4>
                        <div class="space-y-4">
                            ${setupGuide.steps.map(step => `
                                <ds-card variant="glass" padding="md">
                                    <div style="display: flex; align-items: center; gap: var(--theme-spacing-3); margin-bottom: var(--theme-spacing-3);">
                                        <span style="width: 28px; height: 28px; background: var(--ds-color-electric); border-radius: var(--theme-radius-full); display: flex; align-items: center; justify-content: center; font-weight: bold; color: var(--theme-neutral-1000); font-size: var(--theme-font-size-sm);">${step.step}</span>
                                        <h4 class="font-bold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-primary);">${escapeHtml(step.title)}</h4>
                                    </div>
                                    <p class="ds-type-body-sm" style="margin: 0 0 var(--theme-spacing-3); color: var(--ds-text-secondary);">${escapeHtml(step.explanation)}</p>
                                    ${step.commands && step.commands.length > 0 ? `
                                        <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                            ${step.commands.map(cmd => `
                                                <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                                    <code class="text-green-600 font-mono text-sm">${escapeHtml(cmd)}</code>
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

            return html || '<p class="text-gray-400">No setup steps available.</p>';
        }

        // Render run guide
        function renderRunGuide(runGuide) {
            if (!runGuide) {
                return `<p class="text-gray-400">Run guide is being generated...</p>`;
            }

            let html = '';

            if (runGuide.development) {
                html += `
                    <div class="space-y-3">
                        <h4 class="font-semibold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-color-success);">Development Server</h4>
                        <ds-card variant="glow-electric" padding="md">
                            <p class="ds-type-body-sm" style="margin: 0 0 var(--theme-spacing-3); color: var(--ds-text-secondary);">${escapeHtml(runGuide.development.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); margin-bottom: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.development.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-green-600 font-mono text-sm">${escapeHtml(cmd)}</code>
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
                        <h4 class="font-semibold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-brand);">Production Build</h4>
                        <ds-card variant="glass-accent" padding="md">
                            <p class="ds-type-body-sm" style="margin: 0 0 var(--theme-spacing-3); color: var(--ds-text-secondary);">${escapeHtml(runGuide.production.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.production.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-teal-600 font-mono text-sm">${escapeHtml(cmd)}</code>
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
                        <h4 class="font-semibold" style="margin: 0; font-size: var(--theme-font-size-sm); color: var(--ds-text-electric);">Docker Container</h4>
                        <ds-card variant="glass" padding="md">
                            <p class="ds-type-body-sm" style="margin: 0 0 var(--theme-spacing-3); color: var(--ds-text-secondary);">${escapeHtml(runGuide.docker.explanation)}</p>
                            <div class="ds-surface-inset" style="padding: var(--theme-spacing-3); display: flex; flex-direction: column; gap: var(--theme-spacing-2);">
                                ${runGuide.docker.commands.map(cmd => `
                                    <div style="display: flex; align-items: center; justify-content: space-between; py: 1;">
                                        <code class="text-cyan-700 font-mono text-sm">${escapeHtml(cmd)}</code>
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

        function askSuggested(question) {
            const chatInput = document.getElementById('copilotQuestionInput');
            if (chatInput) {
                chatInput.value = question;
                sendCopilotQuestion();
            }
        }

        // Copilot Chat message dispatching
        async function sendCopilotQuestion() {
            const chatInput = document.getElementById('copilotQuestionInput');
            const chatHistory = document.getElementById('chatHistory');
            
            const question = chatInput ? chatInput.value.trim() : '';
            if (!question || !activeVideo) return;

            // Clear input
            chatInput.value = '';

            // 1. Append User Message
            const userMsgHtml = `
                <div class="chat-bubble chat-bubble-user">
                    ${escapeHtml(question)}
                </div>
            `;
            chatHistory.insertAdjacentHTML('beforeend', userMsgHtml);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            // 2. Append Typing Indicator
            const typingId = `typing-${Date.now()}`;
            const typingHtml = `
                <div class="chat-bubble chat-bubble-ai" id="${typingId}">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            chatHistory.insertAdjacentHTML('beforeend', typingHtml);
            chatHistory.scrollTop = chatHistory.scrollHeight;

            try {
                // Send to backend endpoint
                const response = await fetch(`/api/videos/${activeVideo.id}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ question: question })
                });

                const resData = await response.json();
                const typingBubble = document.getElementById(typingId);
                
                if (!response.ok || !resData.success) {
                    throw new Error(resData.error || 'Failed to get answer.');
                }

                // Render markdown code blocks simple parser
                const formattedAnswer = formatChatMessage(resData.answer);
                
                // Replace typing indicator content with AI answer
                typingBubble.innerHTML = formattedAnswer;
                
                // Apply syntax highlighting to code inside the chat
                typingBubble.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
                
            } catch (error) {
                const typingBubble = document.getElementById(typingId);
                if (typingBubble) {
                    typingBubble.innerHTML = `<p style="color: var(--ds-color-error); margin: 0;"><i class="ph ph-warning-circle"></i> Error: ${escapeHtml(error.message)}</p>`;
                }
            } finally {
                chatHistory.scrollTop = chatHistory.scrollHeight;
            }
        }

        // Simple Markdown parser for chat messages
        function formatChatMessage(text) {
            if (!text) return '';
            
            // Encode html first to avoid injection
            let escaped = escapeHtml(text);

            // Replace code blocks: ```lang ... ```
            escaped = escaped.replace(/```(\w*)\n([\s\S]*?)```/g, (match, lang, code) => {
                const codeClass = lang ? `language-${lang}` : '';
                return `<pre><code class="${codeClass}">${code}</code></pre>`;
            });

            // Replace inline code `code`
            escaped = escaped.replace(/`([^`]+)`/g, '<code class="ds-type-code-sm" style="background: rgba(255,255,255,0.06); padding: 2px 4px; border-radius: 4px;">$1</code>');

            // Replace bold **text**
            escaped = escaped.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

            // Paragraph newlines
            escaped = escaped.split('\n\n').map(para => `<p>${para.replace(/\n/g, '<br>')}</p>`).join('');

            return escaped;
        }

        function sanitiseRepoName(title) {
            let name = title.replace(/[^a-zA-Z0-9_.-]/g, '-');
            name = name.replace(/-+/g, '-');
            name = name.trim('-');
            return name.toLowerCase().substring(0, 100) || 'youextractor-project';
        }

        // Action: Push to GitHub handler
        function pushToGitHub(videoId) {
            const tokenInput = document.getElementById('github-token-input');
            const repoNameInput = document.getElementById('github-repo-name');
            const repoDescInput = document.getElementById('github-repo-desc');
            const repoPrivacySelect = document.getElementById('github-repo-privacy');
            const rememberTokenCheckbox = document.getElementById('github-remember-token');
            const pushBtn = document.getElementById('github-push-btn');
            const statusMsg = document.getElementById('github-status-msg');
            
            const token = tokenInput ? tokenInput.value.trim() : '';
            const repoName = repoNameInput ? repoNameInput.value.trim() : '';
            const repoDesc = repoDescInput ? repoDescInput.value.trim() : '';
            const isPrivate = repoPrivacySelect ? repoPrivacySelect.value === 'private' : false;
            
            if (!token) {
                tokenInput.error = 'GitHub Personal Access Token is required';
                return;
            }
            
            tokenInput.error = '';
            
            if (rememberTokenCheckbox && rememberTokenCheckbox.checked) {
                localStorage.setItem('github_personal_token', token);
            } else {
                localStorage.removeItem('github_personal_token');
            }
            
            pushBtn.loading = true;
            statusMsg.classList.remove('hidden');
            statusMsg.className = 'text-sm text-blue-400 font-mono';
            statusMsg.innerHTML = `<i class="ph ph-spinner"></i> Creating new GitHub repo and pushing files... <span style="opacity:0.7">(this may take a few seconds)</span>`;
            
            fetch(`/api/videos/${videoId}/push-to-github`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    github_token: token,
                    repo_name: repoName,
                    description: repoDesc,
                    private: isPrivate
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Failed to push to GitHub');
                }
                return data;
            })
            .then(data => {
                pushBtn.loading = false;
                if (data.success) {
                    statusMsg.className = 'text-sm text-green-400 font-mono';
                    statusMsg.innerHTML = `<i class="ph ph-check-circle"></i> Success! New repo created with your full project. <a href="${escapeHtml(data.github_url)}" target="_blank" class="underline text-green-300 font-semibold" style="margin-left: 6px;">Open on GitHub →</a>`;
                    
                    const formContainer = document.getElementById('github-push-form');
                    if (formContainer) {
                        formContainer.innerHTML = `
                            <a href="${escapeHtml(data.github_url)}" target="_blank" style="text-decoration: none;">
                                <ds-button label="View Repo on GitHub" variant="glow" size="md" icon="arrow-square-out"></ds-button>
                            </a>
                        `;
                    }
                    
                    // Update activeVideo and allVideos cache
                    if (activeVideo) activeVideo.github_repo_url = data.github_url;
                    const cachedVid = allVideos.find(v => v.id == videoId);
                    if (cachedVid) cachedVid.github_repo_url = data.github_url;

                } else {
                    statusMsg.className = 'text-sm text-red-400 font-mono';
                    statusMsg.textContent = data.error || 'Failed to push to GitHub.';
                }
            })
            .catch(err => {
                pushBtn.loading = false;
                statusMsg.className = 'text-sm text-red-400 font-mono';
                statusMsg.textContent = err.message || 'An error occurred while pushing to GitHub.';
                console.error(err);
            });
        }

        // Action: Re-extraction
        async function triggerReExtraction(videoId) {
            const reExtractBtn = document.querySelector('#reExtractForm ds-button');
            reExtractBtn.loading = true;
            
            try {
                const response = await fetch(`/api/videos/${videoId}/re-extract`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.error || 'Failed to trigger re-extraction');
                }

                // Hide workspace and show loading overlay
                resultsContainer.classList.add('hidden');
                loadingDiv.classList.remove('hidden');
                loadingText.textContent = 'Starting re-extraction...';
                
                const completedVideo = await pollExtractionStatus(videoId);
                
                // Update local array cache
                const index = allVideos.findIndex(v => v.id === completedVideo.id);
                if (index !== -1) {
                    allVideos[index] = completedVideo;
                }
                
                loadingDiv.classList.add('hidden');
                openWorkspace(completedVideo.id);
            } catch (error) {
                alert(`Re-extraction failed: ${error.message}`);
                reExtractBtn.loading = false;
            }
        }

        // Helpers
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.createElement('div');
                toast.style.position = 'fixed';
                toast.style.bottom = '24px';
                toast.style.right = '24px';
                toast.style.animation = 'fadeIn 0.4s var(--theme-ease-out)';
                toast.style.zIndex = '999';
                toast.innerHTML = `<span class="ds-badge-success" style="padding: var(--theme-spacing-3) var(--theme-spacing-5); box-shadow: var(--theme-shadow-xl); font-size: var(--theme-font-size-sm); display: flex; align-items: center; gap: 8px;">
                    <i class="ph ph-check-circle" style="font-size: 1.25rem;"></i> Copied code to clipboard!
                </span>`;
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

        function getFileIcon(filename) {
            const ext = (filename.split('.').pop() || '').toLowerCase();
            const name = filename.toLowerCase();

            if (['js', 'jsx', 'ts', 'tsx'].includes(ext)) return 'ph-file-js';
            if (['json'].includes(ext)) return 'ph-file-json';
            if (['css', 'scss', 'less', 'sass'].includes(ext)) return 'ph-file-css';
            if (['html', 'htm'].includes(ext)) return 'ph-file-html';
            if (['md', 'markdown'].includes(ext)) return 'ph-file-text';
            if (['py'].includes(ext)) return 'ph-file-py';
            if (['yml', 'yaml'].includes(ext)) return 'ph-gear';
            if (['sh', 'bash', 'zsh'].includes(ext)) return 'ph-terminal';
            if (['sql'].includes(ext)) return 'ph-database';
            if (['env', 'gitignore', 'dockerfile', 'lock'].some(s => name.includes(s))) return 'ph-file-code';
            if (['png','jpg','jpeg','svg','gif','webp'].includes(ext)) return 'ph-image';
            return 'ph-file-code';
        }

        function getFileExt(filename) {
            const parts = filename.split('.');
            return parts.length > 1 ? parts.pop().toLowerCase() : '';
        }
    </script>
</body>
</html>
