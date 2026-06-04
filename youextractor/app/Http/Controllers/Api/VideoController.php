<?php

namespace App\Http\Controllers\Api;

use App\Jobs\ExtractVideoJob;
use App\Models\Video;
use App\Services\CodeExtractorService;
use App\Services\GitHubService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VideoController extends Controller
{
    public function __construct(private CodeExtractorService $codeExtractor)
    {
    }

    // ------------------------------------------------------------------
    // Extract (now queue-backed with caching)
    // ------------------------------------------------------------------

    /**
     * POST /api/videos/extract
     *
     * 1. Validates the URL
     * 2. Checks for a cached result in the DB (Improvement #4 — Cache)
     * 3. If not cached, saves a stub record and dispatches a background job
     * 4. Returns immediately — no more 60-second HTTP timeouts
     */
    public function extract(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'youtube_url'     => 'required|string',
            'force_refresh'   => 'boolean',
        ]);

        $videoId = $this->extractVideoId($validated['youtube_url']);

        if (!$videoId) {
            return response()->json([
                'success' => false,
                'error'   => 'Invalid YouTube URL. Please use a valid YouTube video URL.',
            ], 400);
        }

        // -------- Cache check (skip if force_refresh requested) ----------
        if (!($validated['force_refresh'] ?? false)) {
            $cached = Video::where('youtube_id', $videoId)
                ->where('extraction_status', 'completed')
                ->first();

            if ($cached) {
                return response()->json([
                    'success' => true,
                    'cached'  => true,
                    'message' => 'Retrieved from cache',
                    'data'    => $cached,
                ]);
            }
        }

        // -------- Fetch video metadata -----------------------------------
        $videoData = $this->getVideoMetadata($videoId);

        if (!$videoData) {
            return response()->json([
                'success' => false,
                'error'   => 'Could not fetch video information. The video might be private or unavailable.',
            ], 400);
        }

        // -------- Delete any stale record and create a fresh stub --------
        Video::where('youtube_id', $videoId)->delete();

        $transcript = $this->getTranscript($videoId);

        $video = Video::create([
            'youtube_id'         => $videoId,
            'title'              => $videoData['title'],
            'description'        => $videoData['description'],
            'transcript'         => $transcript,
            'explanation'        => 'Extraction in progress...',
            'summary'            => 'Pending extraction...',
            'duration'           => $videoData['duration'],
            'published_at'       => now(),
            'extracted_at'       => now(),
            'extraction_status'  => 'pending',
        ]);

        // -------- Dispatch background job --------------------------------
        ExtractVideoJob::dispatch($video);

        // If the queue runs synchronously, reload the completed attributes from the DB
        $video->refresh();

        return response()->json([
            'success'    => true,
            'queued'     => true,
            'message'    => 'Extraction started! Poll /api/videos/' . $video->id . '/status to track progress.',
            'data'       => $video,
        ], 202);
    }

    // ------------------------------------------------------------------
    // Status polling (used by the frontend progress bar)
    // ------------------------------------------------------------------

    /**
     * GET /api/videos/{video}/status
     *
     * Returns extraction_status, and full data when completed.
     */
    public function status(Video $video): JsonResponse
    {
        return response()->json([
            'success'    => true,
            'status'     => $video->extraction_status,
            'error'      => $video->extraction_error,
            'data'       => $video->extraction_status === 'completed' ? $video : null,
        ]);
    }

    // ------------------------------------------------------------------
    // GitHub push (Improvement #5)
    // ------------------------------------------------------------------

    /**
     * POST /api/videos/{video}/push-to-github
     *
     * Expects:  { "github_token": "ghp_xxxx" }
     * Creates a new GitHub repo and pushes all extracted files.
     */
    public function pushToGitHub(Request $request, Video $video): JsonResponse
    {
        $validated = $request->validate([
            'github_token' => 'required|string|min:10',
        ]);

        if (empty($video->code_snippets)) {
            return response()->json([
                'success' => false,
                'error'   => 'This video has no extracted code files yet. Please wait for extraction to complete.',
            ], 422);
        }

        $github   = new GitHubService($validated['github_token']);
        $repoName = $this->sanitiseRepoName($video->title);
        $desc     = "Extracted from YouTube: https://youtu.be/{$video->youtube_id} — by YouExtractor";

        $repoUrl = $github->pushProject($repoName, $desc, $video->code_snippets);

        if (!$repoUrl) {
            return response()->json([
                'success' => false,
                'error'   => 'Failed to push to GitHub. Check your token has `repo` scope.',
            ], 500);
        }

        // Persist the URL for the UI to display
        $video->update(['github_repo_url' => $repoUrl]);

        return response()->json([
            'success'      => true,
            'message'      => 'Repository created and code pushed successfully!',
            'github_url'   => $repoUrl,
        ]);
    }

    // ------------------------------------------------------------------
    // Download ZIP
    // ------------------------------------------------------------------

    public function downloadCode(Video $video): BinaryFileResponse|JsonResponse
    {
        $zipPath = storage_path("app/downloads/{$video->youtube_id}.zip");

        if (!file_exists($zipPath)) {
            if (empty($video->code_snippets)) {
                return response()->json([
                    'success' => false,
                    'error'   => 'No code files available for download.',
                ], 404);
            }

            $zipPath = $this->codeExtractor->generateZipFile($video->youtube_id, [
                'stack'              => $video->tech_stack,
                'files'              => $video->code_snippets ?? [],
                'setup_instructions' => $video->setup_instructions ?? '',
                'dependencies'       => $video->dependencies ?? [],
            ]);
        }

        if (!$zipPath || !file_exists($zipPath)) {
            return response()->json(['success' => false, 'error' => 'Failed to generate download file.'], 500);
        }

        $filename = substr(preg_replace('/[^a-zA-Z0-9_-]/', '_', $video->title), 0, 50) . '_code.zip';

        return response()->download($zipPath, $filename);
    }

    // ------------------------------------------------------------------
    // Re-extract
    // ------------------------------------------------------------------

    public function reExtractCode(Video $video): JsonResponse
    {
        $video->update(['extraction_status' => 'pending', 'extraction_error' => null]);
        ExtractVideoJob::dispatch($video);

        return response()->json([
            'success' => true,
            'message' => 'Re-extraction started. Poll /api/videos/' . $video->id . '/status for progress.',
            'data'    => $video,
        ], 202);
    }

    // ------------------------------------------------------------------
    // CRUD
    // ------------------------------------------------------------------

    public function index(): JsonResponse
    {
        return response()->json(
            Video::latest('extracted_at')->paginate(15)
        );
    }

    public function show(Video $video): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $video]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->get('q', '');

        return response()->json(
            Video::where('title', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->latest('extracted_at')
                ->paginate(15)
        );
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    private function extractVideoId(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
            '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com\/v\/)([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
            '/^([a-zA-Z0-9_-]{11})$/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    private function getVideoMetadata(string $videoId): ?array
    {
        try {
            $response = Http::timeout(10)->withoutVerifying()->get('https://www.youtube.com/oembed', [
                'url'    => "https://www.youtube.com/watch?v={$videoId}",
                'format' => 'json',
            ]);

            if ($response->successful()) {
                $d = $response->json();
                return [
                    'title'       => $d['title'] ?? 'Unknown Title',
                    'description' => "Video by {$d['author_name']}",
                    'duration'    => 0,
                    'author'      => $d['author_name'] ?? 'Unknown',
                ];
            }
        } catch (\Exception $e) {
            Log::warning('oEmbed failed: ' . $e->getMessage());
        }

        return null;
    }

    private function getTranscript(string $videoId): string
    {
        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/121',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get("https://www.youtube.com/api/timedtext", [
                    'v'   => $videoId,
                    'lang' => 'en',
                    'fmt'  => 'srv3',
                ]);

            if ($response->successful() && strlen($response->body()) > 50) {
                return $this->cleanTranscript($response->body());
            }

            $transcript = $this->getTranscriptFromPage($videoId);
            if ($transcript && strlen($transcript) > 50) {
                return $transcript;
            }
        } catch (\Exception $e) {
            Log::warning("Transcript fetch failed for {$videoId}: " . $e->getMessage());
        }

        return 'Transcript not available. Extraction based on video title and metadata.';
    }

    private function cleanTranscript(string $text): string
    {
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/\s+/', ' ', $text));
    }

    private function getTranscriptFromPage(string $videoId): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/120',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                ])
                ->get("https://www.youtube.com/watch?v={$videoId}");

            if (!$response->successful()) {
                return null;
            }

            if (preg_match('/"captionTracks":\s*\[(.*?)\]/', $response->body(), $matches)) {
                $tracks = json_decode('[' . $matches[1] . ']', true);
                if (!empty($tracks[0]['baseUrl'])) {
                    $cap = Http::timeout(10)->withoutVerifying()->get($tracks[0]['baseUrl']);
                    if ($cap->successful()) {
                        return $this->cleanTranscript($cap->body());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Page transcript scraper failed: ' . $e->getMessage());
        }
        return null;
    }

    private function sanitiseRepoName(string $title): string
    {
        $name = preg_replace('/[^a-zA-Z0-9_.-]/', '-', $title);
        $name = preg_replace('/-+/', '-', $name);
        $name = trim($name, '-');
        return strtolower(substr($name, 0, 100)) ?: 'youextractor-project';
    }
}
