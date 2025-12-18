<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'youtube_id',
        'title',
        'description',
        'transcript',

        // Extraction results
        'explanation',
        'code_snippets',
        'tech_stack',
        'dependencies',
        'summary',

        // Extraction lifecycle
        'extraction_status',
        'extraction_error',
        'repository_url',
        'confidence',

        // Guides
        'tutorial_guide',
        'ide_recommendations',
        'prerequisites',
        'setup_guide',
        'run_guide',

        // Meta
        'duration',
        'published_at',
        'extracted_at',
    ];

    protected $casts = [
        'code_snippets'        => 'array',
        'tech_stack'           => 'array',
        'dependencies'         => 'array',
        'confidence'           => 'array',

        'tutorial_guide'       => 'array',
        'ide_recommendations'  => 'array',
        'prerequisites'        => 'array',
        'setup_guide'          => 'array',
        'run_guide'            => 'array',

        'published_at'         => 'datetime',
        'extracted_at'         => 'datetime',
    ];

    /* -------------------------
     |  Extraction State Helpers
     | -------------------------
     */

    public function isPending(): bool
    {
        return $this->extraction_status === 'pending';
    }

    public function isExtracting(): bool
    {
        return $this->extraction_status === 'extracting';
    }

    public function isCompleted(): bool
    {
        return $this->extraction_status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->extraction_status === 'failed';
    }

    public function hasNoCode(): bool
    {
        return $this->extraction_status === 'no_code_detected';
    }

    /* -------------------------
     |  Code Validity (IMPORTANT)
     | -------------------------
     */

    public function hasValidCode(): bool
    {
        if (!$this->isCompleted()) {
            return false;
        }

        if (empty($this->code_snippets)) {
            return false;
        }

        // Confidence gate (prevents hallucinations)
        $confidence = $this->confidence['code_confidence'] ?? 0;

        return $confidence >= 0.6;
    }

    /* -------------------------
     |  Accessors
     | -------------------------
     */

    public function getPrimaryLanguageAttribute(): ?string
    {
        return $this->tech_stack['primary'] ?? null;
    }

    public function getFileCountAttribute(): int
    {
        return count($this->code_snippets ?? []);
    }
}
