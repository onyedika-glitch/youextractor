---
title: How to Fix Errors in a Coding Tutorial (When It Works for Them but Not for You)
date: 2026-10-30
excerpt: You followed the tutorial exactly and still got an error. Here is a step-by-step way to find the cause, plus the eight errors that break tutorial projects most often.
reading_time: 8
---

You typed exactly what the instructor typed. Their app works. Yours shows a red error. This is the most frustrating moment in learning to code, and the most common reason people give up on a tutorial.

The good news: tutorial errors are rarely random. Most come from a short list of causes, and there is a reliable way to find which one you have.

## Step 1: Read the error properly

It sounds obvious, but most people glance at a wall of red text and go straight to Google. Slow down:

- **Find the first error,** not the last. In long output, the first error is usually the cause and the rest are knock-on effects.
- **Find the file and line number.** Stack traces point to it. Ignore lines inside `node_modules` or `site-packages` and look for the first line in *your* code.
- **Read the actual message.** "Cannot read properties of undefined (reading 'map')" tells you exactly what happened: something you expected to be a list was `undefined`.

## Step 2: Compare with the tutorial at that exact point

Go back to the timestamp where the instructor wrote the code that is failing, and compare character by character. Look especially for:

- Typos in names (`userId` vs `userID`) and file paths
- A missing `import` at the top of the file
- An `export default` vs a named export
- Code the instructor added off-camera, which is often visible in a later frame

If the instructor published a repo, compare your file with theirs. A diff tool makes this fast: paste both versions into your editor's compare view.

## Step 3: Check the comments for that timestamp

If an error happens to you, it happened to hundreds of other viewers. Search the video's comments for the error message or for "doesn't work". Creators often pin corrections, and other viewers post fixes.

## Step 4: Search the exact error message

Copy the core of the message, remove anything specific to you (your file paths, your variable names), and search it in quotes. Add the framework name: `"Hydration failed because" nextjs`. Results from GitHub issues and the official docs are usually more reliable than random forum answers.

## The 8 most common tutorial errors

### 1. Version mismatch

The tutorial used version 4, you installed version 6, and an API changed. This is the number one cause. Check the upload date and the versions in the video, then either install the same versions or follow the library's migration guide.

### 2. Missing dependencies

"Cannot find module", "ModuleNotFoundError", "Class not found". You either did not run the install command, installed into the wrong environment (Python without the virtual environment activated is a classic), or the instructor installed a package off-camera. Check the transcript for package names.

### 3. Missing environment variables

The app expects an API key or database URL from a `.env` file that is not in the video. Look for `.env.example`, or search the code for `process.env` / `os.environ` / `env(` to see what it needs.

### 4. Database not set up

"Connection refused", "relation does not exist", "no such table". The database is not running, or you skipped the migration step. See [how to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial).

### 5. Deprecated tools

Some tools in older tutorials are no longer maintained. Create React App, for example, was officially deprecated in 2025, and new React projects now start with a framework or Vite. If the tutorial's setup command fails or prints deprecation warnings, use the current recommended setup and continue with the tutorial's code.

### 6. Framework conventions changed

Frameworks change how projects are structured. Next.js moved from the `pages/` folder to the App Router, for example, so a component in the wrong folder silently does nothing. Check that your file structure matches the version you installed.

### 7. Operating system differences

Shell commands differ between macOS, Linux and Windows. `rm -rf`, `export VAR=value` and `source` do not work in Windows Command Prompt. Use WSL or Git Bash on Windows, or look up the PowerShell equivalent.

### 8. The instructor fixed something off-camera

Sometimes the instructor's code works because they fixed a bug between cuts. If your code matches the screen exactly and still fails, skip ahead a few minutes in the video. The fix is often visible later, or mentioned in the comments.

## Step 5: Isolate the problem

If none of the above solves it, shrink the problem. Comment out code until the error disappears, then add it back piece by piece. Add a `console.log` or `print` just before the failing line to see the actual values. When you can describe the problem in one sentence ("this array is empty when the page loads"), the fix is usually close.

## Step 6: Ask for help the right way

If you are still stuck, post a question with: the exact error, the code around the failing line, the versions you installed, the tutorial link and timestamp, and what you already tried. Questions with that information get answered. "It doesn't work, help" does not.

## Get help that knows the tutorial

Generic AI chat does not know which video you are following. In [YouExtractor](/tools/extract-code-from-youtube), every extracted project comes with an AI tutor that knows the exact tutorial you watched, so you can paste an error and ask what is wrong in the context of that project. The extracted project also includes the dependency files and a setup guide, which prevents several of the errors above before they happen.

## Related

- [How to set up a project from a tutorial](/blog/set-up-a-project-from-a-tutorial)
- [How to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project)
- [How to learn from coding tutorials without retyping code](/blog/learn-from-coding-tutorials-without-retyping-code)
