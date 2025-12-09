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
        'explanation',
        'code_snippets',
        'summary',
        'duration',
        'published_at',
        'extracted_at',
    ];

    protected $casts = [
        'code_snippets' => 'array',
        'published_at' => 'datetime',
        'extracted_at' => 'datetime',
    ];
}
