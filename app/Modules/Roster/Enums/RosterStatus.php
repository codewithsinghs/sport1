<?php

namespace App\Modules\Roster\Enums;

enum RosterStatus: string
{
    case Trialist = 'trialist';
    case Active = 'active';
    case Injured = 'injured';
    case Inactive = 'inactive';
    case Released = 'released';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Trialist => 'Trialist',
            self::Active => 'Active',
            self::Injured => 'Injured',
            self::Inactive => 'Inactive',
            self::Released => 'Released',
            self::Archived => 'Archived',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Trialist => 'badge-amber',
            self::Active => 'badge-green',
            self::Injured => 'badge-rose',
            self::Inactive => 'badge-slate',
            self::Released => 'badge-slate',
            self::Archived => 'badge-slate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (RosterStatus $status): array => [$status->value => $status->label()])
            ->all();
    }
}
