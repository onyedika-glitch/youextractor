# YouTube Tech Video Extractor & Explainer

A Laravel application that extracts YouTube tech videos, fetches transcripts, and uses AI (OpenAI) to generate detailed explanations and code snippets.

## Features

✅ Extract YouTube video metadata  
✅ Fetch video transcripts  
✅ AI-powered explanations using GPT-4  
✅ Automatic code snippet extraction  
✅ Video search and filtering  
✅ PostgreSQL database (default)  
✅ RESTful API endpoints  

## Tech Stack

- **Backend**: Laravel 11
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **AI**: OpenAI API (GPT-4)
- **YouTube API**: For video metadata
- **PHP**: 8.2+

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (optional, for frontend)
- OpenAI API key
- YouTube API key

### Setup Steps

1. **Clone the repo**
```bash
cd "C:\Users\omogo\Documents\Video web\youextractor"
```

2. **Install dependencies**
```bash
composer install
```

3. **Generate app key**
```bash
php artisan key:generate
```

4. **Copy environment file**
```bash
copy .env.example .env
```

5. **Add API keys to .env**
```env
OPENAI_API_KEY=sk-your-openai-key
YOUTUBE_API_KEY=your-youtube-api-key
```

6. **Create database**
```bash
php artisan migrate
```

7. **Start the server**
```bash
php artisan serve
```

Server runs at `http://localhost:8000`

## API Endpoints

### Extract & Explain Video
**POST** `/api/videos/extract`
```json
{
  "youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ"
}
```

Response:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "youtube_id": "dQw4w9WgXcQ",
    "title": "Video Title",
    "explanation": "...",
    "code_snippets": ["..."],
    "summary": "..."
  }
}
```

### Get All Videos
**GET** `/api/videos`

### Get Single Video
**GET** `/api/videos/{id}`

### Search Videos
**GET** `/api/videos/search?q=laravel`

## Database Schema

```sql
Videos Table:
- id
- youtube_id (unique)
- title
- description
- transcript
- explanation (AI generated)
- code_snippets (JSON array)
- summary
- duration (seconds)
- published_at
- extracted_at
- timestamps
```

## Getting API Keys

### OpenAI API Key
1. Go to https://platform.openai.com
2. Sign up / Login
3. Create API key
4. Add to `.env`

### YouTube API Key
1. Go to https://console.cloud.google.com
2. Create new project
3. Enable YouTube Data API v3
4. Create API key
5. Add to `.env`

## Usage Example

```bash
# Start Laravel server
php artisan serve

# Extract a video (from another terminal)
curl -X POST http://localhost:8000/api/videos/extract \
  -H "Content-Type: application/json" \
  -d '{"youtube_url": "https://www.youtube.com/watch?v=dQw4w9WgXcQ"}'

# Get all videos
curl http://localhost:8000/api/videos

# Search videos
curl "http://localhost:8000/api/videos/search?q=laravel"
```

## File Structure

```
youextractor/
├── app/
│   ├── Models/
│   │   └── Video.php
│   └── Http/Controllers/Api/
│       └── VideoController.php
├── config/
│   ├── database.php
│   ├── services.php
│   └── logging.php
├── database/
│   └── migrations/
│       └── 2024_12_08_create_videos_table.php
├── routes/
│   └── api.php
├── .env.example
├── composer.json
└── README.md
```

## Next Steps

1. **Install youtube-transcript-api** (Python)
   ```bash
   pip install youtube-transcript-api
   ```
   Then create a Python service to fetch transcripts

2. **Create Frontend UI** (React/Vue)
   - Build a dashboard to submit videos
   - Display extracted data
   - Search interface

3. **Add Authentication**
   - User registration
   - Save favorite videos
   - User preferences

4. **Deploy**
   - Deploy to Render / Heroku / Railway
   - Set up environment variables
   - Configure PostgreSQL for production

## Troubleshooting

**API Key not working?**
- Check `.env` file has correct keys
- Run `php artisan config:clear`

**Database not creating?**
- Run `php artisan migrate:fresh`
- Check SQLite file permissions

**OpenAI errors?**
- Verify API key is valid
- Check account has credits
- Review rate limits

## Future Enhancements

- [ ] Real-time transcript fetching
- [ ] Multiple language support
- [ ] Video categorization
- [ ] User accounts & favorites
- [ ] Export to PDF/Markdown
- [ ] Batch video processing
- [ ] Video recommendations
- [ ] Mobile app

## License

MIT License
