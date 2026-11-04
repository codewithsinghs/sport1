<?php

namespace App\Modules\Roster\Models;

use App\Models\User;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Roster\Enums\PersonRole;
use App\Modules\Roster\Enums\RosterStatus;
use App\Modules\Sport\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateTeamMember extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'player_id',
        'district_team_member_id',
        'category_id',
        'role',
        'status',
        'jersey_number',
        'selected_on',
        'expires_on',
        'remarks',
        'created_by',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'role' => PersonRole::class,
            'status' => RosterStatus::class,
            'jersey_number' => 'integer',
            'selected_on' => 'date',
            'expires_on' => 'date',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function districtTeamMember(): BelongsTo
    {
        return $this->belongsTo(DistrictTeamMember::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query
            ->where('status', RosterStatus::Active)
            ->where(function (Builder $window): void {
                $window->whereNull('expires_on')
                    ->orWhere('expires_on', '>=', now()->toDateString());
            });
    }

    public function districtLabel(): string
    {
        return $this->districtTeamMember?->districtAssociation?->name ?? 'Direct selection';
    }

    public function isExpired(): bool
    {
        return $this->expires_on !== null && $this->expires_on->isPast();
    }
}
