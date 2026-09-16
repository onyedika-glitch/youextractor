<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'checkout_id',
        'amount',
        'currency',
        'plan_type',
        'credits_added',
        'status',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'credits_added' => 'integer',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
