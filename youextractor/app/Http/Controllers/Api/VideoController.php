<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Services\CodeExtractorService;
use App\Jobs\ProcessExtractionJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VideoController extends Controller
{
    public function __construct(
        private readonly CodeExtractorService $extractor
    ) {}

    /**
     * Queue extraction (FAST, SAFE, NON-BLOCKING)
     */
    public function extract(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'youtube_url'   => 'required|string',
            'force_refresh' => 'sometimes|boolean',
        ]);

        $videoId = $this->extractVideoId($validated['youtube_url']);

        if (!$videoId) {
            return response()->json([
                'success' => false,
                'error'   => 'Invalid YouTube URL',
            ], 422);
        }

        $force = (bool) ($validated['force_refresh'] ?? false);

        $existing = Video::where('youtube_id', $videoId)->first();

        if ($existing && !$force) {
            return response()->json([
                'success' => true,
                'status'  => $existing->extraction_status,
                'data'    => $existing,
            ]);
        }

        if ($existing && $force) {
            $existing->delete();
        }

        $meta = $this->fetchMetadata($videoId);

        $video = Video::create([
            'youtube_id'        => $videoId,
            'title'             => $meta['title'],
            'description'       => $meta['description'],
            'extraction_status' => 'queued',
        ]);

        ProcessExtractionJob::dispatch($video->id)->onQueue('extraction');

        return response()->json([
            'success' => true,
            'status'  => 'queued',
            'video_id'=> $video->id,
        ], 202);
    }

    /**
     * Download ZIP ONLY if real files exist
     */
    public function download(Video $video): BinaryFileResponse|JsonResponse
    {
        if ($video->extraction_status !== 'completed') {
            return response()->json([
                'success' => false,
                'error'   => 'Extraction not completed',
            ], 409);
        }

        if (empty($video->code_files)) {
            return response()->json([
                'success' => false,
                'error'   => 'No extractable code found',
            ], 404);
        }

        $zipPath = $this->extractor->buildZip($video);

        return response()->download(
            $zipPath,
            str_slug($video->title) . '-code.zip'
        );
    }

    /**
     * List videos
     */
    public function index(): JsonResponse
    {
        return response()->json(
            Video::latest()->paginate(15)
        );
    }

    /**
     * Single video
     */
    public function show(Video $video): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $video,
        ]);
    }

    /**
     * Extract YouTube ID safely
     */
    private function extractVideoId(string $url): ?string
    {
        preg_match(
            '/(?:v=|\/)([0-9A-Za-z_-]{11})/',
            $url,
            $matches
        );

        return $matches[1] ?? null;
    }

    /**
     * Fetch metadata (FAST, SAFE)
     */
    private function fetchMetadata(string $videoId): array
    {
        try {
            $response = Http::timeout(5)->get(
                'https://www.youtube.com/oembed',
                [
                    'url' => "https://www.youtube.com/watch?v={$videoId}",
                    'format' => 'json',
                ]
            );

            if ($response->successful()) {
                return [
                    'title'       => $response['title'] ?? 'Untitled',
                    'description' => 'By ' . ($response['author_name'] ?? 'Unknown'),
                ];
            }
        } catch (\Throwable $e) {
            Log::warning('Metadata fetch failed', [
                'video' => $videoId,
            ]);
        }

        return [
            'title'       => 'Unknown Video',
            'description' => '',
        ];
    }
}
    