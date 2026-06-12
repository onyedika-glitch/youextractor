<footer style="background: rgba(6, 11, 24, 0.9); border-top: 1px solid var(--ds-border-subtle); padding: var(--theme-spacing-12) 0 var(--theme-spacing-8); margin-top: var(--theme-spacing-24); color: var(--ds-text-secondary); font-family: var(--theme-font-sans); width: 100%; box-sizing: border-box;">
    <div class="container">
        <!-- Main Footer Content -->
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--theme-spacing-8); margin-bottom: var(--theme-spacing-12);" class="footer-grid-responsive">
            <!-- Brand Column -->
            <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-4);">
                <a href="{{ route('landing') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: var(--ds-text-primary);">
                    <img src="/img/youextractor-logo.jpg" alt="YouExtractor" style="width: 28px; height: 28px; border-radius: 6px; object-fit: cover; border: 1px solid rgba(168, 85, 247, 0.25);">
                    <span style="font-weight: 700; font-size: 1.15rem; letter-spacing: var(--theme-letter-spacing-tight);">YouExtractor</span>
                </a>
                <p style="margin: 0; font-size: var(--theme-font-size-sm); max-width: 280px; line-height: var(--theme-line-height-relaxed);">
                    Turn programming video tutorials into fully structured codebase projects in seconds. Learn faster, code smarter.
                </p>
            </div>

            <!-- Links Columns Wrapper -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: var(--theme-spacing-8); flex: 1;">
                <!-- Product Links -->
                <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-3);">
                    <span style="font-weight: 600; font-size: var(--theme-font-size-xs); text-transform: uppercase; letter-spacing: var(--theme-letter-spacing-wider); color: var(--ds-text-primary);">Product</span>
                    <a href="{{ route('landing') }}#how" class="footer-link-item">How it works</a>
                    <a href="{{ route('landing') }}#features" class="footer-link-item">Features</a>
                    <a href="{{ route('blog.index') }}" class="footer-link-item">Blog</a>
                </div>

                <!-- Support & Legal Links -->
                <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-3);">
                    <span style="font-weight: 600; font-size: var(--theme-font-size-xs); text-transform: uppercase; letter-spacing: var(--theme-letter-spacing-wider); color: var(--ds-text-primary);">Resources</span>
                    <a href="{{ route('support') }}" class="footer-link-item">Support</a>
                    <a href="{{ route('privacy') }}" class="footer-link-item">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="footer-link-item">Terms of Service</a>
                </div>

                <!-- Community / Social -->
                <div style="display: flex; flex-direction: column; gap: var(--theme-spacing-3);">
                    <span style="font-weight: 600; font-size: var(--theme-font-size-xs); text-transform: uppercase; letter-spacing: var(--theme-letter-spacing-wider); color: var(--ds-text-primary);">Community</span>
                    <a href="https://buymeacoffee.com/omogo" target="_blank" class="footer-link-item" style="color: #fbbf24; font-weight: 500;">
                        <i class="ph ph-coffee" style="margin-right: 4px;"></i> Buy Me a Coffee
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright Area -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: var(--theme-spacing-6); display: flex; flex-direction: column; gap: var(--theme-spacing-4); align-items: center; justify-content: space-between;" class="footer-bottom-responsive">
            <p style="margin: 0; font-size: var(--theme-font-size-xs); color: var(--ds-text-muted);">
                &copy; {{ date('Y') }} YouExtractor. Built with love for developers who learn visually.
            </p>
            <p style="margin: 0; font-size: var(--theme-font-size-xs); color: var(--ds-text-muted);">
                All rights reserved.
            </p>
        </div>
    </div>
</footer>

<style>
    .footer-link-item {
        color: var(--ds-text-secondary);
        font-size: var(--theme-font-size-sm);
        text-decoration: none;
        transition: color var(--theme-motion-fast) var(--theme-ease-default);
    }
    .footer-link-item:hover {
        color: var(--ds-text-primary);
    }
    @media (min-width: 768px) {
        .footer-grid-responsive {
            grid-template-columns: 320px 1fr !important;
        }
        .footer-bottom-responsive {
            flex-direction: row !important;
        }
    }
</style>
