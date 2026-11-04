<?php

namespace App\Modules\Roster\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Other = 'other';
    case Undisclosed = 'undisclosed';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::Other => 'Other',
            self::Undisclosed => 'Not recorded',
        };
    }

    public static function fromCategoryGender(string $gender): self
    {
        return match ($gender) {
            'male' => self::Male,
            'female' => self::Female,
            'other' => self::Other,
            default => self::Undisclosed,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (Gender $gender): array => [$gender->value => $gender->label()])
            ->all();
    }
}
