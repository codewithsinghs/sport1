<?php

namespace App\Modules\Roster\Models;

use App\Models\User;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Programme\Models\DistrictAssociation;
use App\Modules\Roster\Enums\PersonRole;
use App\Modules\Roster\Enums\RosterStatus;
use App\Modules\Sport\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistrictTeamMember extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'district_association_id',
        'player_id',
        'category_id',
        'role',
        'status',
        'jersey_number',
        'joined_on',
        'left_on',
        'remarks',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'role' => PersonRole::class,
            'status' => RosterStatus::class,
            'jersey_number' => 'integer',
            'joined_on' => 'date',
            'left_on' => 'date',
        ];
    }

    public function districtAssociation(): BelongsTo
    {
        return $this->belongsTo(DistrictAssociation::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stateTeamMembers(): HasMany
    {
        return $this->hasMany(StateTeamMember::class);
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->whereNull('left_on')->whereIn('status', [
            RosterStatus::Active->value,
            RosterStatus::Injured->value,
            RosterStatus::Trialist->value,
        ]);
    }

    public function isCurrent(): bool
    {
        return $this->left_on === null && in_array($this->status, [
            RosterStatus::Active,
            RosterStatus::Injured,
            RosterStatus::Trialist,
        ], true);
    }

    public function squadLabel(): string
    {
        return $this->category?->name ?? 'Uncategorised';
    }
}
