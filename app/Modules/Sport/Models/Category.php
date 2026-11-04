<?php

namespace App\Modules\Sport\Models;

use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use BelongsToAssociation;

    public const GENDERS = [
        'any' => 'Any',
        'male' => 'Male',
        'female' => 'Female',
    ];

    protected $fillable = [
        'discipline_id',
        'name',
        'slug',
        'gender',
        'min_age',
        'max_age',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'min_age' => 'integer',
            'max_age' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function genderLabel(): string
    {
        return self::GENDERS[$this->gender] ?? 'Any';
    }

    public function ageLabel(): string
    {
        if ($this->min_age !== null && $this->max_age !== null) {
            return "{$this->min_age}-{$this->max_age} years";
        }

        if ($this->min_age !== null) {
            return "{$this->min_age}+ years";
        }

        if ($this->max_age !== null) {
            return "Under {$this->max_age}";
        }

        return 'Open age';
    }
}
