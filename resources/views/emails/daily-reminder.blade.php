@php
    $firstName = trim(explode(' ', $user->name ?? '')[0] ?? '') ?: 'there';
    $defaultTip = 'Try extracting a build-along project video — you\'ll end up with a runnable repo and notes explaining every decision the creator made.';
    $showTip = $tip !== '' ? $tip : $defaultTip;
@endphp

<x-mail-layout :title="'Your daily YouExtractor reminder'" :preheader="'A quick nudge — turn today\'s tutorial into real, running code.'">

<h1 style="margin:0 0 8px 0; font-size:24px; line-height:1.3; font-weight:700; color:#0f172a;">
    Ready to learn something today, {{ $firstName }}?
</h1>

<p style="margin:0 0 22px 0; font-size:16px; line-height:1.7; color:#475569;">
    Every day you spend learning is a day your future self will thank you for. There's a tutorial out there right now that could level up your skills — and YouExtractor can turn it into a real, structured project before your coffee gets cold. ☕
</p>

{{-- Highlight box --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 26px 0;">
    <tr>
        <td style="background-color:#f0fdfa; border:1px solid #99f6e4; border-left:4px solid #14b8a6; border-radius:8px; padding:18px 20px;">
            <p style="margin:0 0 4px 0; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:#0f766e;">Tip of the day</p>
            <p style="margin:0; font-size:15px; line-height:1.6; color:#334155;">{{ $showTip }}</p>
        </td>
    </tr>
</table>

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 30px 0;">
    <tr>
        <td style="border-radius:10px; background:linear-gradient(90deg,#14b8a6 0%,#0ea5e9 100%); background-color:#14b8a6;">
            <a href="https://youextractor.me/dashboard" style="display:inline-block; padding:15px 34px; font-family:'Segoe UI',Helvetica,Arial,sans-serif; font-size:16px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px;">
                Extract a video now →
            </a>
        </td>
    </tr>
</table>

{{-- Why it matters --}}
<h2 style="margin:0 0 14px 0; font-size:18px; font-weight:700; color:#0f172a;">
    Why show up every day?
</h2>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td style="vertical-align:top; padding:0 0 14px 0;">
            <p style="margin:0; font-size:15px; line-height:1.7; color:#475569;">
                <span style="color:#14b8a6; font-weight:700;">Consistency beats intensity.</span> Ten minutes a day compounds faster than a weekend cram session you'll forget by Monday.
            </p>
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top; padding:0 0 14px 0;">
            <p style="margin:0; font-size:15px; line-height:1.7; color:#475569;">
                <span style="color:#14b8a6; font-weight:700;">Learn by doing.</span> Reading code isn't the same as running it. YouExtractor hands you a project you can actually break, fix, and truly understand.
            </p>
        </td>
    </tr>
    <tr>
        <td style="vertical-align:top;">
            <p style="margin:0; font-size:15px; line-height:1.7; color:#475569;">
                <span style="color:#14b8a6; font-weight:700;">📚 Build a library.</span> Every extraction stacks up in your account — your own growing collection of ready-to-run reference projects.
            </p>
        </td>
    </tr>
</table>

{{-- Divider --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr><td style="border-top:1px solid #f1f5f9; font-size:0; line-height:0; padding:24px 0;">&nbsp;</td></tr></table>

<p style="margin:0 0 6px 0; font-size:14px; line-height:1.7; color:#64748b;">
    Not in the mood today? No worries — we'll be here tomorrow. But if you've got even five minutes, one small extraction keeps your streak (and your momentum) alive.
</p>

<p style="margin:22px 0 4px 0; font-size:15px; line-height:1.6; color:#475569;">
    See you in the dashboard,
</p>
<p style="margin:0; font-size:15px; line-height:1.6; color:#0f172a; font-weight:600;">
    The YouExtractor Team
</p>

</x-mail-layout>
