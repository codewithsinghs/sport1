<?php

namespace App\Modules\Content\Models;

use App\Modules\Content\Enums\ContentBlockType;
use App\Modules\Content\Support\CuratedImages;
use App\Modules\Content\Support\HtmlSanitizer;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * An association-owned, orderable section rendered on a public page.
 */
class ContentBlock extends Model
{
    use BelongsToAssociation;

    public const PAGES = [
        'home' => 'Homepage',
        'events' => 'Events page',
        'about' => 'About page',
        'membership' => 'Membership page',
    ];

    public const MAX_STATS = 4;

    protected $fillable = [
        'page',
        'type',
        'eyebrow',
        'title',
        'body',
        'image',
        'link_label',
        'link_url',
        'settings',
        'position',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContentBlockType::class,
            'settings' => 'array',
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value === null || trim($value) === '' || strip_tags($value) === ''
                ? null
                : app(HtmlSanitizer::class)->clean($value),
        );
    }

    public function scopeForPage(Builder $query, string $page): Builder
    {
        return $query->where('page', $page)->orderBy('position');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function bodyHtml(): string
    {
        return app(HtmlSanitizer::class)->clean($this->body ?? '');
    }

    public function plainExcerpt(int $words = 34): string
    {
        $text = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $this->body)));

        if ($text === '') {
            return '';
        }

        $parts = explode(' ', $text);

        return implode(' ', array_slice($parts, 0, $words)).(count($parts) > $words ? '…' : '');
    }

    public function imageUrl(): string
    {
        return CuratedImages::url($this->image);
    }

    public function imageName(): string
    {
        return CuratedImages::resolve($this->image);
    }

    /**
     * @return array<int, array{label:string,value:string}>
     */
    public function stats(): array
    {
        $rows = collect($this->settings['stats'] ?? [])
            ->map(function (mixed $row): ?array {
                if (! is_array($row)) {
                    return null;
                }

                $label = trim(strip_tags((string) ($row['label'] ?? '')));
                $value = trim(strip_tags((string) ($row['value'] ?? '')));

                return $label === '' || $value === '' ? null : ['label' => $label, 'value' => $value];
            })
            ->filter()
            ->take(self::MAX_STATS)
            ->values()
            ->all();

        return $rows;
    }

    public function hasLink(): bool
    {
        return $this->linkHref() !== null;
    }

    /**
     * External destinations only, so an editor can never inject a javascript: link.
     */
    public function linkHref(): ?string
    {
        $url = trim((string) $this->link_url);

        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/') || str_starts_with($url, '#')) {
            return $url;
        }

        return preg_match('#^https?://#i', $url) === 1 ? $url : null;
    }

    public function linkText(): string
    {
        return trim((string) $this->link_label) ?: 'Read more';
    }

    public function isFullBleed(): bool
    {
        return (bool) ($this->settings['full_bleed'] ?? false);
    }

    public static function pageOptions(): array
    {
        return self::PAGES;
    }
}
