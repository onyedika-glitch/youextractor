<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'github_id',
        'avatar',
        'free_extractions_used',
        'credits',
        'is_pro',
        'pro_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_pro' => 'boolean',
            'pro_until' => 'datetime',
            'free_extractions_used' => 'integer',
            'credits' => 'integer',
        ];
    }

    /**
     * Get the user's videos.
     */
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the user's payments.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if user is on active Pro subscription.
     */
    public function isProActive(): bool
    {
        if ($this->is_pro) {
            if ($this->pro_until === null || $this->pro_until->isFuture()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user can perform an extraction.
     */
    public function canExtract(): bool
    {
        if ($this->isProActive()) {
            return true;
        }

        if ($this->credits > 0) {
            return true;
        }

        return $this->free_extractions_used < 1;
    }

    /**
     * Deduct credit or record free extraction usage on successful extraction.
     */
    public function recordSuccessfulExtraction(): void
    {
        if ($this->isProActive()) {
            return;
        }

        if ($this->credits > 0) {
            $this->decrement('credits');
            return;
        }

        $this->increment('free_extractions_used');
    }
}