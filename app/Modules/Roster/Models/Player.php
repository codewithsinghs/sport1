<?php

namespace App\Modules\Roster\Models;

use App\Modules\Association\Models\Association;
use App\Modules\Programme\Models\Event;
use App\Modules\Programme\Models\EventEntry;
use App\Modules\Roster\Enums\Gender;
use App\Modules\Roster\Enums\PersonRole;
use App\Models\User;
use App\Modules\Geography\Models\State;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * The reusable player registry. Deliberately NOT association-scoped: identity is
 * stored once and linked to associations through player_associations, so an
 * association only ever sees the players it has claimed.
 */
class Player extends Model
{
    protected $fillable = [
        'reference_code',
        'owner_association_id',
        'created_by',
        'name',
        'slug',
        'date_of_birth',
        'gender',
        'state_id',
        'city',
        'address',
        'email',
        'phone',
        'photo_path',
        'primary_role',
        'is_public',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'primary_role' => PersonRole::class,
            'is_public' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function ownerAssociation(): BelongsTo
    {
        return $this->belongsTo(Association::class, 'owner_association_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function associations(): BelongsToMany
    {
        return $this->belongsToMany(Association::class, 'player_associations')
            ->withPivot(['source', 'is_active', 'created_by'])
            ->withTimestamps();
    }

    public function districtTeamMembers(): HasMany
    {
        return $this->hasMany(DistrictTeamMember::class);
    }

    public function stateTeamMembers(): HasMany
    {
        return $this->hasMany(StateTeamMember::class);
    }

    public function eventEntries(): HasMany
    {
        return $this->hasMany(EventEntry::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_entries')
            ->withPivot(['role', 'status', 'jersey_number', 'team_name', 'placement'])
            ->withTimestamps();
    }

    public function scopeLinkedTo(Builder $query, int $associationId): Builder
    {
        return $query->whereHas(
            'associations',
            fn (Builder $linked): Builder => $linked->whereKey($associationId)->wherePivot('is_active', true)
        );
    }

    /**
     * Everything this association may reference: its own claims, the profiles it
     * owns, and published profiles available for reuse.
     */
    public function scopeVisibleTo(Builder $query, int $associationId): Builder
    {
        return $query->where(function (Builder $scope) use ($associationId): void {
            $scope->where('is_public', true)
                ->orWhere('owner_association_id', $associationId)
                ->orWhereHas(
                    'associations',
                    fn (Builder $linked): Builder => $linked->whereKey($associationId)
                );
        });
    }

    public function scopeSearchable(Builder $query, string $term): Builder
    {
        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $term).'%';

        return $query->where(function (Builder $inner) use ($like): void {
            $inner->where('name', 'like', $like)
                ->orWhere('reference_code', 'like', $like)
                ->orWhere('city', 'like', $like);
        });
    }

    public function isLinkedTo(?int $associationId): bool
    {
        return $associationId !== null
            && $this->associations->contains(fn (Association $association): bool => $association->id === $associationId);
    }

    public function isActiveLink(?int $associationId): bool
    {
        if ($associationId === null) {
            return false;
        }

        return $this->associations
            ->first(fn (Association $association): bool => $association->id === $associationId)
            ?->pivot->is_active === true;
    }

    public function age(): ?int
    {
        return $this->date_of_birth?->age;
    }

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: '?';
    }

    public function photoUrl(): ?string
    {
        if (is_string($this->photo_path) && $this->photo_path !== '') {
            return Storage::disk('public')->url($this->photo_path);
        }

        return null;
    }

    /**
     * Contact details are admin-only unless a records officer explicitly opens them up.
     */
    public function publicProfilePath(): ?string
    {
        return $this->is_public && is_string($this->slug) && $this->slug !== ''
            ? route('players.show', $this->slug)
            : null;
    }

    public function primaryRoleLabel(): string
    {
        return $this->primary_role->label();
    }
}
