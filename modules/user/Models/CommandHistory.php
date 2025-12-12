<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandHistory extends Model
{
    protected $table = 'command_history';

    protected $fillable = [
        'command_type',
        'user_id',
        'user_type',
        'payload',
        'executed_at',
        'undone_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'executed_at' => 'datetime',
        'undone_at' => 'datetime'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function scopeNotUndone($query)
    {
        return $query->whereNull('undone_at');
    }


    public function scopeWithin24Hours($query)
    {
        return $query->where('executed_at', '>=', now()->subHours(24));
    }
}
