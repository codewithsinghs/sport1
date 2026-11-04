<?php

namespace App\Modules\Association\Models;

use App\Models\User;
use App\Modules\Association\Services\AssociationSettings;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use App\Modules\Membership\Models\MembershipCategory;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Association extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'slug',
        'state_id',
        'email',
        'phone',
        'website',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function domains(): HasMany
    {
        return $this->hasMany(AssociationDomain::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(AssociationSetting::class);
    }

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(Sport::class, 'association_sports')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    public function enabledSports(): BelongsToMany
    {
        return $this->sports()->wherePivot('is_active', true);
    }

    public function disciplines(): HasMany
    {
        return $this->hasMany(Discipline::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function membershipCategories(): HasMany
    {
        return $this->hasMany(MembershipCategory::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'state_id', 'state_id');
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return app(AssociationSettings::class)->get($this, $key, $default);
    }

    public function primaryColor(): string
    {
        return $this->safeColor(
            $this->setting(AssociationSettings::PRIMARY_COLOR),
            '#0f766e'
        );
    }

    public function secondaryColor(): string
    {
        return $this->safeColor(
            $this->setting(AssociationSettings::SECONDARY_COLOR),
            '#f59e0b'
        );
    }

    public function primaryForeground(): string
    {
        return $this->foregroundFor($this->primaryColor());
    }

    public function secondaryForeground(): string
    {
        return $this->foregroundFor($this->secondaryColor());
    }

    public function logoUrl(): ?string
    {
        $path = $this->setting(AssociationSettings::LOGO_PATH);

        if (! is_string($path) || $path === '') {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    public function initials(): string
    {
        $source = $this->short_name ?: $this->name;

        return collect(preg_split('/\s+/', trim($source)))
            ->filter()
            ->take(2)
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }

    private function safeColor(mixed $value, string $fallback): string
    {
        return is_string($value) && preg_match('/^#[0-9a-fA-F]{6}$/', $value) === 1
            ? $value
            : $fallback;
    }

    private function foregroundFor(string $hex): string
    {
        [$r, $g, $b] = array_map(
            fn (int $channel) => $channel / 255,
            sscanf($hex, '#%02x%02x%02x')
        );

        $linear = fn (float $channel) => $channel <= 0.03928
            ? $channel / 12.92
            : (($channel + 0.055) / 1.055) ** 2.4;

        $luminance = 0.2126 * $linear($r) + 0.7152 * $linear($g) + 0.0722 * $linear($b);

        $contrastWithWhite = 1.05 / ($luminance + 0.05);
        $contrastWithInk = ($luminance + 0.05) / 0.056;

        return $contrastWithWhite >= $contrastWithInk ? '#ffffff' : '#0a1220';
    }
}
