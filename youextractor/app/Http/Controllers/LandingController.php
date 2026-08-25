<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Public marketing homepage.
     */
    public function index(): View
    {
        return view('landing', [
            'stats' => $this->stats(),
        ]);
    }

    /**
     * Live platform stats for the homepage counters.
     *
     * Cached so a burst of landing traffic does not hammer the database.
     *
     * @return array{tutorials_extracted: int, hours_saved_this_week: int, projects_shipped: int, developers: int}
     */
    private function stats(): array
    {
        $fallback = [
            'tutorials_extracted' => 0,
            'hours_saved_this_week' => 0,
            'projects_shipped' => 0,
            'developers' => 0,
        ];

        try {
            return Cache::remember('landing.stats', 3600, function () {
                $weekStart = now()->startOfWeek();

                $completedThisWeek = Video::query()
                    ->where('extraction_status', 'completed')
                    ->where('extracted_at', '>=', $weekStart)
                    ->count();

                return [
                    'tutorials_extracted' => Video::query()
                        ->where('extraction_status', 'completed')
                        ->count(),
                    'hours_saved_this_week' => $completedThisWeek * Video::HOURS_SAVED_PER_EXTRACTION,
                    'projects_shipped' => Video::query()
                        ->whereNotNull('github_repo_url')
                        ->where('github_repo_url', '!=', '')
                        ->count(),
                    'developers' => User::query()->count(),
                ];
            });
        } catch (\Throwable $e) {
            Log::warning('Failed to load landing stats', [
                'error' => $e->getMessage(),
            ]);

            return $fallback;
        }
    }
}
