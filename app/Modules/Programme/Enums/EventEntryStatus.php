<?php

namespace App\Modules\Programme\Enums;

enum EventEntryStatus: string
{
    case Awaiting = 'awaiting';
    case Confirmed = 'confirmed';
    case Participated = 'participated';
    case Withdrew = 'withdrew';
    case Disqualified = 'disqualified';

    public function label(): string
    {
        return match ($this) {
            self::Awaiting => 'Awaiting confirmation',
            self::Confirmed => 'Confirmed entry',
            self::Participated => 'Participated',
            self::Withdrew => 'Withdrew',
            self::Disqualified => 'Disqualified',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Awaiting => 'badge-amber',
            self::Confirmed => 'badge-brand',
            self::Participated => 'badge-green',
            self::Withdrew => 'badge-slate',
            self::Disqualified => 'badge-rose',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (EventEntryStatus $status): array => [
                $status->value => $status->label(),
            ])
            ->all();
    }
}
