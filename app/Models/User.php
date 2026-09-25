<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

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
     * Whether this account already received its one free extraction.
     * Completed videos count even when free_extractions_used was never
     * incremented (the counter was not wired up for earlier extractions).
     */
    public function hasUsedFreeExtraction(): bool
    {
        if ((int) $this->free_extractions_used >= 1) {
            return true;
        }

        return $this->videos()->where('extraction_status', 'completed')->exists();
    }

    /**
     * Check if user can perform an extraction.
     */
    public function canExtract(): bool
    {
        if ($this->isProActive()) {
            return true;
        }

        if ((int) $this->credits > 0) {
            return true;
        }

        return ! $this->hasUsedFreeExtraction();
    }

    /**
     * Reserve one extraction before work starts so a second request
     * cannot slip through while the first job is still running.
     *
     * @return 'pro'|'credit'|'free'|null null when the free slot is gone and nothing is paid
     */
    public function consumeExtraction(): ?string
    {
        return DB::transaction(function () {
            /** @var self|null $fresh */
            $fresh = static::query()->whereKey($this->id)->lockForUpdate()->first();

            if (! $fresh || ! $fresh->canExtract()) {
                return null;
            }

            if ($fresh->isProActive()) {
                return 'pro';
            }

            if ((int) $fresh->credits > 0) {
                $fresh->decrement('credits');

                return 'credit';
            }

            $fresh->increment('free_extractions_used');

            return 'free';
        });
    }

    /**
     * Give back an entitlement reserved by consumeExtraction() when the
     * extraction never succeeds.
     */
    public function refundExtraction(?string $kind): void
    {
        if ($kind === 'credit') {
            $this->newQuery()->whereKey($this->id)->increment('credits');

            return;
        }

        if ($kind === 'free') {
            $this->newQuery()
                ->whereKey($this->id)
                ->where('free_extractions_used', '>', 0)
                ->decrement('free_extractions_used');
        }
    }

    /**
     * Deduct credit or record free extraction usage on successful extraction.
     */
    public function recordSuccessfulExtraction(): void
    {
        $this->consumeExtraction();
    }
}