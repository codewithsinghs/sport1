<?php

namespace App\Modules\Content\Enums;

enum ContentBlockType: string
{
    case RichText = 'rich_text';
    case ImageText = 'image_text';
    case Stats = 'stats';
    case Cta = 'cta';

    public function label(): string
    {
        return match ($this) {
            self::RichText => 'Editorial copy',
            self::ImageText => 'Image and copy',
            self::Stats => 'Statistics strip',
            self::Cta => 'Call to action',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::RichText => 'A heading and formatted copy with an optional link.',
            self::ImageText => 'A photograph paired with formatted copy and an optional link.',
            self::Stats => 'Up to four label and value figures on an ink panel.',
            self::Cta => 'A dark band with a short statement and one primary link.',
        };
    }

    public function usesBody(): bool
    {
        return in_array($this, [self::RichText, self::ImageText, self::Cta], true);
    }

    public function usesImage(): bool
    {
        return $this === self::ImageText;
    }

    public function usesStats(): bool
    {
        return $this === self::Stats;
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (ContentBlockType $type): array => [$type->value => $type->label()])
            ->all();
    }
}
