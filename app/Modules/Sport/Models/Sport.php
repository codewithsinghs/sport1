<?php

namespace App\Modules\Sport\Models;

use App\Modules\Association\Models\Association;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sport extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function associations(): BelongsToMany
    {
        return $this->belongsToMany(Association::class, 'association_sports')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }
}
