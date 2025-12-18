<?php

namespace App\Jobs;

use App\Models\Video;
use App\Services\CodeExtractorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessExtractionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 240;
    public int $tries = 2;

    protected string $youtubeId;
    protected string $title;
    protected string $description;

    public function __construct(
        string $youtubeId,
        string $title,
        string $description = ''
    ) {
        $this->youtubeId   = $youtubeId;
        $this->title       = $title;
        $this->description = $description;
    }

    public function handle(): void
    {
        Log::info("[Extraction] Started", [
            'youtube_id' => $this->youtubeId
        ]);

        $service  = new CodeExtractorService();
        $videoUrl = "https://www.youtube.com/watch?v={$this->youtubeId}";

        try {
            /**
             * STEP 1: Detect repository (FAST)
             */
            $repo = $service->detectRepository(
                $videoUrl,
                $this->description
            );

            /**
             * STEP 2: Extract code (repo-first)
             */
            if ($repo) {
                Log::info("[Extraction] Repository detected", [
                    'repo' => $repo
                ]);

                $result = $service->extractFromRepository($repo);
                $source = 'github';
            } else {
                Log::info("[Extraction] No repository found, using transcript");

                $result = $service->extractFromTranscript($videoUrl);
                $source = 'transcript';
            }

            /**
             * STEP 3: Validate result (NO HALLUCINATION)
             */
            if (empty($result['files'])) {
                Log::warning("[Extraction] No code files extracted");

                Video::updateOrCreate(
                    ['youtube_id' => $this->youtubeId],
                    [
                        'title'        => $this->title,
                        'description'  => $this->description,
                        'extracted_at' => now(),
                        'status'       => 'no_code',
                        'source'       => $source,
                    ]
                );

                return;
            }

            /**
             * STEP 4: Persist
             */
            $video = Video::updateOrCreate(
                ['youtube_id' => $this->youtubeId],
                [
                    'title'               => $this->title,
                    'description'         => $this->description,
                    'transcript'          => $result['transcript'] ?? null,
                    'code_snippets'       => $result['files'],
                    'tech_stack'          => $result['stack'] ?? null,
                    'dependencies'        => $result['dependencies'] ?? [],
                    'setup_instructions'  => $result['setup'] ?? null,
                    'tutorial_guide'      => $result['tutorial'] ?? null,
                    'setup_guide'         => $result['setup_guide'] ?? null,
                    'run_guide'           => $result['run_guide'] ?? null,
                    'explanation'         => $result['explanation'] ?? null,
                    'source'              => $source,
                    'status'              => 'success',
                    'extracted_at'        => now(),
                ]
            );

            /**
             * STEP 5: ZIP generation (optional, async-safe)
             */
            $service->generateZipFile(
                $this->youtubeId,
                $result
            );

            Log::info("[Extraction] Completed successfully", [
                'youtube_id' => $this->youtubeId,
                'files'      => count($result['files']),
                'source'     => $source,
            ]);
        } catch (Throwable $e) {
            Log::error("[Extraction] Failed", [
                'youtube_id' => $this->youtubeId,
                'error'      => $e->getMessage(),
            ]);

            Video::updateOrCreate(
                ['youtube_id' => $this->youtubeId],
                [
                    'title'        => $this->title,
                    'description'  => $this->description,
                    'status'       => 'failed',
                    'error'        => $e->getMessage(),
                    'extracted_at'=> now(),
                ]
            );

            throw $e;
        }
    }
}
