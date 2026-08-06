<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserNotifier;
use Illuminate\Console\Command;

class SendDailyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youextractor:daily-reminders
                            {--limit=0 : Max number of users to email (0 = no limit)}
                            {--dry-run : List recipients without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the daily engagement reminder email to all registered users.';

    /**
     * A rotating set of tips so the daily email stays fresh.
     *
     * @var array<int, string>
     */
    protected array $tips = [
        'Try extracting a build-along project video — you\'ll end up with a runnable repo and notes explaining every decision the creator made.',
        'Short on time? A 10-minute tutorial still turns into a complete, structured project you can revisit later.',
        'Use the Chrome extension to extract a tutorial without ever leaving YouTube.',
        'Stuck on a concept? Extract the video, then read the generated explanations alongside the code.',
        'Revisit your extraction library — it\'s a growing collection of ready-to-run reference projects that are all yours.',
        'Learning a new framework this week? Extract three tutorials on it and compare how different creators structure their code.',
        'Pair today\'s extraction with a quick "build it yourself from memory" session to make the lesson stick.',
    ];

    public function handle(UserNotifier $notifier): int
    {
        $limit = (int) $this->option('limit');
        $dryRun = (bool) $this->option('dry-run');

        // Pick today's tip deterministically so every user in a run gets the same one.
        $tip = $this->tips[(int) date('z') % count($this->tips)];

        $query = User::query()->whereNotNull('email');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $sent = 0;
        $failed = 0;

        $query->chunkById(200, function ($users) use ($notifier, $tip, $dryRun, &$sent, &$failed) {
            foreach ($users as $user) {
                if ($dryRun) {
                    $this->line("  would email: {$user->email}");
                    $sent++;
                    continue;
                }

                try {
                    $notifier->dailyReminder($user, $tip);
                    $sent++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->error("  failed for {$user->email}: {$e->getMessage()}");
                }
            }
        });

        $this->info(($dryRun ? '[dry-run] ' : '') . "Daily reminders processed. Sent: {$sent}, Failed: {$failed}.");

        return self::SUCCESS;
    }
}
