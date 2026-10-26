<?php

namespace App\Markdown\Renderers;

use App\Markdown\Format;
use App\Markdown\MarkdownPage;

/**
 * Keep in sync with resources/views/support.blade.php.
 */
class SupportPage extends StaticPage
{
    public function pattern(): string
    {
        return '/support';
    }

    public function render(array $params): ?MarkdownPage
    {
        $email = config('site.contact.email');
        $issues = config('site.contact.issues');

        $faqs = [
            ['q' => 'Is my GitHub token safe?', 'a' => "Absolutely. We do not store your GitHub Personal Access Token on our database. It is stored transiently on your local browser/session, and is only sent to GitHub's official API to push your generated repo."],
            ['q' => 'The Chrome Extension says "Go to a YouTube Video". What do I do?', 'a' => 'The YouExtractor Chrome Extension is designed to activate only when you are on a YouTube video playback page (URLs starting with `youtube.com/watch` or `youtu.be/`). Open any coding tutorial first, and the "Extract Code & Guide" button will light up.'],
            ['q' => 'Can I extract code from long videos?', 'a' => 'Yes, YouExtractor can parse long coding tutorials. If you run into any API rate limits or timeout errors on extremely long videos, please reach out to us at our email address above so we can inspect and optimize it.'],
        ];

        $body = <<<MD
        Have questions, feedback, or run into an issue? Choose a channel below to get help with YouExtractor.

        ## Support channels

        | Channel | Use it for | Link |
        |---|---|---|
        | Direct email | Account queries, billing concerns, API issues, or generic support inquiries. We typically reply within 24 hours. | [{$email}](mailto:{$email}) |
        | GitHub Issues | Technical bugs, Chrome extension issues, feature requests, or open-source discussions. | [Open a GitHub issue]({$issues}) |
        | Developer tip | Support YouExtractor's development: hosted server infrastructure and model APIs. | [Buy Me A Coffee](https://buymeacoffee.com/omogo) |

        ## Frequently Asked Questions


        MD;

        return new MarkdownPage(
            title: 'Support & Help Center',
            summary: 'Get support for YouExtractor. Email, GitHub issues, and help converting YouTube coding tutorials into runnable code projects.',
            path: '/support',
            body: $body . Format::faq($faqs),
            updated: Format::fileDate('resources/views/support.blade.php'),
            related: [['Privacy Policy', '/privacy'], ['Terms of Service', '/terms']],
        );
    }
}
