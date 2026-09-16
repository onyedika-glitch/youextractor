<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.favicon')
    <meta name="robots" content="noindex, nofollow">
    <title>Pricing Plans and Extractions YouExtractor</title>
    
    <!-- Design System CSS -->
    <link rel="stylesheet" href="/css/youextractor-design-system.css?v=5">
    
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <style>
        body { 
            font-family: var(--theme-font-sans); 
            background: #0b0f19;
            color: #f3f4f6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
            box-sizing: border-box;
        }

        header {
            background: rgba(17, 24, 39, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .brand-logo img {
            width: 32px;
            height: 32px;
            border-radius: 6px;
        }

        .nav-link {
            color: #9ca3af;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #ffffff;
        }

        .hero-section {
            text-align: center;
            padding: 60px 20px 40px;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            background: linear-gradient(135deg, #ffffff 0%, #9bc5ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.125rem;
            color: #9ca3af;
            max-width: 600px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }

        .status-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.15);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #60a5fa;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 28px;
            margin-bottom: 80px;
        }

        .pricing-card {
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 36px 28px;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.25s ease, border-color 0.25s ease;
        }

        .pricing-card:hover {
            transform: translateY(-4px);
            border-color: rgba(59, 130, 246, 0.5);
        }

        .pricing-card.featured {
            border: 2px solid #3b82f6;
            background: linear-gradient(180deg, #162032 0%, #111827 100%);
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15);
        }

        .popular-tag {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #3b82f6;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 14px;
            border-radius: 9999px;
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .card-description {
            font-size: 0.9rem;
            color: #9ca3af;
            margin-bottom: 24px;
            min-height: 40px;
        }

        .card-price {
            font-size: 2.75rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 24px;
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .card-price span {
            font-size: 1rem;
            font-weight: 500;
            color: #9ca3af;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0 0 32px 0;
            flex-grow: 1;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            color: #d1d5db;
            margin-bottom: 14px;
        }

        .feature-item i {
            color: #3b82f6;
            font-size: 1.1rem;
        }

        .btn-buy {
            width: 100%;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s ease;
            text-align: center;
            text-decoration: none;
            box-sizing: border-box;
            display: block;
        }

        .btn-buy:hover {
            background: #1d4ed8;
        }

        .btn-buy-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-buy-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .alert-banner {
            max-width: 600px;
            margin: 0 auto 24px;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 0.95rem;
            text-align: center;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        footer {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 0;
            text-align: center;
            color: #6b7280;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <div class="header-content">
                <a href="/dashboard" class="brand-logo">
                    <img src="/img/youextractor-logo.png" alt="YouExtractor Logo">
                    <span>YouExtractor</span>
                </a>
                <a href="/dashboard" class="nav-link">Back to Dashboard</a>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="hero-section">
            <h1 class="hero-title">Simple and Transparent Pricing</h1>
            <p class="hero-subtitle">Extract code setup guides and project repositories from YouTube tutorials with speed and accuracy.</p>

            @if(session('error'))
                <div class="alert-banner alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert-banner alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div class="status-badge">
                    @if(auth()->user()->isProActive())
                        Unlimited Pro Active until {{ auth()->user()->pro_until?->format('M d, Y') }}
                    @elseif(auth()->user()->credits > 0)
                        {{ auth()->user()->credits }} Extractions Remaining
                    @elseif(auth()->user()->free_extractions_used == 0)
                        1 Free Extraction Available
                    @else
                        0 Extractions Remaining. Upgrade Below
                    @endif
                </div>
            @endauth
        </div>

        <div class="pricing-grid">
            <!-- Starter Credit Pack -->
            <div class="pricing-card">
                <div class="card-title">Starter Pack</div>
                <div class="card-description">Great for quick tutorial extractions and small projects.</div>
                <div class="card-price">$2.00 <span>USD</span></div>
                
                <ul class="feature-list">
                    <li class="feature-item"><i class="ph ph-check-circle"></i> 5 Video Extractions</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Complete Codebase Extraction</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> ZIP File Download</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> One Click GitHub Push</li>
                </ul>

                @auth
                    <form action="{{ route('checkout.bachs') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="starter">
                        <button type="submit" class="btn-buy btn-buy-secondary">Buy Starter Pack</button>
                    </form>
                @else
                    <a href="{{ route('signin') }}" class="btn-buy btn-buy-secondary">Sign In to Buy</a>
                @endauth
            </div>

            <!-- Pro Credit Pack -->
            <div class="pricing-card featured">
                <div class="popular-tag">Most Popular</div>
                <div class="card-title">Pro Pack</div>
                <div class="card-description">Ideal for active developers and frequent learners.</div>
                <div class="card-price">$5.00 <span>USD</span></div>
                
                <ul class="feature-list">
                    <li class="feature-item"><i class="ph ph-check-circle"></i> 20 Video Extractions</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Priority Extraction Processing</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Complete Codebase Extraction</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> ZIP Download and GitHub Push</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> AI Code Copilot Chat</li>
                </ul>

                @auth
                    <form action="{{ route('checkout.bachs') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="pro_pack">
                        <button type="submit" class="btn-buy">Buy Pro Pack</button>
                    </form>
                @else
                    <a href="{{ route('signin') }}" class="btn-buy">Sign In to Buy</a>
                @endauth
            </div>

            <!-- Unlimited Pro Monthly -->
            <div class="pricing-card">
                <div class="card-title">Unlimited Pro</div>
                <div class="card-description">For power users and teams building constantly.</div>
                <div class="card-price">$12.00 <span>USD / mo</span></div>
                
                <ul class="feature-list">
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Unlimited Video Extractions</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> 30 Days Full Access</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Top Priority Queue Processing</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Unlimited AI Chat Copilot</li>
                    <li class="feature-item"><i class="ph ph-check-circle"></i> Direct GitHub Push</li>
                </ul>

                @auth
                    <form action="{{ route('checkout.bachs') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan" value="pro_monthly">
                        <button type="submit" class="btn-buy btn-buy-secondary">Get Unlimited Pro</button>
                    </form>
                @else
                    <a href="{{ route('signin') }}" class="btn-buy btn-buy-secondary">Sign In to Subscribe</a>
                @endauth
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>Protected by Bachs Secure Payment Gateway. All transactions processed securely.</p>
        </div>
    </footer>

</body>
</html>
