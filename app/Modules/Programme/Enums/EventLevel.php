<?php

namespace App\Modules\Programme\Enums;

enum EventLevel: string
{
    case District = 'district';
    case State = 'state';
    case National = 'national';
    case International = 'international';

    public function label(): string
    {
        return match ($this) {
            self::District => 'District',
            self::State => 'State',
            self::National => 'National',
            self::International => 'International',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (EventLevel $level): array => [$level->value => $level->label()])
            ->all();
    }
}
