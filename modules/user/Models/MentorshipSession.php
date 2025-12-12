<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MentorshipSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mentorship_sessions';

    protected $fillable = [
        'mentor_id',
        'mentee_id',
        'scheduled_at',
        'duration',
        'status',
        'notes'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function mentee()
    {
        return $this->belongsTo(User::class, 'mentee_id');
    }
}
