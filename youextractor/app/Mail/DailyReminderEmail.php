<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyReminderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $tip = '',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👋 Your daily dose of code awaits on YouExtractor',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-reminder',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
