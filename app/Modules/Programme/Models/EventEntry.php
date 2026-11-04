<?php

namespace App\Modules\Programme\Models;

use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Programme\Enums\EventEntryStatus;
use App\Modules\Roster\Enums\PersonRole;
use App\Modules\Roster\Models\Player;
use App\Models\User;
use App\Modules\Sport\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A participation snapshot: role, jersey and category are recorded at event time
 * and are never rewritten when the player's live roster changes.
 */
class EventEntry extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'event_id',
        'player_id',
        'district_association_id',
        'state_team_member_id',
        'category_id',
        'role',
        'status',
        'jersey_number',
        'team_name',
        'placement',
        'remarks',
        'recorded_by',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'role' => PersonRole::class,
            'status' => EventEntryStatus::class,
            'jersey_number' => 'integer',
            'placement' => 'integer',
            'recorded_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function districtAssociation(): BelongsTo
    {
        return $this->belongsTo(DistrictAssociation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isCompetitor(): bool
    {
        return $this->role->isCompetitor();
    }

    public function displayName(): string
    {
        return $this->player?->name ?? 'Removed player';
    }
}
