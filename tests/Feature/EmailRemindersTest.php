<?php

namespace Tests\Feature;

use App\Mail\DailyReminderEmail;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailRemindersTest extends TestCase
{
    use RefreshDatabase;

    /** Tests that need a database run only where the SQLite driver is installed. */
    protected function setUpTraits()
    {
        if (! extension_loaded('pdo_sqlite') && $this->needsDatabase()) {
            $this->markTestSkipped('pdo_sqlite is not installed on this machine.');
        }

        return parent::setUpTraits();
    }

    private function needsDatabase(): bool
    {
        return ! in_array($this->name(), [
            'test_reminder_email_has_unsubscribe_link_and_headers',
            'test_reminder_runs_daily_in_batches_of_20',
        ], true);
    }

    protected function refreshDatabase()
    {
        if ($this->needsDatabase()) {
            parent::refreshDatabase();
        }
    }

    private function makeUser(array $attrs = []): User
    {
        return User::create(array_merge([
            'name' => 'Ada Lovelace',
            'email' => 'ada' . uniqid() . '@example.com',
            'password' => 'secret-password',
        ], $attrs));
    }

    public function test_signed_link_unsubscribes_the_user(): void
    {
        $user = $this->makeUser();
        $url = URL::signedRoute('email.unsubscribe', ['user' => $user->id]);

        $this->get($url)->assertOk()->assertSee("You're unsubscribed", false);

        $this->assertFalse($user->fresh()->email_reminders);
    }

    public function test_link_without_a_valid_signature_is_rejected(): void
    {
        $user = $this->makeUser();

        $this->get('/unsubscribe/' . $user->id)->assertForbidden();

        $this->assertTrue($user->fresh()->email_reminders);
    }

    public function test_one_click_post_from_mail_apps_works_without_csrf(): void
    {
        $user = $this->makeUser();
        $url = URL::signedRoute('email.unsubscribe', ['user' => $user->id]);

        $this->post($url, ['List-Unsubscribe' => 'One-Click'])->assertOk();

        $this->assertFalse($user->fresh()->email_reminders);
    }

    public function test_reminder_email_has_unsubscribe_link_and_headers(): void
    {
        $user = new User(['name' => 'Ada Lovelace', 'email' => 'ada@example.com']);
        $user->id = 42;
        $user->exists = true;
        $mail = new DailyReminderEmail($user, 'A tip');

        $mail->assertSeeInHtml('Unsubscribe from reminder emails');
        $mail->assertSeeInHtml('/unsubscribe/' . $user->id, false);

        $headers = $mail->headers()->text;
        $this->assertStringContainsString('/unsubscribe/' . $user->id, $headers['List-Unsubscribe']);
        $this->assertSame('List-Unsubscribe=One-Click', $headers['List-Unsubscribe-Post']);
    }

    public function test_command_skips_users_who_unsubscribed(): void
    {
        Mail::fake();
        $subscribed = $this->makeUser();
        $unsubscribed = $this->makeUser(['email_reminders' => false]);

        $this->artisan('youextractor:daily-reminders')->assertSuccessful();

        Mail::assertQueued(DailyReminderEmail::class, fn ($m) => $m->hasTo($subscribed->email));
        Mail::assertNotQueued(DailyReminderEmail::class, fn ($m) => $m->hasTo($unsubscribed->email));
    }

    public function test_reminder_runs_daily_in_batches_of_20(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(fn ($e) => str_contains($e->command ?? '', 'youextractor:daily-reminders'));
        $this->assertNotNull($event);
        $this->assertStringContainsString('--limit=20', $event->command);
        $this->assertSame('0 9 * * *', $event->expression);
    }

    public function test_command_sends_at_most_the_limit_and_skips_recently_reminded(): void
    {
        Mail::fake();
        $recent = $this->makeUser(['email' => 'recent@example.com']);
        $recent->forceFill(['last_reminder_at' => now()->subDay()])->save();
        $old = $this->makeUser(['email' => 'old@example.com']);
        $old->forceFill(['last_reminder_at' => now()->subDays(4)])->save();
        $new1 = $this->makeUser(['email' => 'new1@example.com']);
        $new2 = $this->makeUser(['email' => 'new2@example.com']);

        $this->artisan('youextractor:daily-reminders', ['--limit' => 2])->assertSuccessful();

        Mail::assertQueued(DailyReminderEmail::class, 2);
        Mail::assertQueued(DailyReminderEmail::class, fn ($m) => $m->hasTo('new1@example.com'));
        Mail::assertQueued(DailyReminderEmail::class, fn ($m) => $m->hasTo('new2@example.com'));
        Mail::assertNotQueued(DailyReminderEmail::class, fn ($m) => $m->hasTo('recent@example.com'));
        $this->assertNotNull($new1->fresh()->last_reminder_at);
    }
}
