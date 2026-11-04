<?php

namespace App\Modules\Programme\Enums;

enum EventType: string
{
    case Championship = 'championship';
    case Tournament = 'tournament';
    case Camp = 'camp';
    case Trial = 'trial';
    case Meeting = 'meeting';
    case Community = 'community';

    public function label(): string
    {
        return match ($this) {
            self::Championship => 'Championship',
            self::Tournament => 'Tournament',
            self::Camp => 'Coaching camp',
            self::Trial => 'Selection trial',
            self::Meeting => 'Meeting',
            self::Community => 'Community activity',
        };
    }

    public function pluralLabel(): string
    {
        return match ($this) {
            self::Championship => 'Championships',
            self::Tournament => 'Tournaments',
            self::Camp => 'Coaching camps',
            self::Trial => 'Selection trials',
            self::Meeting => 'Meetings',
            self::Community => 'Community activities',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (EventType $type): array => [$type->value => $type->label()])
            ->all();
    }
}
