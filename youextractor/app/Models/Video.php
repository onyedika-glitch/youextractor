<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'youtube_id',
        'title',
        'description',
        'transcript',
        'explanation',
        'code_snippets',
        'tech_stack',
        'setup_instructions',
        'dependencies',
        'summary',
        'duration',
        'published_at',
        'extracted_at',
        'tutorial_guide',
        'ide_recommendations',
        'prerequisites',
        'setup_guide',
        'run_guide',
        'extraction_status',
        'extraction_error',
        'github_repo_url',
    ];

    protected $casts = [
        'code_snippets' => 'array',
        'tech_stack' => 'array',
        'dependencies' => 'array',
        'tutorial_guide' => 'array',
        'ide_recommendations' => 'array',
        'prerequisites' => 'array',
        'setup_guide' => 'array',
        'run_guide' => 'array',
        'published_at' => 'datetime',
        'extracted_at' => 'datetime',
    ];

    /**
     * Check if video has extracted code
     */
    public function hasCode(): bool
    {
        return !empty($this->code_snippets);
    }

    /**
     * Whether the background job is still running.
     */
    public function isProcessing(): bool
    {
        return in_array($this->extraction_status, ['pending', 'processing']);
    }

    /**
     * Get the primary language
     */
    public function getPrimaryLanguageAttribute(): ?string
    {
        return $this->tech_stack['primary'] ?? null;
    }

    /**
     * Get file count
     */
    public function getFileCountAttribute(): int
    {
        return count($this->code_snippets ?? []);
    }
}
