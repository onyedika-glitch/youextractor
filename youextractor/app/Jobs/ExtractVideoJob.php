<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\CodeExtractorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * ExtractVideoJob
 *
 * Runs the entire AI extraction pipeline in the background so the HTTP
 * request returns immediately with a "started" status.
 *
 * The Video model's `extraction_status` column tracks progress:
 *   pending → processing → completed | failed
 *
 * Usage:
 *   ExtractVideoJob::dispatch($video);
 *
 * Queue worker:
 *   php artisan queue:work --timeout=600
 */
class ExtractVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Allow up to 10 minutes for AI calls */
    public int $timeout = 600;

    /** Retry once more if the job throws an unhandled exception */
    public int $tries = 2;

    public function __construct(private Video $video)
    {
    }

    public function handle(CodeExtractorService $extractor): void
    {
        Log::info("[ExtractVideoJob] Starting for video #{$this->video->id} ({$this->video->youtube_id})");

        $this->video->update(['extraction_status' => 'processing']);

        try {
            // Fetch fresh transcript (may have been set to placeholder)
            $transcript = $this->video->transcript;
            if (empty($transcript) || str_contains($transcript, 'not available')) {
                $transcript = $this->fetchTranscript($this->video->youtube_id);
                $this->video->update(['transcript' => $transcript]);
            }

            // Run AI extraction
            $codeData = $extractor->extractCodeFromTranscript(
                $this->video->title,
                $transcript,
                $this->video->youtube_id
            );

            // Generate summary and explanation
            $summary = $this->video->title;
            $primaryStack = $codeData['stack']['primary'] ?? null;
            if ($primaryStack) {
                $summary .= " | " . ucfirst($primaryStack);
            }
            $fileCount = count($codeData['files'] ?? []);
            if ($fileCount > 0) {
                $summary .= " | {$fileCount} code files";
            }
            $summary = substr($summary, 0, 300);

            $explanation = '';
            if (!empty($codeData['tutorial_guide']['overview'])) {
                $explanation .= "### Overview\n" . $codeData['tutorial_guide']['overview'] . "\n\n";
            }

            if (!empty($codeData['tutorial_guide']['key_concepts'])) {
                $explanation .= "### Key Concepts\n";
                foreach ($codeData['tutorial_guide']['key_concepts'] as $c) {
                    $explanation .= "- **" . ($c['concept'] ?? '') . "**: " . ($c['explanation'] ?? '') . "\n";
                }
                $explanation .= "\n";
            }

            if (!empty($codeData['stack'])) {
                $explanation .= "### Tech Stack\n";
                $explanation .= "- **Primary Language**: " . ($codeData['stack']['primary'] ?? 'Unknown') . "\n";
                if (!empty($codeData['stack']['languages'])) {
                    $explanation .= "- **Languages**: " . implode(', ', $codeData['stack']['languages']) . "\n";
                }
                if (!empty($codeData['stack']['frameworks'])) {
                    $explanation .= "- **Frameworks**: " . implode(', ', $codeData['stack']['frameworks']) . "\n";
                }
                if (!empty($codeData['stack']['description'])) {
                    $explanation .= "- **Description**: " . $codeData['stack']['description'] . "\n";
                }
                $explanation .= "\n";
            }

            if (!empty($codeData['files'])) {
                $explanation .= "### Extracted Files\n";
                foreach ($codeData['files'] as $f) {
                    $explanation .= "- **" . ($f['filename'] ?? '') . "** - " . ($f['description'] ?? '') . "\n";
                }
                $explanation .= "\n";
            }

            if (empty($explanation)) {
                $explanation = "AI Code Extraction for " . $this->video->title;
            }

            // Persist results
            $this->video->update([
                'code_snippets'       => $codeData['files']               ?? [],
                'tech_stack'          => $codeData['stack']               ?? null,
                'setup_instructions'  => $codeData['setup_instructions']  ?? '',
                'dependencies'        => $codeData['dependencies']        ?? [],
                'tutorial_guide'      => $codeData['tutorial_guide']      ?? null,
                'ide_recommendations' => $codeData['ide_recommendations'] ?? null,
                'prerequisites'       => $codeData['prerequisites']       ?? null,
                'setup_guide'         => $codeData['setup_guide']         ?? null,
                'run_guide'           => $codeData['run_guide']           ?? null,
                'explanation'         => $explanation,
                'summary'             => $summary,
                'extraction_status'   => 'completed',
                'extracted_at'        => now(),
            ]);

            // Pre-generate the ZIP so downloads are instant
            if (!empty($codeData['files'])) {
                $extractor->generateZipFile($this->video->youtube_id, $codeData);
            }

            Log::info("[ExtractVideoJob] Completed for video #{$this->video->id}");
        } catch (\Throwable $e) {
            Log::error("[ExtractVideoJob] Failed for #{$this->video->id}: " . $e->getMessage());
            $this->video->update([
                'extraction_status' => 'failed',
                'extraction_error'  => $e->getMessage(),
            ]);
            throw $e; // re-throw so the queue marks the job as failed
        }
    }

    /**
     * Re-fetch transcript using the same strategy as the controller.
     */
    private function fetchTranscript(string $videoId): string
    {
        try {
            Log::info("[ExtractVideoJob] Attempting transcript fetch from youtube-transcript.ai for {$videoId}");
            $response = Http::timeout(25)
                ->withoutVerifying()
                ->get("https://youtube-transcript.ai/transcript/{$videoId}.txt");

            if ($response->successful() && strlen($response->body()) > 200) {
                Log::info("[ExtractVideoJob] Successfully fetched transcript from youtube-transcript.ai for {$videoId} (length: " . strlen($response->body()) . ")");
                return $response->body();
            }
        } catch (\Exception $e) {
            Log::warning("[ExtractVideoJob] youtube-transcript.ai fetch failed for {$videoId}: " . $e->getMessage());
        }

        try {
            Log::info("[ExtractVideoJob] Falling back to direct timedtext for {$videoId}");
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

            Log::info("[ExtractVideoJob] Falling back to page caption scraper for {$videoId}");
            $transcript = $this->getTranscriptFromPage($videoId);
            if ($transcript && strlen($transcript) > 50) {
                return $transcript;
            }
        } catch (\Exception $e) {
            Log::warning("[ExtractVideoJob] Fallback transcript fetch failed for {$videoId}: " . $e->getMessage());
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
            Log::warning('[ExtractVideoJob] Page transcript scraper failed: ' . $e->getMessage());
        }
        return null;
    }
}
