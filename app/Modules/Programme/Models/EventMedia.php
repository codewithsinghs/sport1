<?php

namespace App\Modules\Programme\Models;

use App\Modules\Content\Support\CuratedImages;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Programme\Enums\EventEntryStatus;
use App\Modules\Roster\Models\Player;
use App\Modules\Sport\Models\Category;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EventMedia extends Model
{
    use BelongsToAssociation;

    protected $fillable = [
        'event_id',
        'caption',
        'alt',
        'file_path',
        'width',
        'height',
        'credit',
        'is_public',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'is_public' => 'boolean',
            'position' => 'integer',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function url(): string
    {
        if (is_string($this->file_path) && $this->file_path !== '') {
            return Storage::disk('public')->url($this->file_path);
        }

        return CuratedImages::url(null);
    }

    public function aspectRatio(): float
    {
        if ($this->width && $this->height && $this->height > 0) {
            return round($this->width / $this->height, 3);
        }

        return 1.5;
    }

    public function labelOrFallback(): string
    {
        return $this->alt ?: ($this->caption ?: 'Targetball photography');
    }

    protected function caption(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): ?string => $value === null || trim($value) === ''
                ? null
                : strip_tags($value),
        );
    }
}
