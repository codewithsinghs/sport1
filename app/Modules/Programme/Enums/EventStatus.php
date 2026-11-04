<?php

namespace App\Modules\Programme\Enums;

enum EventStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
    case Published = 'published';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Submitted => 'Submitted for review',
            self::Approved => 'Approved',
            self::Published => 'Published',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeTone(): string
    {
        return match ($this) {
            self::Draft => 'badge-slate',
            self::Submitted => 'badge-amber',
            self::Approved => 'badge-brand',
            self::Published => 'badge-green',
            self::Completed => 'badge-green',
            self::Cancelled => 'badge-rose',
        };
    }

    public function isPubliclyVisible(): bool
    {
        return in_array($this, [self::Published, self::Completed], true);
    }

    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::Submitted, self::Approved, self::Published], true);
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (EventStatus $status): array => [$status->value => $status->label()])
            ->all();
    }
}
