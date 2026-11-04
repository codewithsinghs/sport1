<?php

namespace App\Modules\Roster\Enums;

enum PersonRole: string
{
    case Player = 'player';
    case Captain = 'captain';
    case ViceCaptain = 'vice_captain';
    case Coach = 'coach';
    case AssistantCoach = 'assistant_coach';
    case Manager = 'manager';
    case Selector = 'selector';
    case Official = 'official';
    case Medic = 'medic';

    public function label(): string
    {
        return match ($this) {
            self::Player => 'Player',
            self::Captain => 'Captain',
            self::ViceCaptain => 'Vice captain',
            self::Coach => 'Coach',
            self::AssistantCoach => 'Assistant coach',
            self::Manager => 'Manager',
            self::Selector => 'Selector',
            self::Official => 'Technical official',
            self::Medic => 'Medic',
        };
    }

    public function isCompetitor(): bool
    {
        return in_array($this, [self::Player, self::Captain, self::ViceCaptain], true);
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (PersonRole $role): array => [$role->value => $role->label()])
            ->all();
    }
}
