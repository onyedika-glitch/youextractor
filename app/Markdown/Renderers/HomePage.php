<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;

/**
 * Keep in sync with resources/views/landing.blade.php (main content, visible FAQ).
 */
class HomePage extends StaticPage
{
    public function pattern(): string
    {
        return '/';
    }

    public function render(array $params): ?MarkdownPage
    {
        $tools = [
            Format::link('Extract Code from YouTube Tutorials', '/tools/extract-code-from-youtube') . ' - Free AI tool. Paste a public coding tutorial. Get the files, folders, and a setup guide.',
            Format::link('YouTube to GitHub Tool', '/tools/youtube-to-github') . ' - Push a reconstructed tutorial project to a new GitHub repo in one click.',
            Format::link('AI Code Extractor', '/tools/ai-code-extractor') . ' - Rebuild runnable projects from programming videos: React, Python, Next.js, Laravel, and more.',
            Format::link('Learn Programming Faster', '/tools/learn-programming-faster') . ' - Turn watching into building. Extract, run, modify, commit, without copy-pasting from the player.',
            Format::link('Stack guides (React, Next.js, Python...)', '/for/react') . ' - Extract tutorials for a specific framework.',
            Format::link('All tools & stack guides', '/tools') . ' - YouTube code extractor, GitHub export, follow-along, Docker, Laravel, TypeScript, and more.',
        ];

        $faqs = [
            ['q' => 'What is YouExtractor?', 'a' => 'YouExtractor is an AI developer tool at youextractor.me that turns YouTube coding tutorials into complete, runnable source-code projects, including the file tree, setup guide, and a learning roadmap.'],
            ['q' => 'Is YouExtractor a YouTube tags or metadata extractor?', 'a' => 'No. YouExtractor is not a yt extractor / YouTube tags, title, or SEO metadata tool. Those products copy video tags and descriptions. YouExtractor extracts source code from programming tutorials. See ' . Format::link('About YouExtractor', '/about') . '.'],
            ['q' => 'Does YouExtractor work with private or members-only videos?', 'a' => 'Currently we support any public YouTube video with available captions/transcripts. Private videos and YouTube Premium content are not supported.'],
            ['q' => 'How accurate is the extracted code?', 'a' => 'Accuracy is highest on well-explained tutorials. The AI reconstructs files from both on-screen code and spoken instructions. You always get the full context and can edit anything.'],
            ['q' => 'Is my data private? Do you store the videos?', 'a' => 'We only store the final extracted artifacts (files, guide, metadata) in your private library. We do not re-host or redistribute original video content. See our ' . Format::link('Privacy Policy', '/privacy') . '.'],
            ['q' => 'Can I push directly to a new GitHub repository?', 'a' => 'Yes. Connect your GitHub account once and every extraction can be pushed as a brand new private or public repo with proper .gitignore and README in a single click.'],
            ['q' => 'How does pricing and the free trial work?', 'a' => 'You get 1 free extraction to test YouExtractor on any public YouTube tutorial. After your first extraction, you can purchase credit packs starting at $2 for 5 extractions or get an Unlimited Pro monthly subscription for $12.'],
        ];

        $body = <<<MD
        ## From Video to Workspace

        AI tool that extracts complete source code, folders, and a setup guide from any public YouTube coding tutorial. Paste a link. Download a ZIP or push to GitHub.

        ## From video to project in minutes, not hours

        Paste a link. YouExtractor watches the tutorial, reads the transcript, understands intent, and builds the project you need.

        1. **Paste the URL.** Any public coding tutorial on YouTube. Works with long courses or quick tips.
        2. **AI analyzes everything.** We fetch the transcript, detect tech stack, extract every code block and spoken instruction.
        3. **Get the full project.** Complete folder structure, runnable files, package.json, .env.example, README with setup steps.
        4. **Learn + ship faster.** Follow the interactive roadmap, chat with the AI tutor about concepts, push to GitHub, or download the ZIP.

        ## Features

        - **Instant full project extraction:** complete folder trees, multiple files, config, tests, not just isolated snippets.
        - **Written guides + key concepts:** structured tutorials with explanations, prerequisites, and learning outcomes auto-generated.
        - **One-click ZIP + GitHub:** download everything or push a ready-to-clone repo directly to your GitHub account in one action.
        - **Interactive learning roadmap:** checklist of steps from the video. Mark progress locally. Know exactly what to do next.
        - **AI tutor in every workspace:** ask questions about the code, concepts, or errors. The AI knows the exact tutorial you watched.
        - **Chrome extension:** extract directly from any YouTube page with a single click.

        ## Free YouExtractor tools

        These pages extract source code, not video SEO tags.


        MD;

        $body .= Format::list($tools) . "\n\n" . <<<MD
        ## Who YouExtractor is for

        Built for people who learn programming from video and then want the code to actually run.

        - Stop pausing a 20-minute tutorial to retype every line. YouExtractor rebuilds the project so you can read working code and focus on the concepts.
        - Turn a framework video into a private reference repo. Keep the file tree, setup notes, and roadmap next to the original tutorial.
        - Ship from a tutorial the same afternoon. Download a ZIP or push a GitHub repo with .gitignore and README already in place.

        ## Frequently asked questions


        MD;

        $body .= Format::faq($faqs) . "\n\n## Get started\n\nStart extracting on YouExtractor with 1 free extraction to test any tutorial: "
            . Format::link('Create an account', '/signup') . '. No credit card required.';

        return new MarkdownPage(
            title: 'YouExtractor: YouTube Code Extractor Tool',
            summary: 'AI tool that extracts complete source code, folders, and a setup guide from any public YouTube coding tutorial. Paste a link. Download a ZIP or push to GitHub.',
            path: '/',
            body: $body,
            updated: Format::fileDate('resources/views/landing.blade.php'),
            related: [
                ['About YouExtractor', '/about'],
                ['Blog', '/blog'],
                ['Support', '/support'],
                ['Chrome extension', config('site.chrome_extension')],
                ['Source on GitHub', 'https://github.com/onyedika-glitch/youextractor'],
            ],
        );
    }
}
