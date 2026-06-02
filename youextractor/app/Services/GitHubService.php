<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * GitHubService
 *
 * Creates a new GitHub repository and pushes extracted code files into it,
 * all via the GitHub REST API.  Requires a GitHub personal access token
 * with `repo` scope stored in GITHUB_TOKEN (.env).
 *
 * Usage:
 *   $github = new GitHubService($userToken);
 *   $repoUrl = $github->pushProject($repoName, $description, $files);
 */
class GitHubService
{
    private string $token;
    private string $baseUrl = 'https://api.github.com';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Create a new public repo and commit all extracted files.
     *
     * @param  string  $repoName    Sanitised repo name (no spaces).
     * @param  string  $description Short repo description.
     * @param  array   $files       Extracted code files from codeData['files'].
     * @return string|null          HTML URL of the created repo, or null on failure.
     */
    public function pushProject(string $repoName, string $description, array $files): ?string
    {
        $username = $this->getAuthenticatedUsername();
        if (!$username) {
            return null;
        }

        // 1. Create the repository
        $repoUrl = $this->createRepository($repoName, $description);
        if (!$repoUrl) {
            return null;
        }

        // 2. Push all files in a single commit via the Git Trees API
        $this->commitFiles($username, $repoName, $files);

        return $repoUrl;
    }

    // ------------------------------------------------------------------
    // Private helpers
    // ------------------------------------------------------------------

    private function getAuthenticatedUsername(): ?string
    {
        try {
            $response = $this->github()->get("{$this->baseUrl}/user");
            if ($response->successful()) {
                return $response->json('login');
            }
            Log::warning('[GitHub] Could not get authenticated user: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('[GitHub] getAuthenticatedUsername: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Create a repo and return its HTML URL.
     */
    private function createRepository(string $name, string $description): ?string
    {
        try {
            $response = $this->github()->post("{$this->baseUrl}/user/repos", [
                'name'        => $name,
                'description' => $description,
                'private'     => false,
                'auto_init'   => false, // we push our own README
            ]);

            if ($response->successful()) {
                return $response->json('html_url');
            }

            // Handle name collision — append timestamp
            if ($response->status() === 422) {
                $newName  = $name . '-' . time();
                return $this->createRepository($newName, $description);
            }

            Log::warning('[GitHub] createRepository failed: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('[GitHub] createRepository: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Commit all files in one go using the Git Trees API.
     * This is much more efficient than creating blobs one-by-one.
     */
    private function commitFiles(string $username, string $repoName, array $files): void
    {
        try {
            $repo = "{$this->baseUrl}/repos/{$username}/{$repoName}";

            // Build tree entries
            $treeItems = [];
            foreach ($files as $file) {
                $path = $file['path'] ?? $file['filename'];
                $code = $file['code'] ?? '';

                $treeItems[] = [
                    'path'    => $path,
                    'mode'    => '100644',
                    'type'    => 'blob',
                    'content' => $code,
                ];
            }

            // Add a README
            $treeItems[] = [
                'path'    => 'README.md',
                'mode'    => '100644',
                'type'    => 'blob',
                'content' => "# {$repoName}\n\n> Pushed by [YouExtractor](https://github.com/youextractor) — Turn YouTube tutorials into runnable code.\n",
            ];

            // Create tree
            $treeResponse = $this->github()->post("{$repo}/git/trees", [
                'tree' => $treeItems,
            ]);

            if (!$treeResponse->successful()) {
                Log::warning('[GitHub] Could not create tree: ' . $treeResponse->body());
                return;
            }

            $treeSha = $treeResponse->json('sha');

            // Create commit
            $commitResponse = $this->github()->post("{$repo}/git/commits", [
                'message' => '🚀 Initial commit — extracted by YouExtractor',
                'tree'    => $treeSha,
                'parents' => [],
            ]);

            if (!$commitResponse->successful()) {
                Log::warning('[GitHub] Could not create commit: ' . $commitResponse->body());
                return;
            }

            $commitSha = $commitResponse->json('sha');

            // Update HEAD to point to new commit
            $refResponse = $this->github()->post("{$repo}/git/refs", [
                'ref' => 'refs/heads/main',
                'sha' => $commitSha,
            ]);

            if (!$refResponse->successful()) {
                Log::warning('[GitHub] Could not update ref: ' . $refResponse->body());
            }

            Log::info("[GitHub] Successfully pushed {$repoName}");
        } catch (\Exception $e) {
            Log::error('[GitHub] commitFiles: ' . $e->getMessage());
        }
    }

    /** Pre-configured HTTP client for GitHub API calls. */
    private function github()
    {
        return Http::withHeaders([
            'Authorization' => "Bearer {$this->token}",
            'Accept'        => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28',
        ])->timeout(30);
    }
}
