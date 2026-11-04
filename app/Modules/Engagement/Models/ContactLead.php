<?php

namespace App\Modules\Engagement\Models;

use App\Models\User;
use App\Modules\Core\Models\Concerns\BelongsToAssociation;
use App\Modules\Engagement\Enums\LeadStatus;
use App\Modules\Geography\Models\District;
use App\Modules\Programme\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactLead extends Model
{
    use BelongsToAssociation;

    public const TOPICS = [
        'general' => 'General enquiry',
        'membership' => 'Membership & registration',
        'tournament' => 'Tournaments & fixtures',
        'selection' => 'Selection & trials',
        'district' => 'District association matters',
        'media' => 'Media & photography',
        'result' => 'Results & rankings',
    ];

    protected $fillable = [
        'event_id',
        'district_id',
        'name',
        'email',
        'phone',
        'topic',
        'message',
        'status',
        'source_page',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => LeadStatus::class,
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', LeadStatus::New);
    }

    public function topicLabel(): string
    {
        return self::TOPICS[$this->topic] ?? self::TOPICS['general'];
    }

    public function contextLabel(): string
    {
        if ($this->event !== null) {
            return 'About '.$this->event->title;
        }

        if ($this->district !== null) {
            return 'About '.$this->district->name;
        }

        return $this->topicLabel();
    }

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('') ?: '?';
    }
}
