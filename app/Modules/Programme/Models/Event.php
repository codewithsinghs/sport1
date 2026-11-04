<?php

namespace App\Modules\Programme\Models;

use App\Modules\Association\Models\Association;
use App\Modules\Content\Support\CuratedImages;
use App\Modules\Content\Support\HtmlSanitizer;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Programme\Enums\EventEntryStatus;
use App\Modules\Programme\Enums\EventLevel;
use App\Modules\Programme\Enums\EventStatus;
use App\Modules\Programme\Enums\EventType;
use App\Modules\Sport\Models\Sport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use BelongsToAssociation;

    public const HERO_IMAGES = CuratedImages::NAMES;

    public const THEMES = [
        'ink' => 'Ink — dark editorial',
        'cream' => 'Cream — heritage light',
        'brand' => 'Association brand — gradient',
    ];

    protected $fillable = [
        'district_association_id',
        'sport_id',
        'created_by',
        'approved_by',
        'title',
        'slug',
        'event_type',
        'level',
        'status',
        'summary',
        'description',
        'starts_at',
        'ends_at',
        'venue_name',
        'venue_address',
        'map_lat',
        'map_lng',
        'hero_image',
        'hero_image_path',
        'theme',
        'accent_color',
        'registration_url',
        'contact_email',
        'contact_phone',
        'is_featured',
        'published_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'event_type' => EventType::class,
            'level' => EventLevel::class,
            'status' => EventStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'map_lat' => 'float',
            'map_lng' => 'float',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value === null || trim($value) === ''
                ? null
                : app(HtmlSanitizer::class)->clean($value),
        );
    }

    public function districtAssociation(): BelongsTo
    {
        return $this->belongsTo(DistrictAssociation::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(EventEntry::class)->orderBy('placement');
    }

    public function media(): HasMany
    {
        return $this->hasMany(EventMedia::class)->orderBy('position');
    }

    public function publicMedia(): HasMany
    {
        return $this->media()->where('is_public', true);
    }

    public function participatingTeamNames(): Collection
    {
        return $this->entries
            ->where('status', EventEntryStatus::Participated)
            ->pluck('team_name')
            ->filter()
            ->unique()
            ->values();
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                EventStatus::Published->value,
                EventStatus::Completed->value,
            ])
            ->whereNotNull('published_at');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now()->startOfDay());
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where('starts_at', '<', now()->startOfDay());
    }

    public function isPubliclyVisible(): bool
    {
        return $this->status->isPubliclyVisible() && $this->published_at !== null;
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at->greaterThanOrEqualTo(now()->startOfDay());
    }

    public function descriptionHtml(): string
    {
        return app(HtmlSanitizer::class)->clean($this->description);
    }

    public function heroImageUrl(): ?string
    {
        if (is_string($this->hero_image_path) && $this->hero_image_path !== '') {
            return Storage::disk('public')->url($this->hero_image_path);
        }

        $name = CuratedImages::resolve($this->hero_image);

        return asset('images/site/'.$name.'.webp');
    }

    public function hasUploadedHero(): bool
    {
        return is_string($this->hero_image_path) && $this->hero_image_path !== '';
    }

    public function accentColor(): ?string
    {
        return is_string($this->accent_color) && preg_match('/^#[0-9a-fA-F]{6}$/', $this->accent_color) === 1
            ? $this->accent_color
            : null;
    }

    public function dateRangeLabel(): string
    {
        $start = $this->starts_at->copy();

        if ($this->ends_at === null || $this->ends_at->isSameDay($start)) {
            return $start->format('j F Y');
        }

        if ($start->isSameMonth($this->ends_at)) {
            return $start->format('j').'–'.$this->ends_at->format('j F Y');
        }

        return $start->format('j M').' – '.$this->ends_at->format('j M Y');
    }

    public function shortDateLabel(): string
    {
        return $this->starts_at->format('j M Y');
    }

    public function timeRangeLabel(): ?string
    {
        if ($this->starts_at->format('H:i') === '00:00' && $this->ends_at === null) {
            return null;
        }

        $label = $this->starts_at->format('g:i A');

        if ($this->ends_at !== null) {
            $label .= ' – '.$this->ends_at->format('g:i A');
        }

        return $label;
    }

    public function mapEmbedUrl(): ?string
    {
        if ($this->map_lat === null || $this->map_lng === null) {
            return null;
        }

        $delta = 0.012;
        $bbox = implode(',', [
            $this->map_lng - $delta,
            $this->map_lat - $delta,
            $this->map_lng + $delta,
            $this->map_lat + $delta,
        ]);

        return 'https://www.openstreetmap.org/export/embed.html?bbox='
            .rawurlencode($bbox)
            .'&layer=mapnik&marker='
            .rawurlencode($this->map_lat.','.$this->map_lng);
    }

    public function mapLink(): ?string
    {
        if ($this->map_lat === null || $this->map_lng === null) {
            return null;
        }

        return 'https://www.google.com/maps/search/?api=1&query='
            .rawurlencode($this->map_lat.','.$this->map_lng);
    }

    public function hostName(): ?string
    {
        return $this->districtAssociation?->name;
    }

    public function structuredData(): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'SportsEvent',
            'name' => $this->title,
            'description' => $this->summary,
            'startDate' => $this->starts_at->toIso8601String(),
            'endDate' => $this->ends_at?->toIso8601String(),
            'eventStatus' => 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'url' => route('events.show', $this->slug),
            'image' => $this->heroImageUrl(),
            'location' => $this->venue_name ? array_filter([
                '@type' => 'Place',
                'name' => $this->venue_name,
                'address' => $this->venue_address,
                'geo' => $this->map_lat !== null && $this->map_lng !== null ? [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $this->map_lat,
                    'longitude' => $this->map_lng,
                ] : null,
            ]) : null,
            'organizer' => [
                '@type' => 'Organization',
                'name' => $this->association->name,
                'url' => url('/'),
            ],
        ], fn ($value): bool => $value !== null && $value !== []);
    }
}
