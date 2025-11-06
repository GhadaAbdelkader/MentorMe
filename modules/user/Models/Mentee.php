<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Mentee extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'interests',
        'goals',
        'current_level',
        'mentor_id'
    ];
    public static array $profileFields = [
        'interests',
        'goals',
        'current_level',
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

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }


}
