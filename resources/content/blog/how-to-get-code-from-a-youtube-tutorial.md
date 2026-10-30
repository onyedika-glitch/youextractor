---
title: How to Get Code from a YouTube Tutorial (5 Ways That Actually Work)
date: 2026-10-30
excerpt: The instructor wrote the code, but it is trapped in a video. Here are five ways to get it out, from checking the description to extracting the whole project, and when each one makes sense.
reading_time: 7
---

You found a great coding tutorial on YouTube. The instructor builds exactly what you need. Then you realise the code only exists as pixels in a video, and you are about to spend the next hour pausing, squinting and retyping.

Before you do that, try these five methods in order. The first two are free and take a minute. The last one gets you the full project in one step.

## 1. Check the video description and pinned comment

Most instructors who care about their audience link the source code. Look for:

- A **GitHub** or **GitLab** link in the description (expand it with "...more")
- A **pinned comment** from the creator, which is often where repo links end up after the video is published
- Links to **CodePen**, **CodeSandbox**, **StackBlitz** or **Replit** for frontend tutorials
- A link to the creator's website or newsletter, where the code is sometimes behind a free sign-up

If you find a repo, do not just clone the latest version. Tutorials age fast. Look at the commit history and check out the commit closest to the video's upload date, so the code matches what you are watching:

```bash
git clone https://github.com/creator/project.git
cd project
git log --before="2026-03-01" -1   # last commit before the upload date
git checkout <that-commit-hash>
```

## 2. Search the creator's GitHub directly

No link in the description does not mean no repo. Search GitHub for the creator's channel name, then browse their repositories. Repo names usually match the video title ("nextjs-ecommerce", "django-todo-app"). Sorting by "recently updated" around the upload date helps.

You can also search GitHub code for a distinctive string you saw on screen, such as an unusual function name or a comment. If the code is public anywhere, this often finds it.

## 3. Use the transcript for the explanations

YouTube shows a transcript for most videos with captions: open the description and click **Show transcript**. It will not give you the code itself, because people do not read code aloud character by character, but it does give you:

- The exact package names and commands the instructor said ("now run npm install zod")
- The order of steps, which you can paste into your notes as a checklist
- Explanations you can search instead of scrubbing through the timeline

The transcript plus the on-screen code is much easier to follow than the video alone.

## 4. Copy text from a paused frame

When the code is only on screen, you can still avoid typing every character:

- **macOS / iOS:** Live Text lets you select text in a paused video frame or screenshot
- **Windows:** the Snipping Tool's text actions can copy text from a screenshot
- **Android / Chrome:** Google Lens can copy text from an image

This works well for short snippets. It breaks down for real projects: indentation gets mangled, characters like `l`, `1` and `|` get confused, and you still only get the files the instructor happened to show on screen. Config files, `.env` variables and folder structure are usually missing.

## 5. Extract the whole project with an AI tool

The methods above get you snippets. What you usually want is the **project**: every file, the folder structure, the dependencies, and instructions to run it.

That is what [YouExtractor](/tools/extract-code-from-youtube) does. You paste a public YouTube tutorial URL, and it uses the transcript and the code walkthrough to rebuild:

- The complete file tree, not isolated snippets
- Dependency files such as `package.json`, `requirements.txt` or `composer.json`
- A `.env.example` when the video uses environment variables
- A written setup guide and a step-by-step roadmap of the tutorial

You can download it as a ZIP or push it to a new [GitHub repository](/tools/youtube-to-github). It works with public videos that have captions; private and members-only videos are not supported. Every account gets one free extraction, so you can try it on the tutorial you are watching right now. Once you have the files, [check them before you trust them](/articles/the-fastest-way-to-extract-source-code-from-a-youtube-tutorial).

## Which method should you use?

| Situation | Best method |
|---|---|
| The description links a repo | Clone it and check out the commit from the upload date |
| No link, but the creator is active on GitHub | Search their repositories |
| You need one function or snippet | Copy text from a paused frame |
| You want the full project to run and modify | Extract it with YouExtractor |

## Respect the creator

Whatever method you use, the code still belongs to the person who made the tutorial. Keep extracted projects for learning, credit the instructor and link the original video in your README, and do not republish their course material as your own product.

## Related

- [The fastest way to extract source code from a YouTube tutorial](/articles/the-fastest-way-to-extract-source-code-from-a-youtube-tutorial): a deeper guide to verifying extracted code before you trust it
- [How to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project)
- [How to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial)
- [Extract code from YouTube tutorials](/tools/extract-code-from-youtube)
- [Copy code from coding videos](/tools/copy-code-from-video)
