<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use  HasFactory, Notifiable;

    /**
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_photo_path',
    ];

    /**
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'profile_photo_path',
    ];

    /**
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];


    /**
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public function mentorProfile(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    /**
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        return $this->defaultProfilePhotoUrl();
    }

    /**
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl(): string
    {
        $name = trim(collect(explode(' ', $this->name))->map(fn ($segment) => mb_substr($segment, 0, 1))->join(' '));

        return '[https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF](https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF)';
    }
}
