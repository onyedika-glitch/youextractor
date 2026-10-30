---
title: How to Turn a YouTube Tutorial into a GitHub Project
date: 2026-08-25
excerpt: Stop leaving tutorials in watch-later. Get the code, organise it, write a README and push a GitHub repo you can clone, show and build on, with or without YouExtractor.
reading_time: 7
---

You watched a useful coding tutorial. A week later you want to reuse what you learned, and all you have is a YouTube link and a vague memory. You cannot search a video timeline. You cannot jump to a function definition in a talking head.

If a tutorial mattered, it belongs in git. Here is how to turn a YouTube tutorial into a proper GitHub project.

## Why put tutorial projects on GitHub

- **You can find it again.** Search your repos instead of re-watching a 90-minute video.
- **You can build on it.** A repo is a starting point for your own projects.
- **It shows your work.** A tutorial project you extended, with a clear README, is a reasonable early portfolio piece. A straight copy is not, so see "Make it yours" below.
- **You keep it if the video disappears.** Videos get deleted, made private or replaced.

## Step 1: Get the code

You need the project on your machine first. In order of effort:

1. **The instructor's repo.** Check the description and pinned comment. If it exists, you can fork it on GitHub and work from your fork.
2. **Recreate it while following along.** Slow, but you learn the structure. See [how to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project).
3. **Extract it.** A [YouTube to GitHub tool](/tools/youtube-to-github) like YouExtractor rebuilds the files, folders and dependencies from a public tutorial with captions.

All the options are covered in [how to get code from a YouTube tutorial](/blog/how-to-get-code-from-a-youtube-tutorial).

## Step 2: Make sure it runs

Do not push a project you have never run. Install the dependencies, set up the `.env` file and database if needed, and start it. If it does not work, fix it first; [how to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial) covers the usual steps. A repo that runs is worth ten that do not.

## Step 3: Add a .gitignore before your first commit

This is the step people skip, and it is the one that causes real problems. Without a `.gitignore`, you will commit:

- `node_modules/`, `vendor/` or `.venv/`, which are huge and easy to recreate
- `.env`, which contains **your API keys and passwords**
- Build output and editor files

GitHub has ready-made templates for every language, and you can pick one when you create the repo. At minimum:

```
node_modules/
vendor/
.venv/
.env
dist/
build/
```

If you ever do commit a secret, deleting the file is not enough, because it stays in the git history. Revoke the key with the provider and create a new one.

## Step 4: Write a README

A good tutorial repo README answers four questions:

1. **What is this?** One sentence about the project.
2. **Where is it from?** The original video link and the instructor's name. Always credit the creator.
3. **How do I run it?** The exact commands: install, environment setup, start.
4. **What did I change?** The parts you added or modified after the tutorial.

That last section is what turns a copy into your project.

## Step 5: Create the repo and push

Create a new repository on GitHub (start with **private** if you are unsure about the licence), then from the project folder:

```bash
git init
git add .
git commit -m "Initial commit: project from <tutorial name>"
git branch -M main
git remote add origin https://github.com/<you>/<repo>.git
git push -u origin main
```

Run `git status` before `git add .` to check that nothing in your `.gitignore` list is about to be committed.

## The faster way

If you are starting from a video with no repo, [YouExtractor](/tools/youtube-to-github) does steps 1, 3 and 4 for you. It rebuilds the project from a public YouTube coding tutorial, generates a README with the setup steps and a `.gitignore` for the stack, and pushes it to a **new** GitHub repository in one click, public or private. A new repo each time means tutorial code never ends up mixed into your real projects.

You still run it, and you still make it yours.

## Make it yours, and respect the creator

The instructor owns the code they wrote. Credit them in the README, keep the repo private if the licence is unclear, and do not present their tutorial as your original work. The best way to make a tutorial project genuinely yours is to change it: add a feature, swap a technology, write tests, deploy it. Then the repo shows what you can do, not just what you watched.

## Related

- [YouTube to GitHub tool](/tools/youtube-to-github)
- [How to get code from a YouTube tutorial](/blog/how-to-get-code-from-a-youtube-tutorial)
- [How to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial)
- [How YouExtractor's pipeline works](/blog/how-we-turn-youtube-tutorials-into-code)
