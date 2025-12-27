<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Services\CodeExtractorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VideoController extends Controller
{
    private CodeExtractorService $codeExtractor;

    public function __construct()
    {
        $this->codeExtractor = new CodeExtractorService();
    }

    /**
     * Extract YouTube video and code snippets
     */
    public function extract(Request $request): JsonResponse
    {
        // Increase execution time for AI processing
        set_time_limit(600);
        
        try {
            $validated = $request->validate([
                'youtube_url' => 'required|string',
            ]);

            $videoId = $this->extractVideoId($validated['youtube_url']);
            
            if (!$videoId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid YouTube URL. Please use a valid YouTube video URL.',
                ], 400);
            }

            // Always extract fresh - no caching from DB
            // Removed check for existing video to force re-extraction

            // Get video metadata
            $videoData = $this->getVideoMetadata($videoId);
            
            if (!$videoData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Could not fetch video information. The video might be private or unavailable.',
                ], 400);
            }

            // Get transcript for code extraction
            $transcript = $this->getTranscript($videoId);

            // Extract code using AI
            $codeData = $this->codeExtractor->extractCodeFromTranscript(
                $videoData['title'],
                $transcript
            );

            // Generate explanation
            $explanation = $this->generateExplanation($videoData['title'], $codeData);
            
            // Generate summary
            $summary = $this->generateSummary($videoData['title'], $codeData);

            // Save to database
            $video = Video::create([
                'youtube_id' => $videoId,
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'transcript' => $transcript,
                'explanation' => $explanation,
                'code_snippets' => $codeData['files'] ?? [],
                'tech_stack' => $codeData['stack'] ?? null,
                'setup_instructions' => $codeData['setup_instructions'] ?? '',
                'dependencies' => $codeData['dependencies'] ?? [],
                'tutorial_guide' => $codeData['tutorial_guide'] ?? null,
                'ide_recommendations' => $codeData['ide_recommendations'] ?? null,
                'prerequisites' => $codeData['prerequisites'] ?? null,
                'setup_guide' => $codeData['setup_guide'] ?? null,
                'run_guide' => $codeData['run_guide'] ?? null,
                'summary' => $summary,
                'duration' => $videoData['duration'],
                'published_at' => now(),
                'extracted_at' => now(),
            ]);

            // Generate ZIP file
            if (!empty($codeData['files'])) {
                $this->codeExtractor->generateZipFile($videoId, $codeData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Video and code extracted successfully',
                'data' => $video,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Video extraction error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Failed to extract video: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download code as ZIP
     */
    public function downloadCode(Video $video): BinaryFileResponse|JsonResponse
    {
        $zipPath = storage_path("app/downloads/{$video->youtube_id}.zip");

        if (!file_exists($zipPath)) {
            // Generate ZIP if not exists
            $codeData = [
                'stack' => $video->tech_stack,
                'files' => $video->code_snippets ?? [],
                'setup_instructions' => $video->setup_instructions ?? '',
                'dependencies' => $video->dependencies ?? [],
            ];

            if (empty($codeData['files'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'No code files available for download',
                ], 404);
            }

            $zipPath = $this->codeExtractor->generateZipFile($video->youtube_id, $codeData);
        }

        if (!$zipPath || !file_exists($zipPath)) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to generate download file',
            ], 500);
        }

        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $video->title);
        $filename = substr($filename, 0, 50) . '_code.zip';

        return response()->download($zipPath, $filename);
    }

    /**
     * Re-extract code from existing video
     */
    public function reExtractCode(Video $video): JsonResponse
    {
        // Increase execution time for AI processing
        set_time_limit(600);
        
        try {
            $transcript = $video->transcript;
            
            if (empty($transcript) || str_contains($transcript, 'skipped')) {
                $transcript = $this->getTranscript($video->youtube_id);
            }

            $codeData = $this->codeExtractor->extractCodeFromTranscript(
                $video->title,
                $transcript
            );

            $video->update([
                'transcript' => $transcript,
                'code_snippets' => $codeData['files'] ?? [],
                'tech_stack' => $codeData['stack'] ?? null,
                'setup_instructions' => $codeData['setup_instructions'] ?? '',
                'dependencies' => $codeData['dependencies'] ?? [],
                'tutorial_guide' => $codeData['tutorial_guide'] ?? null,
                'ide_recommendations' => $codeData['ide_recommendations'] ?? null,
                'prerequisites' => $codeData['prerequisites'] ?? null,
                'setup_guide' => $codeData['setup_guide'] ?? null,
                'run_guide' => $codeData['run_guide'] ?? null,
                'explanation' => $this->generateExplanation($video->title, $codeData),
            ]);

            // Regenerate ZIP
            if (!empty($codeData['files'])) {
                $this->codeExtractor->generateZipFile($video->youtube_id, $codeData);
            }

            return response()->json([
                'success' => true,
                'message' => 'Code re-extracted successfully',
                'data' => $video->fresh(),
            ]);

        } catch (\Exception $e) {
            Log::error('Re-extraction error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to re-extract code: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all extracted videos
     */
    public function index(): JsonResponse
    {
        $videos = Video::latest('extracted_at')->paginate(15);
        return response()->json($videos);
    }

    /**
     * Get single video
     */
    public function show(Video $video): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $video,
        ]);
    }

    /**
     * Search videos
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        $videos = Video::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->latest('extracted_at')
            ->paginate(15);
        
        return response()->json($videos);
    }

    /**
     * Extract video ID from YouTube URL
     */
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

    /**
     * Get video metadata using oEmbed
     */
    private function getVideoMetadata(string $videoId): ?array
    {
        try {
            $response = Http::timeout(10)->get('https://www.youtube.com/oembed', [
                'url' => "https://www.youtube.com/watch?v={$videoId}",
                'format' => 'json',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'title' => $data['title'] ?? 'Unknown Title',
                    'description' => "Video by {$data['author_name']}",
                    'duration' => 0,
                    'author' => $data['author_name'] ?? 'Unknown',
                ];
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('oEmbed failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get video transcript
     */
    private function getTranscript(string $videoId): string
    {
        try {
            // Method 1: Try YouTube's timedtext API
            $response = Http::timeout(15)->get("https://www.youtube.com/api/timedtext", [
                'v' => $videoId,
                'lang' => 'en',
                'kind' => 'asr',
            ]);

            if ($response->successful() && $response->body()) {
                $transcript = $this->parseTimedText($response->body());
                if ($transcript && strlen($transcript) > 50) {
                    return $transcript;
                }
            }

            // Method 2: Try to get caption URL from video page
            $transcript = $this->getTranscriptFromPage($videoId);
            if ($transcript && strlen($transcript) > 50) {
                return $transcript;
            }

        } catch (\Exception $e) {
            Log::warning('Transcript fetch failed: ' . $e->getMessage());
        }

        return 'Transcript not available. Code extraction will be based on video title and metadata.';
    }

    /**
     * Parse timedtext XML
     */
    private function parseTimedText(string $xml): ?string
    {
        try {
            libxml_use_internal_errors(true);
            $doc = simplexml_load_string($xml);
            
            if (!$doc) return null;

            $transcript = '';
            foreach ($doc->text ?? [] as $text) {
                $content = (string) $text;
                $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $transcript .= $content . ' ';
            }

            return trim($transcript) ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get transcript from YouTube page
     */
    private function getTranscriptFromPage(string $videoId): ?string
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders(['Accept-Language' => 'en-US,en;q=0.9'])
                ->get("https://www.youtube.com/watch?v={$videoId}");

            if (!$response->successful()) return null;

            $html = $response->body();
            
            if (preg_match('/"captionTracks":\s*\[(.*?)\]/', $html, $matches)) {
                $tracksJson = '[' . $matches[1] . ']';
                $tracks = json_decode($tracksJson, true);
                
                if ($tracks && isset($tracks[0]['baseUrl'])) {
                    $captionUrl = str_replace('\u0026', '&', $tracks[0]['baseUrl']);
                    $captionResponse = Http::timeout(10)->get($captionUrl);
                    
                    if ($captionResponse->successful()) {
                        return $this->parseTimedText($captionResponse->body());
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning('Page transcript failed: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate explanation with code context
     */
    private function generateExplanation(string $title, array $codeData): string
    {
        $explanation = "## {$title}\n\n";

        $stack = $codeData['stack'] ?? null;
        if ($stack) {
            $explanation .= "### Tech Stack\n";
            $explanation .= "**Primary Language**: {$stack['primary']}\n\n";
            
            if (!empty($stack['frameworks'])) {
                $explanation .= "**Frameworks/Libraries**: " . implode(', ', $stack['frameworks']) . "\n\n";
            }
            
            if (!empty($stack['description'])) {
                $explanation .= "{$stack['description']}\n\n";
            }
        }

        $files = $codeData['files'] ?? [];
        if (!empty($files)) {
            $explanation .= "### Extracted Files (" . count($files) . " files)\n\n";
            foreach ($files as $file) {
                $explanation .= "- **{$file['filename']}** - {$file['description']}\n";
            }
            $explanation .= "\n";
        }

        if (!empty($codeData['setup_instructions'])) {
            $explanation .= "### Setup Instructions\n```bash\n{$codeData['setup_instructions']}\n```\n\n";
        }

        $explanation .= "---\n*Click \"Download Code\" to get all the code files in a ZIP archive.*";

        return $explanation;
    }

    /**
     * Generate summary
     */
    private function generateSummary(string $title, array $codeData): string
    {
        $stack = $codeData['stack'] ?? null;
        $fileCount = count($codeData['files'] ?? []);

        $summary = $title;
        if ($stack) {
            $summary .= " | {$stack['primary']}";
        }
        if ($fileCount > 0) {
            $summary .= " | {$fileCount} code files";
        }

        return substr($summary, 0, 300);
    }
}
