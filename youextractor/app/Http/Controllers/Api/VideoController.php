<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Extract YouTube video and explain it
     */
    public function extract(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'youtube_url' => 'required|string',
            ]);

            // Extract video ID from URL
            $videoId = $this->extractVideoId($validated['youtube_url']);
            
            if (!$videoId) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid YouTube URL. Please use a valid YouTube video URL.',
                ], 400);
            }

            // Check if already exists
            $existingVideo = Video::where('youtube_id', $videoId)->first();
            if ($existingVideo) {
                return response()->json([
                    'success' => true,
                    'message' => 'Video already extracted',
                    'data' => $existingVideo,
                ], 200);
            }

            // Get video metadata (fast method using oEmbed)
            $videoData = $this->getVideoMetadata($videoId);
            
            if (!$videoData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Could not fetch video information. The video might be private or unavailable.',
                ], 400);
            }

            // Generate explanation (without waiting for transcript)
            $explanation = $this->generateExplanation($videoData['title'], $videoData['description']);
            
            // Generate summary
            $summary = $this->generateSummary($videoData['title']);

            // Save to database
            $video = Video::create([
                'youtube_id' => $videoId,
                'title' => $videoData['title'],
                'description' => $videoData['description'],
                'transcript' => 'Transcript extraction skipped for faster processing.',
                'explanation' => $explanation,
                'code_snippets' => [],
                'summary' => $summary,
                'duration' => $videoData['duration'],
                'published_at' => now(),
                'extracted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video extracted successfully',
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
     * Get all extracted videos
     */
    public function index(): JsonResponse
    {
        try {
            $videos = Video::latest('extracted_at')->paginate(15);
            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch videos',
            ], 500);
        }
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
     * Get video metadata using oEmbed (fast, no API key needed)
     */
    private function getVideoMetadata(string $videoId): ?array
    {
        try {
            // Use oEmbed - fast and reliable
            $response = Http::timeout(10)->get('https://www.youtube.com/oembed', [
                'url' => "https://www.youtube.com/watch?v={$videoId}",
                'format' => 'json',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'title' => $data['title'] ?? 'Unknown Title',
                    'description' => "Video by {$data['author_name']}. Watch on YouTube for full content.",
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
     * Generate explanation using OpenAI or fallback
     */
    private function generateExplanation(string $title, string $description): string
    {
        $openaiKey = env('OPENAI_API_KEY');
        
        // If no OpenAI key or it's invalid, use fallback
        if (empty($openaiKey) || strlen($openaiKey) < 20) {
            return $this->generateFallbackExplanation($title, $description);
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$openaiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a helpful assistant that explains YouTube video content based on their titles. Provide a brief, informative explanation of what the video likely covers.',
                        ],
                        [
                            'role' => 'user',
                            'content' => "Based on this video title, provide a helpful explanation of what this video likely covers:\n\nTitle: {$title}\n\nProvide a 2-3 paragraph explanation.",
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? null;
                if ($content) {
                    return $content;
                }
            }
            
            Log::warning('OpenAI request failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::warning('OpenAI error: ' . $e->getMessage());
        }

        return $this->generateFallbackExplanation($title, $description);
    }

    /**
     * Generate fallback explanation without AI
     */
    private function generateFallbackExplanation(string $title, string $description): string
    {
        return "## {$title}\n\n{$description}\n\n### About This Video\nThis video has been saved to your library. Click the YouTube link above to watch the full video and get all the details.\n\n### Note\nFor AI-powered explanations, ensure your OpenAI API key is properly configured in the .env file.";
    }

    /**
     * Generate summary
     */
    private function generateSummary(string $title): string
    {
        return "Video: " . substr($title, 0, 200);
    }
}
