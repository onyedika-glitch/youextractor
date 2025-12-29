# 🎬 YouExtractor

**Turn YouTube coding tutorials into full courses and working code projects instantly using AI.**

[![Modules](https://img.shields.io/badge/STATUS-ACTIVE-374151?style=for-the-badge&labelColor=374151&color=16a34a)]()  [![Level](https://img.shields.io/badge/VERSION-2.0-374151?style=for-the-badge&labelColor=374151&color=0284c7)]()  [![Type](https://img.shields.io/badge/LICENSE-MIT-374151?style=for-the-badge&labelColor=374151&color=ea580c)]()

YouExtractor is a powerful Laravel application that transforms passive video watching into active learning. It extracts video metadata, fetches transcripts, and uses advanced AI (Gemini Pro/GPT-4) to generate comprehensive written tutorials, code snippets, and complete runnable projects from YouTube videos.

---

## 📖 Overview

YouExtractor solves the problem of "tutorial hell" by converting video information into structured, actionable data. Instead of pausing and rewinding to copy code, YouExtractor provides you with the complete source code, a detailed written guide, and a structured learning path in seconds. It allows you to download the entire project as a ZIP file, ready to run.

---

## 🚀 Features

*   **🤖 AI-Powered Extraction**: Automatically converts video content into structured learning guides using state-of-the-art AI models.
*   **📄 Full Source Code**: Generates complete, downloadable project files (ZIP) from video tutorials.
*   **📚 Comprehensive Guides**: Creates detailed "blog-post" style tutorials with key concepts and learning outcomes.
*   **🧩 Chrome Extension**: Start extractions directly from YouTube with a single click.
*   **🔍 Search & Filter**: Easily find previously extracted videos in your library.
*   **⚡ Modern UI**: Built with TailwindCSS and a sleek dark mode design for comfortable reading.
*   **💾 Database Integration**: Stores all extractions in PostgreSQL/MySQL for easy retrieval.

---

## 📸 Screenshots

![Dashboard](https://placehold.co/1200x600/1f2937/purple?text=Dashboard+UI)
*The main dashboard where you can manage your extracted videos.*

![Extraction View](https://placehold.co/1200x600/1f2937/blue?text=Extraction+Results)
*Detailed view of an extracted video with code, guide, and summary.*

---

## 🛠️ Technology Stack

<div align="center">

**Backend:** Laravel 11 • PHP 8.2+ • MySQL/PostgreSQL
**Frontend:** Blade Templates • TailwindCSS • Alpine.js
**AI Services:** Google Gemini Pro • OpenAI GPT-4
**Tools:** Composer • Node.js • Docker

</div>

---

## 🚀 Getting Started

### Prerequisites
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   Database (MySQL/PostgreSQL)
*   Google Gemini API Key (or OpenAI Key)

### Installation

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/onyedika-glitch/youextractor.git
    cd youextractor
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Configure Environment**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Edit `.env` and add your database credentials and `GEMINI_API_KEY`.*

4.  **Run Migrations**
    ```bash
    php artisan migrate
    ```

5.  **Start the Server**
    ```bash
    php artisan serve
    npm run dev
    ```

---

## 🧩 Chrome Extension

1.  Go to `chrome://extensions/`
2.  Enable **Developer Mode**.
3.  Click **Load Unpacked**.
4.  Select the `youextractor/chrome-extension` folder.
5.  Go to any YouTube video and click the YouExtractor icon to extract code instantly!

---

## 👨‍💻 Founder

**Built and maintained by me as founder.**

YouExtractor was created to solve my own need for better learning tools. I am passionate about making technical education more accessible and efficient.

---

> *Project maintained by @onyedika-glitch*
