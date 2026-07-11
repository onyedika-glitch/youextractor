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
                ->where('user_id', auth()->id())
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

            // Clone from another user if exists!
            $otherCached = Video::where('youtube_id', $videoId)
                ->whereNotNull('extracted_at')
                ->where('extraction_status', 'completed')
                ->first();

            if ($otherCached) {
                $video = $otherCached->replicate();
                $video->user_id = auth()->id();
                $video->save();

                return response()->json([
                    'success' => true,
                    'cached'  => true,
                    'message' => 'Retrieved from public cache',
                    'data'    => $video,
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
        Video::where('youtube_id', $videoId)->where('user_id', auth()->id())->delete();

        $transcript = $this->getTranscript($videoId);

        $video = Video::create([
            'user_id'            => auth()->id(),
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
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

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
     * Expects:  { "github_token": "ghp_xxxx", "repo_name": "...", "description": "...", "private": false }
     * Creates a new GitHub repo and pushes all extracted files.
     */
    public function pushToGitHub(Request $request, Video $video): JsonResponse
    {
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

        $validated = $request->validate([
            'github_token' => 'required|string|min:10',
            'repo_name'    => 'nullable|string|max:100',
            'description'  => 'nullable|string|max:255',
            'private'      => 'nullable|boolean',
        ]);

        if (empty($video->code_snippets)) {
            return response()->json([
                'success' => false,
                'error'   => 'This video has no extracted code files yet. Please wait for extraction to complete.',
            ], 422);
        }

        $github   = new GitHubService($validated['github_token']);
        $repoName = !empty($validated['repo_name']) ? $this->sanitiseRepoName($validated['repo_name']) : $this->sanitiseRepoName($video->title);
        $desc     = !empty($validated['description']) ? $validated['description'] : "Extracted from YouTube: https://youtu.be/{$video->youtube_id} — by YouExtractor";
        $isPrivate = filter_var($validated['private'] ?? false, FILTER_VALIDATE_BOOLEAN);

        $repoUrl = $github->pushProject($repoName, $desc, $video->code_snippets, $isPrivate);

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
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

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
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

        $video->update([
            'extraction_status' => 'pending',
            'extraction_error' => null,
            'transcript' => null,
            'code_snippets' => null,
        ]);
        ExtractVideoJob::dispatch($video);

        return response()->json([
            'success' => true,
            'message' => 'Re-extraction started. Poll /api/videos/' . $video->id . '/status for progress.',
            'data'    => $video,
        ], 202);
    }

    // ------------------------------------------------------------------
    // Chat Copilot
    // ------------------------------------------------------------------

    /**
     * POST /api/videos/{video}/chat
     *
     * Asks a question to the AI about the video code or overview.
     */
    public function chat(Request $request, Video $video): JsonResponse
    {
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $answer = $this->codeExtractor->chatAboutVideo($video, $validated['question']);

        return response()->json([
            'success' => true,
            'answer'  => $answer,
        ]);
    }

    // ------------------------------------------------------------------
    // CRUD
    // ------------------------------------------------------------------

    public function index(): JsonResponse
    {
        // Automatically run migrations if needed using the host's DB connection
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            if (\Illuminate\Support\Facades\Schema::hasColumn('videos', 'user_id')) {
                Video::whereNull('user_id')->update(['user_id' => auth()->id()]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to run auto-migration or assign videos: " . $e->getMessage());
        }

        $videos = Video::where('user_id', auth()->id())->latest('extracted_at')->paginate(15);
        foreach ($videos as $v) {
            \Illuminate\Support\Facades\Log::info('Video index item: ' . $v->id . ' title: ' . $v->title . ' code_snippets type: ' . gettype($v->code_snippets) . ' count: ' . count($v->code_snippets ?? []) . ' content: ' . json_encode($v->code_snippets));
        }
        return response()->json($videos);
    }

    public function show(Video $video): JsonResponse
    {
        if ($denied = $this->denyUnlessOwns($video)) {
            return $denied;
        }

        \Illuminate\Support\Facades\Log::info('Video show: ' . $video->id . ' code_snippets type: ' . gettype($video->code_snippets) . ' count: ' . count($video->code_snippets ?? []) . ' content: ' . json_encode($video->code_snippets));
        return response()->json(['success' => true, 'data' => $video]);
    }

    public function search(Request $request): JsonResponse
    {
        $q = $request->get('q', '');

        $videos = Video::where('user_id', auth()->id())
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->latest('extracted_at')
            ->paginate(15);

        return response()->json($videos);
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    /**
     * Ensure the current user can access this video.
     * Orphan rows (user_id null) — caused by older mass-assignment bugs —
     * are claimed by the first authenticated user who touches them.
     */
    private function denyUnlessOwns(Video $video): ?JsonResponse
    {
        $userId = auth()->id();

        if (!$userId) {
            return response()->json(['success' => false, 'error' => 'Unauthorized access to this video.'], 403);
        }

        // Claim legacy / broken rows that never got an owner
        if ($video->user_id === null) {
            $video->user_id = $userId;
            $video->save();
            return null;
        }

        // Loose compare so int/string DB driver differences don't false-deny
        if ((int) $video->user_id !== (int) $userId) {
            return response()->json(['success' => false, 'error' => 'Unauthorized access to this video.'], 403);
        }

        return null;
    }

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
            Log::info("[VideoController] Attempting transcript fetch from youtube-transcript.ai for {$videoId}");
            $response = Http::timeout(25)
                ->withoutVerifying()
                ->get("https://youtube-transcript.ai/transcript/{$videoId}.txt");

            if ($response->successful() && strlen($response->body()) > 200) {
                Log::info("[VideoController] Successfully fetched transcript from youtube-transcript.ai for {$videoId} (length: " . strlen($response->body()) . ")");
                return $response->body();
            }
        } catch (\Exception $e) {
            Log::warning("[VideoController] youtube-transcript.ai fetch failed for {$videoId}: " . $e->getMessage());
        }

        try {
            Log::info("[VideoController] Falling back to direct timedtext for {$videoId}");
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/121',
                    'Accept-Language' => 'en-US,en;q=0.9',
                    'Cookie'          => 'CONSENT=YES+cb.20210328-17-p0.en+FX+917; SOCS=CAESEwgDEgk0ODE3Nzk3MjQaAmVuIAEaBgiA_LyaBg',
                ])
                ->get("https://www.youtube.com/api/timedtext", [
                    'v'   => $videoId,
                    'lang' => 'en',
                    'fmt'  => 'srv3',
                ]);

            if ($response->successful() && strlen($response->body()) > 50) {
                return $this->cleanTranscript($response->body());
            }

            Log::info("[VideoController] Falling back to page caption scraper for {$videoId}");
            $transcript = $this->getTranscriptFromPage($videoId);
            if ($transcript && strlen($transcript) > 50) {
                return $transcript;
            }
        } catch (\Exception $e) {
            Log::warning("[VideoController] Fallback transcript fetch failed for {$videoId}: " . $e->getMessage());
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
                    'Accept-Language' => 'en-US,en;q=0.9,en;q=0.5',
                    'Cookie'          => 'CONSENT=YES+cb.20210328-17-p0.en+FX+917; SOCS=CAESEwgDEgk0ODE3Nzk3MjQaAmVuIAEaBgiA_LyaBg',
                ])
                ->get("https://www.youtube.com/watch?v={$videoId}");

            if (!$response->successful()) {
                return null;
            }

            if (preg_match('/"captionTracks":\s*\[(.*?)\]/', $response->body(), $matches)) {
                $tracks = json_decode('[' . $matches[1] . ']', true);
                if (is_array($tracks) && count($tracks) > 0) {
                    // Find English track first, otherwise fallback to first track
                    $selectedTrack = null;
                    foreach ($tracks as $track) {
                        if (isset($track['languageCode']) && str_starts_with(strtolower($track['languageCode']), 'en')) {
                            $selectedTrack = $track;
                            break;
                        }
                    }
                    if (!$selectedTrack) {
                        $selectedTrack = $tracks[0];
                    }

                    if (!empty($selectedTrack['baseUrl'])) {
                        $cap = Http::timeout(10)
                            ->withoutVerifying()
                            ->withHeaders([
                                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 Chrome/120',
                                'Cookie'     => 'CONSENT=YES+cb.20210328-17-p0.en+FX+917; SOCS=CAESEwgDEgk0ODE3Nzk3MjQaAmVuIAEaBgiA_LyaBg',
                            ])
                            ->get($selectedTrack['baseUrl']);
                        if ($cap->successful()) {
                            return $this->cleanTranscript($cap->body());
                        }
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
