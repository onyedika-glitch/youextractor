<?php

namespace App\Services;

use App\Mail\ActivityNotification;
use App\Mail\DailyReminderEmail;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UserNotifier
{
    /**
     * Send the onboarding welcome email to a newly registered user.
     */
    public function welcome(User $user): void
    {
        $this->dispatch($user, fn () => Mail::to($user->email)->send(new WelcomeEmail($user)), 'welcome');
    }

    /**
     * Notify the user about an account activity (login, logout, etc.).
     */
    public function activity(User $user, string $type, ?Request $request = null, array $extra = []): void
    {
        $data = $extra;

        if ($request) {
            $data['ip'] = $data['ip'] ?? $request->ip();
            $data['device'] = $data['device'] ?? $request->userAgent();
        }
        $data['time'] = $data['time'] ?? now()->format('M j, Y \a\t g:i A T');

        $this->dispatch(
            $user,
            fn () => Mail::to($user->email)->send(new ActivityNotification($user, $type, $data)),
            "activity:{$type}"
        );
    }

    /**
     * Send the daily engagement reminder.
     */
    public function dailyReminder(User $user, string $tip = ''): void
    {
        $this->dispatch($user, fn () => Mail::to($user->email)->send(new DailyReminderEmail($user, $tip)), 'daily-reminder');
    }

    /**
     * Run a send closure without ever letting a mail failure break the request.
     */
    protected function dispatch(User $user, callable $send, string $label): void
    {
        if (empty($user->email)) {
            return;
        }

        try {
            $send();
        } catch (\Throwable $e) {
            Log::error("Failed to send {$label} email to user {$user->id}: " . $e->getMessage());
        }
    }
}
