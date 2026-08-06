@php
    $firstName = trim(explode(' ', $user->name ?? '')[0] ?? '') ?: 'there';

    // Per-activity presentation config.
    $map = [
        'login' => [
            'emoji' => '🔐',
            'heading' => 'New sign-in to your account',
            'intro' => 'We noticed a new sign-in to your YouExtractor account. If this was you, you\'re all set — no action needed.',
            'accent' => '#14b8a6',
            'accentBg' => '#f0fdfa',
            'accentBorder' => '#99f6e4',
        ],
        'logout' => [
            'emoji' => '👋',
            'heading' => 'You\'ve been signed out',
            'intro' => 'You just signed out of YouExtractor. Your work is saved and waiting for you — come back anytime.',
            'accent' => '#0ea5e9',
            'accentBg' => '#f0f9ff',
            'accentBorder' => '#bae6fd',
        ],
        'video_extracted' => [
            'emoji' => '✅',
            'heading' => 'Your extraction is ready',
            'intro' => 'Great news — we finished extracting your video into a structured code project. It\'s ready to view and download.',
            'accent' => '#16a34a',
            'accentBg' => '#f0fdf4',
            'accentBorder' => '#bbf7d0',
        ],
        'profile_updated' => [
            'emoji' => '✏️',
            'heading' => 'Your profile was updated',
            'intro' => 'Your YouExtractor profile details were just changed. If this was you, no further action is needed.',
            'accent' => '#7c3aed',
            'accentBg' => '#faf5ff',
            'accentBorder' => '#e9d5ff',
        ],
    ];

    $cfg = $map[$activityType] ?? [
        'emoji' => '🔔',
        'heading' => 'Account activity',
        'intro' => 'There was recent activity on your YouExtractor account.',
        'accent' => '#14b8a6',
        'accentBg' => '#f0fdfa',
        'accentBorder' => '#99f6e4',
    ];

    $time = $activityData['time'] ?? now()->format('M j, Y \a\t g:i A T');
    $ip = $activityData['ip'] ?? null;
    $device = $activityData['device'] ?? null;
    $location = $activityData['location'] ?? null;
    $security = in_array($activityType, ['login', 'logout', 'profile_updated'], true);
@endphp

<x-mail-layout :title="$cfg['heading']" :preheader="$cfg['intro']">

<h1 style="margin:0 0 8px 0; font-size:23px; line-height:1.3; font-weight:700; color:#0f172a;">
    {{ $cfg['emoji'] }} {{ $cfg['heading'] }}
</h1>

<p style="margin:0 0 22px 0; font-size:16px; line-height:1.7; color:#475569;">
    Hi {{ $firstName }}, {{ $cfg['intro'] }}
</p>

{{-- Details card --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 26px 0;">
    <tr>
        <td style="background-color:{{ $cfg['accentBg'] }}; border:1px solid {{ $cfg['accentBorder'] }}; border-radius:10px; padding:20px 22px;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:0 0 10px 0; font-size:13px; color:#64748b; width:110px;">Account</td>
                    <td style="padding:0 0 10px 0; font-size:14px; color:#0f172a; font-weight:600;">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td style="padding:0 0 10px 0; font-size:13px; color:#64748b;">When</td>
                    <td style="padding:0 0 10px 0; font-size:14px; color:#0f172a; font-weight:600;">{{ $time }}</td>
                </tr>
                @if($device)
                <tr>
                    <td style="padding:0 0 10px 0; font-size:13px; color:#64748b;">Device</td>
                    <td style="padding:0 0 10px 0; font-size:14px; color:#0f172a; font-weight:600;">{{ $device }}</td>
                </tr>
                @endif
                @if($location)
                <tr>
                    <td style="padding:0 0 10px 0; font-size:13px; color:#64748b;">Location</td>
                    <td style="padding:0 0 10px 0; font-size:14px; color:#0f172a; font-weight:600;">{{ $location }}</td>
                </tr>
                @endif
                @if($ip)
                <tr>
                    <td style="padding:0; font-size:13px; color:#64748b;">IP address</td>
                    <td style="padding:0; font-size:14px; color:#0f172a; font-weight:600;">{{ $ip }}</td>
                </tr>
                @endif
            </table>
        </td>
    </tr>
</table>

{{-- CTA --}}
<table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 0 28px 0;">
    <tr>
        <td style="border-radius:10px; background-color:{{ $cfg['accent'] }};">
            <a href="https://youextractor.me/dashboard" style="display:inline-block; padding:14px 30px; font-family:'Segoe UI',Helvetica,Arial,sans-serif; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:10px;">
                @if($activityType === 'video_extracted') View your project → @else Go to dashboard → @endif
            </a>
        </td>
    </tr>
</table>

@if($security)
{{-- Security note --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 8px 0;">
    <tr>
        <td style="background-color:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:16px 18px;">
            <p style="margin:0; font-size:14px; line-height:1.6; color:#92400e;">
                <strong>Didn't do this?</strong> If you don't recognize this activity, please
                <a href="https://youextractor.me/support" style="color:#b45309; font-weight:700; text-decoration:underline;">contact support</a>
                right away and consider changing your password.
            </p>
        </td>
    </tr>
</table>
@endif

<p style="margin:22px 0 4px 0; font-size:15px; line-height:1.6; color:#475569;">
    Stay secure,
</p>
<p style="margin:0; font-size:15px; line-height:1.6; color:#0f172a; font-weight:600;">
    The YouExtractor Team
</p>

</x-mail-layout>
