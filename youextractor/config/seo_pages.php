<?php

/**
 * Unique search-landing pages. Each entry has its own title, snippet, H1, and body
 * so Google can show a distinct blue-link result (like competing extractor tools)
 * and rank for developer queries beyond the brand name.
 */
return [
    'tools' => [
        'extract-code-from-youtube' => [
            'title' => 'Extract Code from YouTube Tutorials — Free AI Tool | YouExtractor',
            'description' => 'YouExtractor extracts source code from YouTube coding tutorials — files, folders, README, and a learning roadmap. Free tool. Not a YouTube tags or metadata extractor.',
            'keywords' => 'how to extract code from YouTube tutorial, what is YouTube code extractor, why extract code from YouTube videos, who uses YouTube code extractor, when to extract code from video, where to download extracted project, which frameworks are supported, can I extract code from YouTube, could I convert tutorial to repository, should I use YouExtractor for courses, would it work on long tutorials, does it extract dependencies, do I need to install software, how did YouExtractor rebuild project, extract code from YouTube, YouTube code extractor, YouTube code extractor tool, copy code from YouTube, AI code extractor, programming tutorial to project, YouExtractor, youextractor.me',
            'h1' => 'Extract Code from YouTube Tutorials',
            'eyebrow' => 'Free YouTube code extractor tool',
            'intro' => 'YouExtractor is a free AI tool for developers: paste a public YouTube coding tutorial and get the full project back — not a single snippet, and not the video’s tags. Copy the files, follow the written guide, or push a new GitHub repo.',
            'sections' => [
                [
                    'h2' => 'What this tool copies (and what it does not)',
                    'body' => [
                        'Most “YouTube extractor” results are tag inspectors: they copy the video title, description, SEO tags, and sometimes the thumbnail. That is useful for creators. It is useless if you were trying to follow a programming tutorial.',
                        'This page is the code extractor. YouExtractor reads the transcript and spoken instructions, reconstructs the file tree, and writes a setup guide so you can run the project locally. It does not download the video file and it does not scrape hidden SEO keywords.',
                    ],
                ],
                [
                    'h2' => 'How to extract code from a YouTube tutorial',
                    'body' => [
                        'Open YouExtractor, paste the public video URL, and start an extraction. Public videos with captions work best. Long courses and short tips both work; the model uses on-screen code plus what the instructor says out loud.',
                        'When it finishes you get a workspace: folders, source files, dependencies, a README-style guide, and a checklist roadmap. Download a ZIP or connect GitHub and push a new repository in one click.',
                    ],
                ],
                [
                    'h2' => 'Who uses a YouTube code extractor',
                    'body' => [
                        'Bootcamp students who are tired of pausing every ten seconds. Working engineers who want a reference repo from a conference talk or a framework video. Indie hackers who want to ship from a tutorial the same afternoon.',
                    ],
                ],
            ],
            'bullets' => [
                'Complete folder tree, not isolated snippets',
                'package.json, requirements.txt, composer.json, and .env.example when the video implies them',
                'Step-by-step written guide plus a learning roadmap',
                'ZIP download or one-click GitHub push',
                'Works on public coding tutorials (React, Python, Next.js, Laravel, Docker, and more)',
            ],
            'faqs' => [
                ['q' => 'Can I extract code from any YouTube video?', 'a' => 'Public coding tutorials with captions work. Private, members-only, and videos with no transcript are not supported.'],
                ['q' => 'Is this a YouTube tags extractor?', 'a' => 'No. YouExtractor extracts source code from programming tutorials. It does not copy tags, titles, or SEO metadata.'],
                ['q' => 'Is the YouTube code extractor free?', 'a' => 'Yes. The core extraction tool is free to start. Create an account on youextractor.me and paste a public tutorial URL.'],
            ],
            'related' => ['youtube-to-github', 'ai-code-extractor', 'copy-code-from-video'],
        ],
        'youtube-to-github' => [
            'title' => 'YouTube to GitHub Tool — Push Tutorial Code to a Repo | YouExtractor',
            'description' => 'Turn a YouTube coding tutorial into a GitHub repository. YouExtractor rebuilds the project files, README, and .gitignore, then pushes a new repo in one click.',
            'keywords' => 'YouTube to GitHub, tutorial to GitHub, push tutorial code to GitHub, coding tutorial repository, YouTube tutorial GitHub repo, extract code to GitHub, YouExtractor GitHub, youextractor.me',
            'h1' => 'YouTube to GitHub Tool',
            'eyebrow' => 'Tutorial video → GitHub repository',
            'intro' => 'YouExtractor is the YouTube-to-GitHub tool for developers. Paste a coding tutorial, get a runnable project, and push it as a new GitHub repo with a README and .gitignore already in place — so the video you watched becomes a repo you can clone.',
            'sections' => [
                [
                    'h2' => 'Why tutorial code rarely makes it onto GitHub',
                    'body' => [
                        'You finish a two-hour Next.js video with twenty files in your head and none in git. Copy-pasting from the player drops comments, env vars, and folder structure. By the time the video ends, the “project” is a gist of broken snippets.',
                        'YouExtractor rebuilds the tree first, then lets you push. The GitHub repo is a starting point — not a claim that the original instructor’s copyright disappeared. Credit the creator, keep the repo private if you need to, and treat the code as a learning artifact.',
                    ],
                ],
                [
                    'h2' => 'What gets pushed',
                    'body' => [
                        'Source files, config, a README with setup steps, and a sensible .gitignore for the detected stack. Connect GitHub once. After that, each extraction can become a brand-new public or private repository.',
                    ],
                ],
            ],
            'bullets' => [
                'New repo per extraction — no overwriting an existing project',
                'README generated from the tutorial’s actual steps',
                'Private or public, your choice',
                'Works with React, Python, Node, Laravel, Docker, and other stacks the video teaches',
            ],
            'faqs' => [
                ['q' => 'Does YouExtractor commit to my existing repos?', 'a' => 'No. It creates a new repository for that extraction so tutorial code stays isolated from your real work.'],
                ['q' => 'Do I need a paid GitHub plan?', 'a' => 'No. A free GitHub account is enough. YouExtractor uses the permission you grant to create the new repo.'],
                ['q' => 'Who owns the code?', 'a' => 'Copyright stays with the original creator of the tutorial. YouExtractor does not claim the code. Use it to learn; do not republish someone else’s course as your product.'],
            ],
            'related' => ['extract-code-from-youtube', 'ai-code-extractor', 'learn-programming-faster'],
        ],
        'ai-code-extractor' => [
            'title' => 'AI Code Extractor for Programming Videos | YouExtractor',
            'description' => 'AI code extractor that rebuilds runnable projects from programming videos. File tree, dependencies, written guide, and roadmap — free to start on YouExtractor.',
            'keywords' => 'AI code extractor, AI extract code from video, programming video to code, AI YouTube to code, extract project from tutorial, AI developer tool, YouExtractor, youextractor.me',
            'h1' => 'AI Code Extractor for Programming Videos',
            'eyebrow' => 'AI developer tool',
            'intro' => 'YouExtractor is an AI code extractor: it watches a programming tutorial (via transcript and spoken instructions) and reconstructs a complete codebase. Use it when you would rather run the project than pause-and-type for forty minutes.',
            'sections' => [
                [
                    'h2' => 'How the AI extraction works',
                    'body' => [
                        'The pipeline pulls video metadata and captions, splits the lesson into steps, infers file paths and dependencies, then writes a guide and a checklist. It is built for coding tutorials — React, Python, TypeScript, Laravel, Docker, and similar developer content — not for cooking videos or podcasts.',
                        'Accuracy is highest when the instructor shows real files and talks through setup. Silent loom recordings with no captions give the model less to work with.',
                    ],
                ],
                [
                    'h2' => 'AI extractor vs copy-paste vs tags tools',
                    'body' => [
                        'Copy-paste from a player loses structure. Tag extractors copy SEO metadata. An AI code extractor is the third category: it tries to produce something you can `npm install` or `pip install` and actually run.',
                    ],
                ],
            ],
            'bullets' => [
                'Reconstructs multiple files and folders',
                'Detects stack from the lesson, not from the video title alone',
                'Writes a guide you can reread without rewatching',
                'Free to start at youextractor.me',
            ],
            'faqs' => [
                ['q' => 'Which AI models does YouExtractor use?', 'a' => 'The hosted product uses current code-capable models (including DeepSeek and Claude) depending on the extraction. You interact with the result, not the raw model.'],
                ['q' => 'Does it work on non-YouTube sites?', 'a' => 'Today the extractor is built around public YouTube coding tutorials. The output is still a normal code project you can run anywhere.'],
                ['q' => 'Can I edit the extracted code?', 'a' => 'Yes. Treat it as a starting workspace. Fix names, drop files, and push your own commits.'],
            ],
            'related' => ['extract-code-from-youtube', 'copy-code-from-video', 'youtube-to-github'],
        ],
        'copy-code-from-video' => [
            'title' => 'Copy Code from Coding Videos — No More Pausing | YouExtractor',
            'description' => 'Copy code from programming videos without pausing every ten seconds. YouExtractor rebuilds the full project so you can read working files instead of squinting at the player.',
            'keywords' => 'copy code from video, copy code from coding video, pause coding tutorial, transcribe code from YouTube, get code from programming video, YouExtractor, youextractor.me',
            'h1' => 'Copy Code from Coding Videos',
            'eyebrow' => 'Stop pausing. Start running.',
            'intro' => 'If your study loop is pause, type, rewind, miss a semicolon, you do not need a better playback speed — you need the files. YouExtractor copies the project out of a coding video so you can read it in an editor like any other repo.',
            'sections' => [
                [
                    'h2' => 'Why pausing a coding video is a bad way to learn',
                    'body' => [
                        'Working memory is spent on keystrokes instead of ideas. You remember the instructor’s jokes and forget why the folder is named `lib`. A reconstructed project lets you search, jump to definition, and break things on purpose.',
                        'YouExtractor is for that workflow. It is not a downloader for movies, and it is not a subtitle ripper. It exists to get tutorial source code into your editor.',
                    ],
                ],
                [
                    'h2' => 'A better follow-along loop',
                    'body' => [
                        'Extract the project, skim the generated guide, then watch the video with the repo open. Pause only when a concept is unclear — not because you need to photograph a function off the screen.',
                    ],
                ],
            ],
            'bullets' => [
                'Get every file the tutorial implied, not just the last snippet on screen',
                'Read code in your own editor, with your own font and theme',
                'Use the roadmap checklist so you know which part of the video you are on',
                'Works alongside the Chrome extension on the YouTube watch page',
            ],
            'faqs' => [
                ['q' => 'Can I copy code from a video without an account?', 'a' => 'You need a free YouExtractor account to run a real extraction. The homepage demo shows the flow before you sign up.'],
                ['q' => 'Does this download the video?', 'a' => 'No. Only the extracted project artifacts (files, guide, metadata) are stored in your library.'],
            ],
            'related' => ['follow-along-coding-tutorials', 'extract-code-from-youtube', 'learn-programming-faster'],
        ],
        'learn-programming-faster' => [
            'title' => 'Learn Programming Faster — Turn Tutorials into Projects | YouExtractor',
            'description' => 'Learn programming faster by turning video tutorials into runnable projects. YouExtractor gives you the code, the guide, and a checklist so watching becomes building.',
            'keywords' => 'learn programming faster, tutorial hell, learn to code from videos, coding bootcamp study tool, turn tutorials into projects, learn React faster, learn Python faster, YouExtractor, youextractor.me',
            'h1' => 'Learn Programming Faster',
            'eyebrow' => 'Developer learning tool',
            'intro' => 'The slowest way to learn to code is to watch a tutorial, pause it, retype it, and never run it. YouExtractor speeds up the part that is not learning — the copying — so you spend the hour on the ideas. This page is for people searching how to learn programming faster, not for people who want YouTube tags.',
            'sections' => [
                [
                    'h2' => 'Watching is not the same as building',
                    'body' => [
                        'Tutorial hell is usually a workflow problem. You finish twelve React videos and have zero repos. The fix is not “watch more slowly.” The fix is to leave every lesson with a project you can break.',
                        'YouExtractor extracts the codebase and writes a roadmap. You still have to type the next feature yourself. The tool removes the tax of copying boilerplate so practice starts sooner.',
                    ],
                ],
                [
                    'h2' => 'A study system that actually compounds',
                    'body' => [
                        'Pick one video. Extract it. Run it. Change one thing (rename a route, add a test, swap a color). Push to GitHub. Tomorrow, pick the next video. That loop beats a 40-hour playlist you never finish.',
                        'It works for bootcamp homework, weekend Python projects, interview prep, and “I need to understand Docker this week” panic. The stack can be React, Python, Laravel, or anything else the instructor actually codes.',
                    ],
                ],
            ],
            'bullets' => [
                'Leave each video with a runnable folder, not notes in Notion',
                'Reread the generated guide instead of scrubbing the timeline',
                'Keep a library of past extractions as your personal course wiki',
                'Push to GitHub so your learning history is real commits',
            ],
            'faqs' => [
                ['q' => 'Will this replace doing the exercises?', 'a' => 'No. It replaces retyping boilerplate. You still learn by changing the code and watching it break.'],
                ['q' => 'Is this only for YouTube?', 'a' => 'The extractor currently takes public YouTube tutorial URLs. The learning system — extract, run, modify, commit — is just normal software practice.'],
            ],
            'related' => ['follow-along-coding-tutorials', 'copy-code-from-video', 'youtube-to-github'],
        ],
        'follow-along-coding-tutorials' => [
            'title' => 'Follow Along Coding Tutorials Without Copy-Pasting | YouExtractor',
            'description' => 'Follow along with coding tutorials without copy-pasting from the player. YouExtractor rebuilds the project so you can code along in a real editor.',
            'keywords' => 'follow along coding tutorial, follow along programming video, code along YouTube, bootcamp follow along, copy paste tutorial, YouExtractor, youextractor.me',
            'h1' => 'Follow Along Coding Tutorials Without Copy-Pasting',
            'eyebrow' => 'For bootcamps, courses, and side projects',
            'intro' => 'Following along should mean writing the interesting parts, not transcribing `npx create-next-app` from 480p. YouExtractor rebuilds the tutorial project first, then you follow the lesson with a working tree already on disk.',
            'sections' => [
                [
                    'h2' => 'The follow-along problem',
                    'body' => [
                        'Instructors skip files they created off-camera. They paste from a private repo. They say “same as last time” and you were not here last time. A reconstructed project plus a written guide is the missing layer between the video and your editor.',
                    ],
                ],
                [
                    'h2' => 'How to follow along with YouExtractor',
                    'body' => [
                        'Extract the video. Open the workspace. Watch at 1.25× with the guide on the side. When the instructor introduces a new concept, pause and implement a tiny variation. You are still following along — you are just not fighting the player.',
                    ],
                ],
            ],
            'bullets' => [
                'Roadmap checklist mapped to the lesson',
                'AI tutor in the workspace for “why did they do it that way?” questions',
                'ZIP or GitHub if you want the follow-along on another machine',
            ],
            'faqs' => [
                ['q' => 'Does this work for long Udemy-style courses that were uploaded to YouTube?', 'a' => 'If the upload is a public YouTube video with captions, you can extract it. Multi-hour videos may take longer in the queue.'],
                ['q' => 'Can I follow along on a phone?', 'a' => 'Sign-up works on mobile. The workspace is built for a desktop editor. Extract on your laptop when you can.'],
            ],
            'related' => ['learn-programming-faster', 'copy-code-from-video', 'extract-code-from-youtube'],
        ],
    ],

    'stacks' => [
        'react' => [
            'title' => 'Extract React Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract React code from YouTube tutorials: components, hooks, package.json, and a runnable project. Free AI tool for React, Next.js, and JavaScript videos.',
            'keywords' => 'extract React code, React YouTube tutorial, React hooks tutorial, copy React components, Vite React project, learn React from YouTube, YouExtractor React, youextractor.me',
            'h1' => 'Extract React Code from YouTube Tutorials',
            'eyebrow' => 'React · JavaScript · hooks',
            'intro' => 'React tutorials on YouTube hide half the app in files the instructor never opens. YouExtractor rebuilds the component tree, hooks, and package.json so you can run the lesson in your own Vite or Next.js app instead of pausing on `useEffect`.',
            'sections' => [
                [
                    'h2' => 'Why React videos are hard to copy by hand',
                    'body' => [
                        'A “simple” React lesson often includes a dozen components, a context provider, CSS modules, and three npm libraries mentioned in passing. Miss one import and the whole tree goes red. YouExtractor is built for that mess: it aims to reconstruct the files the instructor would have in VS Code, not just the one function on screen.',
                    ],
                ],
                [
                    'h2' => 'What you typically get from a React tutorial',
                    'body' => [
                        'Component files, hooks, a package.json with the libraries they installed, and a README with `npm install` / `npm run dev`. Pair it with a Next.js video and you also want routing and server components — see the Next.js page if that is the stack.',
                    ],
                ],
            ],
            'bullets' => [
                'Components and hooks as separate files',
                'Dependencies the instructor actually installed',
                'Works for React 18/19, Vite, and Create React App lessons',
                'Push the result to GitHub as a reference app',
            ],
            'faqs' => [
                ['q' => 'Does this work for React Native videos too?', 'a' => 'If it is a public YouTube coding tutorial with a transcript, you can extract it. Native modules and device setup still happen on your machine.'],
                ['q' => 'Can it extract Next.js App Router tutorials?', 'a' => 'Yes — use the Next.js page for App Router-specific notes. React and Next.js lessons overlap; pick the page that matches the video.'],
            ],
            'related_stacks' => ['nextjs', 'javascript', 'typescript'],
            'related_tools' => ['extract-code-from-youtube', 'youtube-to-github'],
        ],
        'python' => [
            'title' => 'Extract Python Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Python code from YouTube tutorials: scripts, requirements.txt, and a runnable project. Free AI tool for Django, Flask, FastAPI, and data videos.',
            'keywords' => 'extract Python code, Python YouTube tutorial, Django tutorial code, Flask tutorial, FastAPI tutorial, pandas tutorial, learn Python from YouTube, YouExtractor Python, youextractor.me',
            'h1' => 'Extract Python Code from YouTube Tutorials',
            'eyebrow' => 'Python · Django · FastAPI',
            'intro' => 'Python tutorials scatter logic across notebooks, `main.py`, hidden `.env` keys, and a requirements file the instructor never shows. YouExtractor rebuilds a project folder you can `pip install -r` and run — so the lesson lives in a venv, not in the watch-later pile.',
            'sections' => [
                [
                    'h2' => 'Python on YouTube vs Python on disk',
                    'body' => [
                        'Data science videos live in Colab. Web videos skip `settings.py`. Automation videos paste five unrelated scripts. YouExtractor tries to turn that into one directory with the modules, requirements, and a short guide for how to run it locally.',
                    ],
                ],
                [
                    'h2' => 'Good fit for these Python lessons',
                    'body' => [
                        'Flask or FastAPI APIs, Django starters, pandas/ETL walkthroughs, and bot tutorials. You still provide API keys. The extractor will not log into your cloud for you.',
                    ],
                ],
            ],
            'bullets' => [
                'requirements.txt or pyproject hints when the video installs packages',
                'Separate modules instead of one giant pasted cell',
                'README with venv + run commands',
                'Optional GitHub push for a clean learning repo',
            ],
            'faqs' => [
                ['q' => 'Can I extract a Jupyter notebook tutorial?', 'a' => 'The tool reconstructs a project. Notebook-heavy videos may land as Python modules plus a guide rather than a perfect `.ipynb` clone.'],
                ['q' => 'Does it support Django?', 'a' => 'Yes. Django tutorials are a strong fit because they have many files (settings, urls, apps) that are painful to copy from a player.'],
            ],
            'related_stacks' => ['javascript', 'docker', 'php'],
            'related_tools' => ['extract-code-from-youtube', 'learn-programming-faster'],
        ],
        'javascript' => [
            'title' => 'Extract JavaScript Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract JavaScript code from YouTube tutorials. Rebuild vanilla JS, Node, and frontend projects with files, npm deps, and a setup guide. Free on YouExtractor.',
            'keywords' => 'extract JavaScript code, JavaScript YouTube tutorial, vanilla JS project, Node JavaScript, copy JS from video, learn JavaScript from YouTube, YouExtractor JavaScript, youextractor.me',
            'h1' => 'Extract JavaScript Code from YouTube Tutorials',
            'eyebrow' => 'JavaScript · Node · vanilla JS',
            'intro' => 'JavaScript tutorials still dominate YouTube: vanilla DOM projects, Node CLIs, webpack rabbit holes. YouExtractor extracts the JS project so you can run it with Node or in the browser instead of photographing `addEventListener` off the screen.',
            'sections' => [
                [
                    'h2' => 'Vanilla JS, Node, or a framework video?',
                    'body' => [
                        'If the lesson is React or Next.js, use those pages — the file layout is different. This page is for JavaScript as JavaScript: `index.html` + `app.js`, Express servers, and Node scripts. The extractor still looks at the transcript to decide which of those you were watching.',
                    ],
                ],
            ],
            'bullets' => [
                'HTML/CSS/JS folders when the tutorial is a landing-page build',
                'package.json for Node lessons',
                'A guide that lists the npm scripts the instructor ran',
            ],
            'faqs' => [
                ['q' => 'Does this replace MDN or a JS course?', 'a' => 'No. It turns a specific video into a project you can study. You still need language docs.'],
            ],
            'related_stacks' => ['typescript', 'react', 'nodejs'],
            'related_tools' => ['copy-code-from-video', 'ai-code-extractor'],
        ],
        'nextjs' => [
            'title' => 'Extract Next.js Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Next.js code from YouTube tutorials: App Router, server components, package.json, and a runnable project. Free AI tool on YouExtractor.',
            'keywords' => 'extract Next.js code, Next.js YouTube tutorial, App Router tutorial, Next.js 15 project, server components, learn Next.js from YouTube, YouExtractor Next.js, youextractor.me',
            'h1' => 'Extract Next.js Code from YouTube Tutorials',
            'eyebrow' => 'Next.js · App Router · React',
            'intro' => 'Next.js tutorials go stale in months (pages vs app, server actions, `src/` or not). YouExtractor rebuilds the App Router tree the instructor actually used so you are not mixing a 2022 pages-directory snippet into a 2026 video.',
            'sections' => [
                [
                    'h2' => 'App Router tutorials need a real file tree',
                    'body' => [
                        '`app/layout.tsx`, route groups, loading UI, and server components do not survive copy-paste from a 16:9 player. YouExtractor is for those videos: the ones where the interesting code is spread across ten tiny files.',
                    ],
                ],
            ],
            'bullets' => [
                'App Router or pages directory, matching the lesson',
                'Env example files when they configure Stripe, Auth, or a DB',
                'README with `npm install` and `npm run dev`',
            ],
            'faqs' => [
                ['q' => 'Can it extract a Next.js + Tailwind + Prisma video?', 'a' => 'Those full-stack videos are a good fit because so many files are created off-screen. You still add your own database URL.'],
            ],
            'related_stacks' => ['react', 'typescript', 'nodejs'],
            'related_tools' => ['youtube-to-github', 'extract-code-from-youtube'],
        ],
        'nodejs' => [
            'title' => 'Extract Node.js Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Node.js code from YouTube tutorials: Express APIs, CLIs, package.json, and env examples. Free AI code extractor for backend videos.',
            'keywords' => 'extract Node.js code, Node.js YouTube tutorial, Express API tutorial, Node backend project, learn Node from YouTube, YouExtractor Node.js, youextractor.me',
            'h1' => 'Extract Node.js Code from YouTube Tutorials',
            'eyebrow' => 'Node.js · Express · APIs',
            'intro' => 'Node tutorials look simple until you realize the instructor created `middleware/auth.js` while talking. YouExtractor reconstructs Express (and other Node) projects so the API runs with `node` or `npm run dev` on your machine.',
            'sections' => [
                [
                    'h2' => 'Backend videos hide the boring files',
                    'body' => [
                        'Error handlers, routers, `.env.example`, and `package.json` scripts are the files you need and the files they skip. The extractor is most useful on REST API builds, webhook tutorials, and “build a bot” Node lessons.',
                    ],
                ],
            ],
            'bullets' => [
                'Route files instead of one `server.js` dump when the lesson is structured',
                'Dependency list from what they installed on camera',
                'GitHub export for a backend reference repo',
            ],
            'faqs' => [
                ['q' => 'Does this deploy the API for me?', 'a' => 'No. You get the project. Deploy is still Render, Fly, Railway, or your VPS.'],
            ],
            'related_stacks' => ['javascript', 'typescript', 'docker'],
            'related_tools' => ['ai-code-extractor', 'youtube-to-github'],
        ],
        'typescript' => [
            'title' => 'Extract TypeScript Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract TypeScript code from YouTube tutorials. Rebuild tsconfig, types, and a runnable TS project from programming videos. Free on YouExtractor.',
            'keywords' => 'extract TypeScript code, TypeScript YouTube tutorial, tsconfig from video, learn TypeScript from YouTube, TS project extractor, YouExtractor TypeScript, youextractor.me',
            'h1' => 'Extract TypeScript Code from YouTube Tutorials',
            'eyebrow' => 'TypeScript · tsconfig · types',
            'intro' => 'TypeScript tutorials fail when `tsconfig.json` never appears on screen. YouExtractor rebuilds the TS project — types, config, and source — so `tsc` or `tsx` can run the lesson without a wall of red squiggles you did not cause.',
            'sections' => [
                [
                    'h2' => 'Types are the first thing copy-paste drops',
                    'body' => [
                        'Instructors say “I’ll add the interface later” and never do. A reconstructed project at least gives you a consistent set of types to argue with. That is more useful than a screenshot of a generic `any`.',
                    ],
                ],
            ],
            'bullets' => [
                'tsconfig when the tutorial is a TS codebase',
                'Works alongside React, Next.js, and Node TypeScript videos',
                'Guide that repeats the compile/run commands they used',
            ],
            'faqs' => [
                ['q' => 'Will the extracted types be perfect?', 'a' => 'No. They follow the video. You should still tighten types as you learn. The point is a compiling starting point.'],
            ],
            'related_stacks' => ['javascript', 'react', 'nextjs'],
            'related_tools' => ['extract-code-from-youtube', 'copy-code-from-video'],
        ],
        'docker' => [
            'title' => 'Extract Docker Compose from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Docker and Docker Compose files from YouTube tutorials. Rebuild Dockerfiles, compose.yaml, and a local stack from devops coding videos.',
            'keywords' => 'extract Docker compose, Docker YouTube tutorial, Dockerfile from video, docker compose.yaml, DevOps tutorial code, learn Docker from YouTube, YouExtractor Docker, youextractor.me',
            'h1' => 'Extract Docker Compose from YouTube Tutorials',
            'eyebrow' => 'Docker · Compose · DevOps',
            'intro' => 'Docker tutorials are a nightmare to copy: one Dockerfile on screen, three services mentioned, a compose file edited off-camera. YouExtractor reconstructs the Docker project so `docker compose up` is closer to what the instructor ran.',
            'sections' => [
                [
                    'h2' => 'Compose files are the real lesson',
                    'body' => [
                        'Networking, volumes, and env files are the parts people pause for. A reconstructed compose stack plus a guide is the difference between “I watched a Docker video” and “Postgres is running on 5432.”',
                    ],
                ],
            ],
            'bullets' => [
                'Dockerfile and compose fragments inferred from the lesson',
                'Notes for the ports and volumes they used',
                'Useful next to Node, Laravel, and Python API videos that also containerize',
            ],
            'faqs' => [
                ['q' => 'Does YouExtractor run Docker for me?', 'a' => 'No. It writes the files. You run Docker Desktop or the engine locally.'],
            ],
            'related_stacks' => ['nodejs', 'laravel', 'python'],
            'related_tools' => ['extract-code-from-youtube', 'youtube-to-github'],
        ],
        'laravel' => [
            'title' => 'Extract Laravel Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Laravel code from YouTube tutorials: routes, controllers, migrations, and composer.json. Free AI tool for PHP and Laravel coding videos.',
            'keywords' => 'extract Laravel code, Laravel YouTube tutorial, Laravel migrations, composer.json from video, PHP Laravel project, learn Laravel from YouTube, YouExtractor Laravel, youextractor.me',
            'h1' => 'Extract Laravel Code from YouTube Tutorials',
            'eyebrow' => 'Laravel · PHP · Artisan',
            'intro' => 'Laravel tutorials create ten Artisan files before the first feature. YouExtractor rebuilds the PHP project — routes, controllers, migrations, composer.json — so you are not transcribing `php artisan make:model` from a video.',
            'sections' => [
                [
                    'h2' => 'Laravel is file-heavy. Players are not.',
                    'body' => [
                        'Providers, FormRequests, policies, and `.env` keys never fit in one take. The extractor is for those course-style Laravel videos where the real app is a tree, not a gist.',
                    ],
                ],
            ],
            'bullets' => [
                'Composer dependencies mentioned in the lesson',
                'Route and controller files instead of one `web.php` dump',
                'Setup guide with `composer install` and `php artisan migrate` when that was the flow',
            ],
            'faqs' => [
                ['q' => 'Does this work for livewire or Inertia videos?', 'a' => 'If it is a public YouTube Laravel tutorial with captions, extract it. You will still install NPM assets the way the instructor did.'],
            ],
            'related_stacks' => ['php', 'docker', 'javascript'],
            'related_tools' => ['extract-code-from-youtube', 'learn-programming-faster'],
        ],
        'php' => [
            'title' => 'Extract PHP Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract PHP code from YouTube tutorials. Rebuild PHP projects, composer files, and setup guides from programming videos. Free on YouExtractor.',
            'keywords' => 'extract PHP code, PHP YouTube tutorial, Composer PHP project, learn PHP from YouTube, PHP code from video, YouExtractor PHP, youextractor.me',
            'h1' => 'Extract PHP Code from YouTube Tutorials',
            'eyebrow' => 'PHP · Composer · backends',
            'intro' => 'PHP tutorials still teach a huge share of the web. YouExtractor extracts the PHP project from the video so you can run it with Composer and a local server instead of copy-pasting functions out of the player.',
            'sections' => [
                [
                    'h2' => 'From “index.php dump” to a project',
                    'body' => [
                        'Older PHP videos are one file. Newer ones are Composer packages with src/, tests, and a README. The extractor follows the lesson you pasted — then you can modernize.',
                    ],
                ],
            ],
            'bullets' => [
                'Composer projects when the tutorial uses them',
                'A written guide with the PHP built-in server or Herd/Valet notes they mentioned',
            ],
            'faqs' => [
                ['q' => 'Should I use the Laravel page instead?', 'a' => 'Yes, if the video is clearly Laravel. Use this page for general PHP, WordPress-adjacent PHP, or Composer libraries.'],
            ],
            'related_stacks' => ['laravel', 'javascript', 'docker'],
            'related_tools' => ['ai-code-extractor', 'youtube-to-github'],
        ],
        'vue' => [
            'title' => 'Extract Vue Code from YouTube Tutorials | YouExtractor',
            'description' => 'Extract Vue.js code from YouTube tutorials: SFCs, Pinia, Vite, and a runnable Vue project. Free AI tool for Vue 3 coding videos.',
            'keywords' => 'extract Vue code, Vue.js YouTube tutorial, Vue 3 SFC, Pinia tutorial, Vite Vue project, Nuxt tutorial, learn Vue from YouTube, YouExtractor Vue, youextractor.me',
            'h1' => 'Extract Vue Code from YouTube Tutorials',
            'eyebrow' => 'Vue 3 · Vite · Pinia',
            'intro' => 'Vue tutorials split the app across `.vue` files, a router, and a store the instructor created off-screen. YouExtractor rebuilds the Vue 3 project so you can run it with Vite instead of pausing on `<script setup>`.',
            'sections' => [
                [
                    'h2' => 'SFCs are awkward to copy from video',
                    'body' => [
                        'Template, script, and style in one file looks easy until there are twelve of them. A reconstructed Vue tree is the point of this page.',
                    ],
                ],
            ],
            'bullets' => [
                'Vue 3 + Vite layouts when that is the lesson',
                'Router and Pinia files if they were part of the tutorial',
            ],
            'faqs' => [
                ['q' => 'Nuxt videos?', 'a' => 'Treat Nuxt as a Vue-family tutorial and extract it. File-based routing is exactly the kind of tree that is painful to transcribe.'],
            ],
            'related_stacks' => ['javascript', 'typescript', 'react'],
            'related_tools' => ['extract-code-from-youtube', 'copy-code-from-video'],
        ],
    ],
];
