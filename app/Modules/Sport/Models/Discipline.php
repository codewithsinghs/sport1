<?php

namespace App\Modules\Sport\Models;

use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discipline extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'sport_id',
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

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }
}
