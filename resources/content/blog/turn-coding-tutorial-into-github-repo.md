---
title: How to Turn a YouTube Tutorial into a GitHub Project
date: 2026-08-25
excerpt: Stop leaving tutorials in watch-later. Get the code, make it run, keep your secrets out, write a README and push a GitHub repo you can clone, show and build on, step by step.
reading_time: 11
image: /img/blog/turn-coding-tutorial-into-github-repo/cover.png
image_alt: Hand-drawn sketch of a video player with an arrow pointing to a repository card with a branch icon, folder and files
---

You watched a useful coding tutorial. You followed along, maybe the app even worked. A week later you want to reuse what you learned, and all you have is a YouTube link and a vague memory of which file the database code was in. You cannot search a video timeline. You cannot jump to a function definition in a talking head.

If a tutorial mattered enough to watch, it matters enough to keep. The right place to keep it is a GitHub repository: searchable, versioned, cloneable on any machine, and ready to build on. This guide walks through the whole process, from getting the code to a clean, credited repo you will actually use again.

> **Quick answer:** get the project onto your machine and make sure it runs. Add a `.gitignore` *before* your first commit so `.env` files and `node_modules` never leave your computer. Write a README with the video link, the instructor's name, the run commands and what you changed. Then create a new repository (private if you are unsure about the licence) and push.

![Five steps from tutorial to GitHub: get the code, make it run, add .gitignore, write the README, push.](/img/blog/turn-coding-tutorial-into-github-repo/repo-steps.webp)
*Do the steps in this order. Skipping step 3 is how API keys end up public.*

## Why put tutorial projects on GitHub

It is tempting to treat a tutorial as something you watch once. Putting the result on GitHub changes what you get out of it:

- **You can find it again.** Search your own repositories in seconds instead of re-watching a 90-minute video to find one function.
- **You keep it if the video disappears.** Videos get deleted, made private, re-uploaded or replaced by "updated for 2027" versions with different code.
- **You can build on it.** A working repo is a starting point for your own projects. Clone it, branch it and add the feature you actually needed.
- **You can see what you changed.** Git history shows the difference between the tutorial's code and your own work, which is exactly what you want to remember.
- **It can show your work.** A tutorial project you extended, with a clear README, is a reasonable early portfolio piece. A straight copy is not, more on that below.

## Step 1: Get the code onto your machine

You need the project locally before anything else. There are three routes, in order of effort:

1. **Use the instructor's repository.** Check the description and the pinned comment for a link. If there is one, **fork** it on GitHub (so you have your own copy you can push to), then clone your fork. If you want the code exactly as it was in the video, check out the last commit before the upload date.
2. **Recreate it while following along.** Slow, but you learn the structure as you build it. See [how to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project) for a method that does not stall halfway.
3. **Extract it.** A [YouTube to GitHub tool](/tools/youtube-to-github) like YouExtractor rebuilds the files, folders and dependencies from a public tutorial that has captions.

Every option, including searching the creator's GitHub and copying from paused frames, is covered in detail in [how to get code from a YouTube tutorial](/blog/how-to-get-code-from-a-youtube-tutorial).

