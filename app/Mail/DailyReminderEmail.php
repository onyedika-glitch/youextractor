<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class DailyReminderEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $unsubscribeUrl = '';

    public function __construct(
        public User $user,
        public string $tip = '',
    ) {
        // A signed link, so only the person who got the email can use it.
        $this->unsubscribeUrl = $user->exists
            ? URL::signedRoute('email.unsubscribe', ['user' => $user->id])
            : '#';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your next tutorial is waiting on YouExtractor',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.daily-reminder',
        );
    }

    /**
     * Lets Gmail, Outlook and others show their own "Unsubscribe" button.
     */
    public function headers(): Headers
    {
        if ($this->unsubscribeUrl === '#') {
            return new Headers();
        }

        return new Headers(text: [
            'List-Unsubscribe' => '<' . $this->unsubscribeUrl . '>',
            'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
        ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
