<?php

namespace App\Modules\Programme\Models;

use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Geography\Models\District;
use App\Modules\Programme\Enums\DistrictAssociationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistrictAssociation extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'district_id',
        'name',
        'contact_name',
        'contact_designation',
        'email',
        'phone',
        'address',
        'status',
        'players_registered',
        'coaches_registered',
        'last_reviewed_on',
        'supervision_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => DistrictAssociationStatus::class,
            'players_registered' => 'integer',
            'coaches_registered' => 'integer',
            'last_reviewed_on' => 'date',
        ];
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(\App\Modules\Roster\Models\DistrictTeamMember::class);
    }

    public function activeSquadCount(): int
    {
        return $this->teamMembers()->current()->count();
    }

    public function isPublic(): bool
    {
        return $this->status->isPublic();
    }

    public function contactLine(): ?string
    {
        return collect([$this->contact_name, $this->contact_designation])
            ->filter()
            ->implode(' · ') ?: null;
    }
}