![YouExtractor's File Explorer showing 14 files from a React tutorial, with package.json open.](/img/blog/turn-coding-tutorial-into-github-repo/youextractor-file-explorer.webp)
*An extracted React tutorial: 14 files, including the dependency file and config, ready to become a repo.*

## Step 2: Make sure it runs

Do not push a project you have never run. A repo that runs is worth ten that do not, and "I'll fix it later" rarely happens once the code is on GitHub.

Before your first commit:

1. **Install the dependencies** with the project's package manager: `npm install`, `pip install -r requirements.txt`, `composer install` and so on.
2. **Create your `.env` file** from `.env.example` and fill in your own values.
3. **Set up the database** and run any migrations, if the project has one.
4. **Start the project** and check that it does what the video showed.

If anything fails here, [how to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial) covers versions, environment files and databases, and [how to fix errors in a coding tutorial](/blog/fix-errors-in-a-coding-tutorial) covers the most common errors.

## Step 3: Add a .gitignore before your first commit

This is the step people skip, and it is the one that causes real damage. Without a `.gitignore`, `git add .` happily commits everything in the folder, including:

- **`.env`**, which contains your API keys, database passwords and secret tokens
- **`node_modules/`, `vendor/` or `.venv/`**, which can be hundreds of megabytes and are easy to reinstall
- **Build output** like `dist/` and `build/`, which is generated from your source anyway
- **Editor and OS files** like `.DS_Store` and `.idea/`

![Two columns: files that go in the repo (src, package.json, lock file, .env.example, README, .gitignore) and files .gitignore keeps out (.env, node_modules, .venv, vendor, dist, build, logs, OS files).](/img/blog/turn-coding-tutorial-into-github-repo/gitignore-split.webp)
*Commit the source and the instructions. Keep out anything huge, generated or secret.*

When you create a repository on GitHub, you can pick a ready-made `.gitignore` template for your language. If you are creating the file yourself, this covers most tutorial projects:

```gitignore
# Secrets
.env
.env.local

# Dependencies
node_modules/
vendor/
.venv/

# Build output
dist/
build/
.next/

# Logs and OS/editor files
*.log
.DS_Store
.idea/
.vscode/
```

Note that `.env.example` is **not** ignored. It should be committed, because it tells people which settings the project needs, without the values.

### If you already committed a secret

It happens to everyone once. Deleting the file in a new commit is **not** enough: the secret is still in the git history, and anyone who clones the repo can find it. Bots scan public GitHub repositories for leaked keys constantly.

Do this instead, in this order:

1. **Revoke or rotate the key immediately** in the provider's dashboard (Stripe, OpenAI, AWS, Firebase and so on). This is the only step that actually makes you safe.
2. Add the file to `.gitignore` and remove it from tracking with `git rm --cached .env`.
3. Put the new key only in your local `.env`.

Rewriting history to remove the file is possible, but once a key has been pushed, treat it as exposed regardless. Rotating it is what matters.

## Step 4: Write a README you will thank yourself for

A tutorial repo without a README is a folder of code you will not understand in six months. A good README for a tutorial project answers four questions:

![A tutorial README with four highlighted sections: what is this, where is it from, how do I run it, and what did I change.](/img/blog/turn-coding-tutorial-into-github-repo/readme-anatomy.webp)
*Four sections cover everything your future self, or a recruiter, will ask.*

Here is a complete template you can copy:

```markdown
# Task Tracker (React)

A to-do app with filters, built with React, Vite and Tailwind,
using json-server as a mock backend.

## Source

Built by following "React Crash Course" by <instructor name>:
https://www.youtube.com/watch?v=<video-id>

All credit for the original project goes to the instructor.
This repo is my learning copy.

## Run it

Requirements: Node.js 18 or newer.

    npm install
    cp .env.example .env
    npm run server   # starts the mock API on port 5000
    npm run dev      # starts the app on http://localhost:5173

## What I changed

- Added due dates and sorting by date
- Replaced json-server with a SQLite backend
- Added tests for the filter logic

## What I learned

- How useEffect handles data fetching and cleanup
- Lifting state up vs using context
```

The "What I changed" and "What I learned" sections are the most valuable parts. They turn a copy of someone else's tutorial into a record of your own work.

## Step 5: Create the repository and push

### Option A: The GitHub website

1. Go to github.com and click **New repository**.
2. Give it a clear name, such as `react-task-tracker`, rather than `tutorial-1`.
3. Choose **Private** or **Public** (see the next section if you are unsure).
4. Leave "Add a README" unticked if you already have one locally, to avoid a merge conflict on your first push.
5. Click **Create repository**, then run the commands GitHub shows you from your project folder:

```bash
git init
git add .
git status            # check that .env and node_modules are NOT listed
git commit -m "Initial commit: React task tracker from tutorial"
git branch -M main
git remote add origin https://github.com/<you>/react-task-tracker.git
git push -u origin main
```

Always run `git status` before your first commit. If you see `.env` or `node_modules` in the list, stop and fix your `.gitignore` first.

### Option B: The GitHub CLI

If you have the [GitHub CLI](https://cli.github.com) installed and signed in (`gh auth login`), you can create the repo and push in one command from your project folder, after your first commit:

```bash
git init
git add .
git commit -m "Initial commit: React task tracker from tutorial"
gh repo create react-task-tracker --private --source=. --push
```

Swap `--private` for `--public` when you are ready to share it.

### Option C: Push straight from YouExtractor

If you extracted the project with [YouExtractor](/tools/youtube-to-github), you can skip most of this. Connect GitHub once, and each extraction can be pushed as a **new** repository, public or private, with a README containing the setup steps and a `.gitignore` for the detected stack already in place. A new repo per extraction means tutorial code never ends up mixed into your real projects. You can still clone it afterwards and add your own README sections.

## Private or public? Licences and credit

The instructor owns the code they wrote. Putting it on GitHub does not change that, so a few rules keep you on the right side of the creator:

| Situation | What to do |
|---|---|
| The instructor's repo has an open-source licence (MIT, Apache, etc.) | Public is usually fine. Keep the licence file and credit them in the README |
| The instructor's repo has no licence | Keep your copy private. No licence means all rights are reserved by default |
| The code is from a paid course | Keep it private. Do not redistribute paid material |
| You rebuilt or extracted it yourself, with no original repo | Credit the video and instructor. Private if unsure, public once you have made substantial changes of your own |

If in doubt, start private. You can make a repository public later with one click, but you cannot un-publish something someone has already cloned.

## Make it yours

A repository that only contains the tutorial's code is a bookmark. To turn it into a project, change it:

- **Add a feature** the tutorial did not cover: search, pagination, authentication, dark mode.
- **Swap a piece of the stack:** a different database, a different CSS approach, TypeScript instead of JavaScript.
- **Write tests** for the core logic. It forces you to understand what the code actually does.
- **Deploy it.** Free tiers on platforms like Vercel, Netlify or Render are enough for most tutorial projects, and a live link makes the README far more convincing.

Commit each change separately with a clear message. Your git history then tells the story: here is the tutorial, here is everything I built on top of it.

## Using tutorial projects in your portfolio

Recruiters and hiring managers see a lot of tutorial clones, and they can usually tell. A tutorial project helps your portfolio only when it shows something beyond the tutorial:

- **Be upfront.** Say in the README which tutorial it started from. Hiding it looks worse than admitting it.
- **Lead with your changes.** Put "What I changed" near the top, and make the changes substantial.
- **Pin your best one or two.** GitHub lets you pin up to six repositories on your profile. Pin extended projects, not raw copies.
- **Keep it tidy.** A clear name, a short description, topics (tags) and a live demo link make a repo look maintained.

## Frequently asked questions

### Can I put a YouTube tutorial's code on GitHub?

For your own learning, a private repository is fine. For a public repository, check whether the instructor published the code under an open-source licence, credit them clearly, and keep the repo private if there is no licence or the code is from a paid course.

### Should I fork the instructor's repo or create a new one?

Fork it if you want to keep a link to the original and possibly pull in their updates. Create a new repository if you rebuilt the project yourself or want a clean history that starts with your own work. Either way, credit the original in the README.

### I committed my .env file. What do I do?

Revoke or rotate every key in it immediately, in each provider's dashboard. Then add `.env` to `.gitignore` and run `git rm --cached .env`. The old values stay in the history, so rotating the keys is what actually protects you.

### What should I name the repository?

Describe what it is, not where it came from: `react-task-tracker` or `django-blog-api` rather than `tutorial-project-3`. Put the tutorial name in the description and README instead.

### Can I do all of this without using the command line?

Mostly, yes. You can create repositories on the GitHub website, and GitHub Desktop handles committing and pushing with buttons. YouExtractor can also push an extracted project straight to a new repository. Learning the basic git commands is still worth it, because you will need them as soon as you work on a team.

## Related

- [How to get code from a YouTube tutorial](/blog/how-to-get-code-from-a-youtube-tutorial)
- [How to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial)
- [How to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project)
- [YouTube to GitHub tool](/tools/youtube-to-github)
- [How YouExtractor's pipeline works](/blog/how-we-turn-youtube-tutorials-into-code)
