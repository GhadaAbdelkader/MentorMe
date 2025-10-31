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

public static array $profileFields = [
    'specialization',
    'bio',
    'experience_years',
    'available_hours',
    'linkedin_url',
    'is_available',
];

public function completionPercentage(): int
{
    $fields= self::$profileFields;
    $filled = 0;
    foreach ($fields as $field) {
        $val = $this->{$field} ?? null;
        if(!empty($val) && $val !== '[]') {
            $filled++;
        }
    }
    return (int) round(($filled / count($fields)) * 100);
}

public function completionItems(): array
{
    $fields = self::$profileFields;
    $items = [];

    foreach ($fields as $field) {
        $label = ucwords(str_replace('_', ' ', $field));
        $items[] = [
            'field' => $field,
            'label' => $label,
            'filled' => !empty($this->{$field}) && $this->{$field} !== '[]',
        ];
    }
    return $items;
}
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
