<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OauthLoginCode extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'code',
        'meta',
        'expires_at',
        'consumed_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at instanceof Carbon ? $this->expires_at->isPast() : true;
    }

    public function isConsumed(): bool
    {
        return ! is_null($this->consumed_at);
    }
}
