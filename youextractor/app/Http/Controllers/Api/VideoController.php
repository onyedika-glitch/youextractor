<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    /**
     * Extract YouTube video and explain it
     */
    public function extract(Request $request)
    {
        $validated = $request->validate([
            'youtube_url' => 'required|url',
        ]);

        try {
            // Extract video ID from URL
            $videoId = $this->extractVideoId($validated['youtube_url']);
            
            // Check if already exists
            $video = Video::where('youtube_id', $videoId)->first();
            if ($video) {
                return response()->json($video, 200);
            }

            // Get video metadata from YouTube API
            $videoData = $this->getYoutubeMetadata($videoId);
            
            // Get transcript (using a free service)
            $transcript = $this->getTranscript($videoId);
            
            // For now, skip OpenAI and just use a basic explanation from transcript
            $explanation = $this->generateSimpleExplanation($transcript, $videoData['title']);
            
            // Extract code snippets
            $codeSnippets = $this->extractCodeSnippets($explanation);
            
            // Save to database
            $video = Video::create([
                'youtube_id' => $videoId,
                'title' => $videoData['title'],
                'description' => $videoData['description'] ?? '',
                'transcript' => $transcript,
                'explanation' => $explanation,
                'code_snippets' => $codeSnippets,
                'summary' => substr($explanation, 0, 300) . '...',
                'duration' => $videoData['duration'] ?? 0,
                'published_at' => $videoData['published_at'] ?? now(),
                'extracted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Video extracted successfully',
                'data' => $video,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Video extraction error: ' . $e->getMessage(), [
                'url' => $validated['youtube_url'] ?? 'unknown',
            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get all extracted videos
     */
    public function index()
    {
        $videos = Video::latest('extracted_at')->paginate(15);
        return response()->json($videos);
    }

    /**
     * Get single video
     */
    public function show(Video $video)
    {
        return response()->json($video);
    }

    /**
     * Search videos
     */
    public function search(Request $request)
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
    private function extractVideoId(string $url): string
    {
        preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches);
        return $matches[1] ?? throw new \Exception('Invalid YouTube URL format');
    }

    /**
     * Get YouTube video metadata
     */
    private function getYoutubeMetadata(string $videoId): array
    {
        try {
            $apiKey = config('services.youtube.key');
            if (!$apiKey) {
                throw new \Exception('YouTube API key not configured');
            }
            
            $response = Http::timeout(10)->get('https://www.googleapis.com/youtube/v3/videos', [
                'id' => $videoId,
                'key' => $apiKey,
                'part' => 'snippet,contentDetails',
            ]);

            if ($response->failed()) {
                throw new \Exception('YouTube API Error: ' . $response->body());
            }

            $data = $response->json();
            if (!isset($data['items'][0])) {
                throw new \Exception('Video not found on YouTube');
            }

            $video = $data['items'][0];
            $snippet = $video['snippet'] ?? [];
            
            return [
                'title' => $snippet['title'] ?? 'Unknown',
                'description' => $snippet['description'] ?? '',
                'duration' => $this->parseDuration($video['contentDetails']['duration'] ?? 'PT0S'),
                'published_at' => $snippet['publishedAt'] ?? now(),
            ];
        } catch (\Exception $e) {
            Log::error('YouTube API error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get video transcript
     */
    private function getTranscript(string $videoId): string
    {
        try {
            $response = Http::timeout(10)->get("https://www.youtube.com/api/timedtext", [
                'v' => $videoId,
                'kind' => 'asr',
                'lang' => 'en',
            ]);
            
            if ($response->successful() && $response->body()) {
                libxml_use_internal_errors(true);
                $xml = simplexml_load_string($response->body());
                
                if ($xml) {
                    $transcript = '';
                    foreach ($xml->text ?? [] as $text) {
                        $transcript .= (string)$text . ' ';
                    }
                    return trim($transcript) ?: 'No transcript available';
                }
            }
            
            return 'Transcript not available';
        } catch (\Exception $e) {
            Log::warning('Transcript extraction: ' . $e->getMessage());
            return 'Transcript not available';
        }
    }

    /**
     * Generate simple explanation without OpenAI
     */
    private function generateSimpleExplanation(string $transcript, string $title): string
    {
        if (empty($transcript) || strpos($transcript, 'not available') !== false) {
            return "Video: **{$title}**\n\nNo transcript available for analysis.";
        }

        // Take first 1000 characters of transcript
        $excerpt = substr($transcript, 0, 1000);
        
        return "**{$title}**\n\n**Transcript Excerpt:**\n{$excerpt}\n\n*(Full OpenAI explanation requires valid API key and longer processing)*";
    }

    /**
     * Generate explanation using OpenAI
     */
    private function generateExplanation(string $transcript, string $title): string
    {
        try {
            if (empty($transcript) || strpos($transcript, 'not available') !== false) {
                return "Video: {$title}\n\nTranscript not available for this video.";
            }

            // Limit to 2000 chars to avoid token limits
            $shortTranscript = substr($transcript, 0, 2000);
            
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Explain technical video content in 100-200 words.',
                    ],
                    [
                        'role' => 'user',
                        'content' => "Video: {$title}\nContent: {$shortTranscript}",
                    ],
                ],
                'temperature' => 0.7,
                'max_tokens' => 250,
            ]);

            return $result->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            Log::error('OpenAI error: ' . $e->getMessage());
            return "Video: {$title}\n\nOpenAI Error: " . $e->getMessage();
        }
    }

    /**
     * Generate summary
     */
    private function generateSummary(string $explanation): string
    {
        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Summarize in 1-2 sentences: " . substr($explanation, 0, 500),
                    ],
                ],
                'max_tokens' => 100,
            ]);

            return $result->choices[0]->message->content ?? '';
        } catch (\Exception $e) {
            return substr($explanation, 0, 200) . '...';
        }
    }

    /**
     * Extract code snippets
     */
    private function extractCodeSnippets(string $explanation): array
    {
        $snippets = [];
        preg_match_all('/```(\w+)?\n(.*?)\n```/s', $explanation, $matches);
        
        foreach ($matches[2] ?? [] as $code) {
            if (trim($code)) {
                $snippets[] = trim($code);
            }
        }

        return $snippets;
    }

    /**
     * Parse ISO 8601 duration
     */
    private function parseDuration(string $duration): int
    {
        preg_match('/PT(\d+H)?(\d+M)?(\d+S)?/', $duration, $matches);
        
        $hours = (int)preg_replace('/H/', '', $matches[1] ?? 0);
        $minutes = (int)preg_replace('/M/', '', $matches[2] ?? 0);
        $seconds = (int)preg_replace('/S/', '', $matches[3] ?? 0);
        
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }
}
