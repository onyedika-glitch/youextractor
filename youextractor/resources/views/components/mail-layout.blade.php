{{--
    Shared email layout for YouExtractor (anonymous Blade component).
    Usage: <x-mail-layout :title="..." :preheader="...">...</x-mail-layout>
    Inline styles + table layout for maximum email-client compatibility.
    Brand: teal (#14b8a6) accent on a clean light canvas.
--}}
@props(['title' => 'YouExtractor', 'preheader' => 'Turn programming video tutorials into structured code projects in seconds.'])
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>{{ $title }}</title>
    <!--[if mso]>
    <style type="text/css">
        table, td, div, p, a { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f4f4f5; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    {{-- Preheader: hidden preview text shown in the inbox list --}}
    <div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#f4f4f5;">
        {{ $preheader }}
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;">
        <tr>
            <td align="center" style="padding:24px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; margin:0 auto;">

                    {{-- Header / brand bar --}}
                    <tr>
                        <td style="padding:0 0 20px 0;" align="center">
                            <a href="https://youextractor.me" style="text-decoration:none;">
                                <table role="presentation" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style="vertical-align:middle; padding-right:10px;">
                                            <img src="https://youextractor.me/img/youextractor-logo.jpg" width="36" height="36" alt="YouExtractor" style="display:block; border-radius:8px; border:1px solid rgba(20,184,166,0.25);">
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <span style="font-family:'Segoe UI',Helvetica,Arial,sans-serif; font-size:20px; font-weight:700; color:#0f172a; letter-spacing:-0.02em;">YouExtractor</span>
                                        </td>
                                    </tr>
                                </table>
                            </a>
                        </td>
                    </tr>

                    {{-- Accent strip --}}
                    <tr>
                        <td style="height:4px; background:linear-gradient(90deg,#14b8a6 0%,#0ea5e9 100%); background-color:#14b8a6; border-radius:4px 4px 0 0; font-size:0; line-height:0;">&nbsp;</td>
                    </tr>

                    {{-- Main card --}}
                    <tr>
                        <td style="background-color:#ffffff; border:1px solid #e4e4e7; border-top:none; border-radius:0 0 12px 12px; padding:40px 40px 32px 40px; font-family:'Segoe UI',Helvetica,Arial,sans-serif; color:#334155;">
                            {{ $slot }}
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:28px 20px 8px 20px; text-align:center; font-family:'Segoe UI',Helvetica,Arial,sans-serif;">
                            <p style="margin:0 0 12px 0; font-size:13px; color:#64748b; line-height:1.6;">
                                Turn programming video tutorials into fully structured codebase projects in seconds.<br>
                                Learn faster, code smarter.
                            </p>
                            <p style="margin:0 0 14px 0;">
                                <a href="https://youextractor.me/dashboard" style="color:#14b8a6; text-decoration:none; font-size:13px; font-weight:600;">Open Dashboard</a>
                                &nbsp;&nbsp;·&nbsp;&nbsp;
                                <a href="https://youextractor.me/blog" style="color:#14b8a6; text-decoration:none; font-size:13px; font-weight:600;">Blog</a>
                                &nbsp;&nbsp;·&nbsp;&nbsp;
                                <a href="https://youextractor.me/support" style="color:#14b8a6; text-decoration:none; font-size:13px; font-weight:600;">Support</a>
                            </p>
                            <p style="margin:0 0 6px 0; font-size:12px; color:#94a3b8;">
                                &copy; {{ date('Y') }} YouExtractor. Built with love for developers who learn visually.
                            </p>
                            <p style="margin:0; font-size:11px; color:#cbd5e1; line-height:1.6;">
                                You're receiving this email because you have a YouExtractor account.<br>
                                <a href="https://youextractor.me/support" style="color:#94a3b8; text-decoration:underline;">Contact support</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
