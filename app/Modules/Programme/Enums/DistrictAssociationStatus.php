<?php

namespace App\Modules\Programme\Enums;

enum DistrictAssociationStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Suspended = 'suspended';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending onboarding',
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Archived => 'Archived',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Pending => 'badge-amber',
            self::Active => 'badge-green',
            self::Suspended => 'badge-rose',
            self::Archived => 'badge-slate',
        };
    }

    public function isPublic(): bool
    {
        return $this === self::Active;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])
            ->all();
    }
}
