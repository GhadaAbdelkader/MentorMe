<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use modules\user\Models\User;


class MentorshipRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'mentee_id',
        'mentor_id',
        'status',
        'message',
    ];


    protected $casts = [
        'status' => 'string',
    ];



    public function mentee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }


    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }
}
