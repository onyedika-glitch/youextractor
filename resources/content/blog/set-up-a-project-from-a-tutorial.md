---
title: How to Set Up a Project from a Tutorial (Node, Python and PHP)
date: 2026-10-30
excerpt: You have the tutorial code, but it will not run. Here is the setup checklist instructors skip: versions, dependencies, environment variables, databases and the first run.
reading_time: 7
---

Getting the code is only half the job. Instructors usually start recording with everything already installed, so the setup steps happen off-camera or in five rushed seconds. You end up with the right files and a project that will not start.

This checklist covers what you need to set up a project from a tutorial, whether you cloned a repo, copied the code or extracted it.

## 1. Find out what the project is built with

Look at the root folder. The files tell you the stack:

| File | Stack | Install command |
|---|---|---|
| `package.json` | Node.js / JavaScript / TypeScript | `npm install` (or `pnpm install` / `yarn` if there is a matching lock file) |
| `requirements.txt` or `pyproject.toml` | Python | `pip install -r requirements.txt` |
| `composer.json` | PHP / Laravel | `composer install` |
| `Gemfile` | Ruby / Rails | `bundle install` |
| `go.mod` | Go | `go mod download` |
| `docker-compose.yml` | Docker | `docker compose up` |

If there is a lock file (`package-lock.json`, `pnpm-lock.yaml`, `poetry.lock`, `composer.lock`), use the matching tool. The lock file pins the exact versions the instructor had, which avoids a whole class of errors.

## 2. Use the right language version

This is the most common cause of mysterious install errors.

- **Node:** check `package.json` for an `engines` field, or a `.nvmrc` file. Then run `nvm use`. If neither exists, use the Node version that was current when the video was published.
- **Python:** always use a virtual environment so the project's packages do not clash with others:

```bash
python3 -m venv .venv
source .venv/bin/activate      # Windows: .venv\Scripts\activate
pip install -r requirements.txt
```

- **PHP:** check `composer.json` for the `"php"` requirement and make sure `php -v` matches.

## 3. Create the environment file

Most real projects need secrets and configuration: database URLs, API keys, ports. These live in a `.env` file that is never committed to git, so it is never in the repo and rarely shown on camera.

Look for a `.env.example` file and copy it:

```bash
cp .env.example .env
```

Then fill in each value. If there is no example file, search the code for where it reads configuration: `process.env.` in Node, `os.environ` or `os.getenv` in Python, `env(` in Laravel. Every name you find needs a value.

For API keys (Stripe, OpenAI, Firebase, Supabase and so on), create your own free or test account. Never use keys you see in a video, even if they are visible. They are usually revoked, and using someone else's key is not okay.

## 4. Set up the database

If the project uses a database, it needs to exist before the app can start.

- **SQLite:** usually just a file. The app or migration command creates it.
- **Postgres / MySQL:** install it locally or run it in Docker, then create a database with the name in your `.env`.
- **Hosted (Supabase, PlanetScale, MongoDB Atlas, Firebase):** create a free project and copy the connection string.

Then run the migrations, so the tables exist:

```bash
npx prisma migrate dev        # Prisma
python manage.py migrate      # Django
php artisan migrate           # Laravel
```

## 5. Run it, and read what it says

Find the start command. For Node, look at the `scripts` section of `package.json` (`npm run dev` is the usual one). For Django it is `python manage.py runserver`, for Laravel `php artisan serve`.

When it fails, and the first time it often does, read the first error, not the last one. The first error is usually the cause and the rest are consequences. The most common ones:

- **"Cannot find module" / "ModuleNotFoundError":** dependencies not installed, or installed in a different environment
- **"Port already in use":** another app is running on that port. Stop it or change the port
- **"Connection refused" to a database:** the database is not running, or the URL in `.env` is wrong
- **Undefined environment variable:** a value is missing from `.env`

There is a longer list in [how to fix errors in a coding tutorial](/blog/fix-errors-in-a-coding-tutorial).

## 6. Write down what you did

Once it runs, write the exact steps into a README while you remember them. It takes three minutes and means you will never have to figure this out again.

## Skip the guesswork

If you are starting from a YouTube video rather than a repo, [YouExtractor](/tools/extract-code-from-youtube) rebuilds the project with its dependency files, a `.env.example` when the video uses environment variables, and a written setup guide based on what the instructor actually did. You still run the commands, but you are not guessing which ones.

## Related

- [How to get code from a YouTube tutorial](/blog/how-to-get-code-from-a-youtube-tutorial)
- [How to recreate a coding tutorial project](/blog/recreate-a-coding-tutorial-project)
- [Extract Node.js code from YouTube tutorials](/for/nodejs)
- [Extract Python code from YouTube tutorials](/for/python)
- [Extract Laravel code from YouTube tutorials](/for/laravel)
