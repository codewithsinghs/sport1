<?php

namespace App\Modules\Content\Support;

class CuratedImages
{
    public const NAMES = [
        'hero-stadium',
        'championship-stage',
        'championship-team',
        'championship-lineup',
        'ceremony',
        'india-champions',
        'india-women',
        'officials',
        'speaker',
        'gallery-01',
        'gallery-02',
        'gallery-03',
        'gallery-04',
        'gallery-05',
        'gallery-06',
        'gallery-08',
        'gallery-10',
        'gallery-11',
        'gallery-12',
    ];

    public static function options(): array
    {
        return array_combine(self::NAMES, array_map(
            fn (string $name): string => ucwords(str_replace('-', ' ', $name)),
            self::NAMES
        ));
    }

    /**
     * Falls back so a stale database value can never render a broken image.
     */
    public static function resolve(mixed $name): string
    {
        return is_string($name) && in_array($name, self::NAMES, true)
            ? $name
            : 'championship-stage';
    }

    public static function url(?string $name): string
    {
        return asset('images/site/'.self::resolve($name).'.webp');
    }
}
