<?php

/**
 * Site identity shared by the AI-readable surfaces: /llms.txt, /llms-full.txt,
 * /ai.txt, /robots.txt and the markdown page versions.
 *
 * The HTML pages' JSON-LD carries the same facts; keep them in agreement.
 */
return [
    'name' => 'YouExtractor',

    'url' => rtrim(env('APP_URL', 'https://youextractor.me'), '/'),

    'tagline' => 'AI tool that turns YouTube coding tutorials into runnable source-code projects.',

    'description' => 'YouExtractor is an AI developer tool that turns public YouTube coding tutorials into complete, runnable source-code projects: file tree, dependencies, a written setup guide and a learning roadmap. It is built for students, self-taught developers and engineers who learn from video and want code that runs.',

    'disambiguation' => 'YouExtractor is not a YouTube tags, title, description or SEO metadata extractor. It extracts source code from programming tutorials.',

    'pricing' => 'Every account gets 1 free extraction. After that, credit packs start at $2 for 5 extractions, or Unlimited Pro is $12 per month.',

    'founder' => [
        'name' => 'Omogo Peter Onyedika',
        'url' => 'https://devomogo.tech',
    ],

    'contact' => [
        'email' => 'omogopeter48@gmail.com',
        'issues' => 'https://github.com/onyedika-glitch/youextractor/issues',
        'support_page' => '/support',
    ],

    'same_as' => [
        'https://github.com/onyedika-glitch/youextractor',
        'https://chromewebstore.google.com/detail/youextractor/ihajahjkhnelimamilebbcjibbhghbcn',
        'https://devomogo.tech',
        'https://productwatch.io/products/youextractor',
    ],

    'chrome_extension' => 'https://chromewebstore.google.com/detail/youextractor/ihajahjkhnelimamilebbcjibbhghbcn',

    /*
     * Paths no crawler or agent should read. Feeds robots.txt (every user agent)
     * and the [scope] section of ai.txt. /pricing, /signin and /signup are left
     * crawlable on purpose: they carry a noindex meta tag Google must be able to see.
     */
    'private_paths' => [
        '/dashboard',
        '/videos',
        '/auth/',
        '/logout',
        '/checkout/',
        '/payment/',
        '/webhooks/',
        '/api/',
    ],

    // Exceptions inside private_paths that agents may fetch.
    'public_exceptions' => [
        '/api/md/',
    ],
];
