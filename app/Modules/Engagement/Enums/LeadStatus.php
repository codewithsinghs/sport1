<?php

namespace App\Modules\Engagement\Enums;

enum LeadStatus: string
{
    case New = 'new';
    case Read = 'read';
    case Responded = 'responded';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Read => 'Read',
            self::Responded => 'Responded',
            self::Archived => 'Archived',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::New => 'badge-amber',
            self::Read => 'badge-brand',
            self::Responded => 'badge-green',
            self::Archived => 'badge-slate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (LeadStatus $status): array => [$status->value => $status->label()])
            ->all();
    }
}
