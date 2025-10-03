<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Mentor extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'specialization',
        'bio',
        'experience_years',
        'available_hours',
        'linkedin_url',
        'is_available',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
