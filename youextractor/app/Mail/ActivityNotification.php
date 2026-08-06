<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivityNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $activityType,
        public array $activityData = [],
    ) {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'login' => '🔐 New login to your YouExtractor account',
            'logout' => '👋 You logged out of YouExtractor',
            'video_extracted' => '✅ Video extraction complete!',
            'profile_updated' => '✏️ Your profile was updated',
        ];

        return new Envelope(
            subject: $subjects[$this->activityType] ?? '🔔 Activity on your YouExtractor account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activity-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
