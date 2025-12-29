# 🎬 YouExtractor

**Turn YouTube coding tutorials into full courses and working code projects instantly using AI.**

[![Modules](https://img.shields.io/badge/STATUS-ACTIVE-374151?style=for-the-badge&labelColor=374151&color=16a34a)]()  [![Level](https://img.shields.io/badge/VERSION-2.0-374151?style=for-the-badge&labelColor=374151&color=0284c7)]()  [![Type](https://img.shields.io/badge/LICENSE-MIT-374151?style=for-the-badge&labelColor=374151&color=ea580c)]()

YouExtractor is a powerful Laravel application that transforms passive video watching into active learning. It extracts video metadata, fetches transcripts, and uses advanced AI (Gemini Pro/GPT-4) to generate comprehensive written tutorials, code snippets, and complete runnable projects from YouTube videos.

---

## 🚀 Features

*   **🤖 AI-Powered Extraction**: Automatically converts video content into structured learning guides.
*   **📄 Full Source Code**: Generates complete, downloadable project files (ZIP) from video tutorials.
*   **📚 Comprehensive Guides**: Creates detailed "blog-post" style tutorials with key concepts and learning outcomes.
*   **🧩 Chrome Extension**: Start extractions directly from YouTube with a single click.
*   **🔍 Search & Filter**: Easily find previously extracted videos in your library.
*   **⚡ Modern UI**: Built with TailwindCSS and a sleek dark mode design.
*   **💾 Database Integration**: Stores all extractions in PostgreSQL/MySQL for easy retrieval.

---

## 🛠️ Technology Stack

<div align="center">

**Backend:** Laravel 11 • PHP 8.2+ • MySQL/PostgreSQL
**Frontend:** Blade Templates • TailwindCSS • Alpine.js
**AI Services:** Google Gemini Pro • OpenAI GPT-4
**Tools:** Composer • Node.js • Docker

</div>

---

## 📂 Project Structure

The project is organized to be scalable and maintainable:

| Directory | Description |
|------|-------------|
| `app/Services/CodeExtractorService.php` | Core logic for interacting with AI and parsing transcripts. |
| `app/Http/Controllers/Api/VideoController.php` | Handles API requests for video extraction and data management. |
| `resources/views/index.blade.php` | The main dashboard UI for the application. |
| `chrome-extension/` | Source code for the companion Chrome Extension. |
| `routes/web.php` | Application route definitions. |

---

## 🚀 Getting Started

### Prerequisites
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   Database (MySQL/PostgreSQL)
*   Google Gemini API Key (or OpenAI Key)

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/youextractor.git
cd youextractor
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```
*Edit `.env` and add your database credentials and `GEMINI_API_KEY`.*

### Step 4: Run Migrations
```bash
php artisan migrate
```

### Step 5: Start the Server
```bash
php artisan serve
npm run dev
```

Visit `http://localhost:8000` to start extracting!

---

## 🧩 Chrome Extension

1.  Go to `chrome://extensions/`
2.  Enable **Developer Mode**.
3.  Click **Load Unpacked**.
4.  Select the `youextractor/chrome-extension` folder.
5.  Go to any YouTube video and click the YouExtractor icon to extract code instantly!

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

> *Built with ❤️ by YouExtractor Team*
