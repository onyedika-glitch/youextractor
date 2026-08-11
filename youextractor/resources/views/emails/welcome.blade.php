@php
    $firstName = trim(explode(' ', $user->name ?? '')[0] ?? '') ?: 'there';
@endphp

<x-mail-layout :title="'Welcome to YouExtractor'" :preheader="'Welcome aboard! Here\'s how to turn your first tutorial video into a full code project.'">

<h1 style="margin:0 0 8px 0; font-size:26px; line-height:1.3; font-weight:700; color:#0f172a;">
    Welcome aboard, {{ $firstName }}!
</h1>

<p style="margin:0 0 20px 0; font-size:16px; line-height:1.7; color:#475569;">
    We're genuinely thrilled to have you at <strong style="color:#0f172a;">YouExtractor</strong>. You've just joined a community of developers who've decided that watching, pausing, rewinding, and squinting at code in tutorial videos is a thing of the past.
</p>

<p style="margin:0 0 28px 0; font-size:16px; line-height:1.7; color:#475569;">
    From this moment on, any programming tutorial on YouTube can become a <strong style="color:#0f172a;">fully structured, ready-to-run codebase</strong> — extracted, organized, and explained for you in seconds.
</p>

{{-- Primary CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 32px 0;">
    <tr>
        <td style="border-radius:10px; background:linear-gradient(90deg,#14b8a6 0%,#0ea5e9 100%); background-color:#14b8a6;">
            <a href="https://youextractor.me/dashboard" style="display:inline-block; padding:15px 34px; font-family:'Segoe UI',Helvetica,Arial,sans-serif; font-size:16px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px;">
                Extract your first video →
            </a>
        </td>
    </tr>
</table>

{{-- How it works --}}
<h2 style="margin:0 0 18px 0; font-size:18px; font-weight:700; color:#0f172a;">
    Here's how it works — in 3 steps
</h2>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 16px 0;">
    <tr>
        <td width="40" style="vertical-align:top; padding:0 14px 18px 0;">
            <div style="width:34px; height:34px; border-radius:50%; background-color:#ccfbf1; color:#0f766e; font-size:16px; font-weight:700; text-align:center; line-height:34px;">1</div>
        </td>
        <td style="vertical-align:top; padding:0 0 18px 0;">
            <p style="margin:0 0 3px 0; font-size:15px; font-weight:700; color:#0f172a;">Paste a YouTube link</p>
            <p style="margin:0; font-size:14px; line-height:1.6; color:#64748b;">Grab the URL of any coding tutorial and drop it into your dashboard. That's the whole input.</p>
        </td>
    </tr>
    <tr>
        <td width="40" style="vertical-align:top; padding:0 14px 18px 0;">
            <div style="width:34px; height:34px; border-radius:50%; background-color:#ccfbf1; color:#0f766e; font-size:16px; font-weight:700; text-align:center; line-height:34px;">2</div>
        </td>
        <td style="vertical-align:top; padding:0 0 18px 0;">
            <p style="margin:0 0 3px 0; font-size:15px; font-weight:700; color:#0f172a;">We extract & structure the code</p>
            <p style="margin:0; font-size:14px; line-height:1.6; color:#64748b;">Our AI watches the video for you — pulling out every file, command, and snippet, then organizing it into a proper project tree.</p>
        </td>
    </tr>
    <tr>
        <td width="40" style="vertical-align:top; padding:0 14px 0 0;">
            <div style="width:34px; height:34px; border-radius:50%; background-color:#ccfbf1; color:#0f766e; font-size:16px; font-weight:700; text-align:center; line-height:34px;">3</div>
        </td>
        <td style="vertical-align:top; padding:0;">
            <p style="margin:0 0 3px 0; font-size:15px; font-weight:700; color:#0f172a;">Download, run, and learn</p>
            <p style="margin:0; font-size:14px; line-height:1.6; color:#64748b;">Get a clean, documented codebase you can run immediately — with explanations so you actually understand what you built.</p>
        </td>
    </tr>
</table>

{{-- Divider --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr><td style="border-top:1px solid #f1f5f9; font-size:0; line-height:0; padding:24px 0;">&nbsp;</td></tr></table>

{{-- Tips --}}
<h2 style="margin:0 0 14px 0; font-size:18px; font-weight:700; color:#0f172a;">
    A few things to try first
</h2>
<ul style="margin:0 0 24px 0; padding:0 0 0 20px; font-size:15px; line-height:1.8; color:#475569;">
    <li style="margin-bottom:8px;"><strong style="color:#0f172a;">Install the Chrome extension</strong> — extract any tutorial without leaving YouTube.</li>
    <li style="margin-bottom:8px;"><strong style="color:#0f172a;">Start with a short video</strong> — a 10–20 minute tutorial is the perfect first run.</li>
    <li style="margin-bottom:8px;"><strong style="color:#0f172a;">Revisit your library</strong> — every extraction is saved to your account so you can come back anytime.</li>
</ul>

<p style="margin:0 0 8px 0; font-size:15px; line-height:1.7; color:#475569;">
    Got a question or hit a snag? Just reply to this email — a real human reads every message, and we'd love to hear what you're building.
</p>

<p style="margin:24px 0 4px 0; font-size:15px; line-height:1.6; color:#475569;">
    Happy extracting,
</p>
<p style="margin:0; font-size:15px; line-height:1.6; color:#0f172a; font-weight:600;">
    The YouExtractor Team
</p>

</x-mail-layout>
