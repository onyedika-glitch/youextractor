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
                            {--limit=20 : Max number of users to email in one run (0 = no limit)}
                            {--dry-run : List recipients without actually sending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the reminder email to the next batch of users (at most one email per user every 3 days).';

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

        // Users who haven't unsubscribed and haven't had a reminder in the last 3 days.
        // Never-reminded users go first, then whoever waited longest.
        $query = User::query()
            ->whereNotNull('email')
            ->where('email_reminders', true)
            ->where(fn ($q) => $q->whereNull('last_reminder_at')
                ->orWhere('last_reminder_at', '<=', now()->subDays(3)))
            ->orderByRaw('last_reminder_at IS NOT NULL')
            ->orderBy('last_reminder_at')
            ->orderBy('id');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $sent = 0;
        $failed = 0;

        foreach ($query->get() as $user) {
            if ($dryRun) {
                $this->line("  would email: {$user->email}");
                $sent++;
                continue;
            }

            if ($notifier->dailyReminder($user, $tip)) {
                $user->forceFill(['last_reminder_at' => now()])->save();
                $sent++;
            } else {
                // Most likely the email provider's daily limit: stop now and let tomorrow's run continue.
                $failed++;
                $this->error("  failed for user {$user->id}, stopping this run");
                break;
            }
        }

        $this->info(($dryRun ? '[dry-run] ' : '') . "Reminders processed. Sent: {$sent}, Failed: {$failed}.");

        return self::SUCCESS;
    }
}
