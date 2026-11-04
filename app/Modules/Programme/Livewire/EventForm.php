<?php

namespace App\Modules\Programme\Livewire;

use App\Modules\Core\Association\AssociationContext;
use App\Modules\Programme\Enums\EventLevel;
use App\Modules\Programme\Enums\EventStatus;
use App\Modules\Programme\Enums\EventType;
use App\Modules\Programme\Models\DistrictAssociation;
use App\Modules\Programme\Models\Event;
use App\Modules\Sport\Models\Sport;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class EventForm extends Component
{
    use WithFileUploads;

    public ?Event $event = null;

    public string $title = '';

    public string $slug = '';

    public bool $slugLocked = false;

    public string $eventType = EventType::Tournament->value;

    public string $level = EventLevel::State->value;

    public string $summary = '';

    public string $description = '';

    public string $startsAt = '';

    public string $endsAt = '';

    public string $venueName = '';

    public string $venueAddress = '';

    public string $mapLat = '';

    public string $mapLng = '';

    public string $heroImage = 'championship-stage';

    public $heroUpload = null;

    public bool $hasUploadedHero = false;

    public string $theme = 'ink';

    public string $accentColor = '';

    public string $registrationUrl = '';

    public string $contactEmail = '';

    public string $contactPhone = '';

    public bool $isFeatured = false;

    public ?int $districtAssociationId = null;

    public ?int $sportId = null;

    public function mount(?Event $event = null): void
    {
        $this->event = $event;

        if ($event === null) {
            $this->authorize('create', Event::class);
            $this->sportId = Sport::query()->where('is_active', true)->value('id');

            return;
        }

        $this->authorize('update', $event);

        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->slugLocked = true;
        $this->eventType = $event->event_type->value;
        $this->level = $event->level->value;
        $this->summary = (string) $event->summary;
        $this->description = (string) $event->description;
        $this->startsAt = $event->starts_at->format('Y-m-d\TH:i');
        $this->endsAt = $event->ends_at?->format('Y-m-d\TH:i') ?? '';
        $this->venueName = (string) $event->venue_name;
        $this->venueAddress = (string) $event->venue_address;
        $this->mapLat = (string) $event->map_lat;
        $this->mapLng = (string) $event->map_lng;
        $this->heroImage = $event->hero_image ?: 'championship-stage';
        $this->theme = $event->theme ?: 'ink';
        $this->accentColor = (string) $event->accent_color;
        $this->registrationUrl = (string) $event->registration_url;
        $this->contactEmail = (string) $event->contact_email;
        $this->contactPhone = (string) $event->contact_phone;
        $this->isFeatured = $event->is_featured;
        $this->districtAssociationId = $event->district_association_id;
        $this->sportId = $event->sport_id;
        $this->hasUploadedHero = $event->hasUploadedHero();
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->slugLocked) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedSlug(): void
    {
        $this->slug = Str::slug($this->slug);
        $this->slugLocked = true;
    }

    public function useCuratedHero(): void
    {
        $this->heroUpload = null;

        if ($this->event?->exists) {
            $this->authorize('update', $this->event);
            $this->event->forceFill(['hero_image_path' => null])->save();
            $this->event->refresh();
        }

        $this->hasUploadedHero = false;
    }

    public function save()
    {
        $this->authorize($this->event?->exists ? 'update' : 'create', $this->event ?? Event::class);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:200', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'eventType' => ['required', 'in:'.implode(',', array_column(EventType::cases(), 'value'))],
            'level' => ['required', 'in:'.implode(',', array_column(EventLevel::cases(), 'value'))],
            'summary' => ['nullable', 'string', 'max:280'],
            'description' => ['nullable', 'string', 'max:60000'],
            'startsAt' => ['required', 'date'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
            'venueName' => ['nullable', 'string', 'max:180'],
            'venueAddress' => ['nullable', 'string', 'max:400'],
            'mapLat' => ['nullable', 'numeric', 'between:-90,90'],
            'mapLng' => ['nullable', 'numeric', 'between:-180,180'],
            'heroImage' => ['required', 'string', 'in:'.implode(',', Event::HERO_IMAGES)],
            'heroUpload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'theme' => ['required', 'in:'.implode(',', array_keys(Event::THEMES))],
            'accentColor' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'registrationUrl' => ['nullable', 'url:http,https', 'max:255'],
            'contactEmail' => ['nullable', 'email:rfc', 'max:150'],
            'contactPhone' => ['nullable', 'string', 'max:30'],
            'districtAssociationId' => ['nullable', 'integer'],
            'sportId' => ['nullable', 'integer', 'exists:sports,id'],
        ]);

        if ($validated['districtAssociationId'] !== null) {
            $owned = DistrictAssociation::query()->whereKey($validated['districtAssociationId'])->exists();

            if (! $owned) {
                $this->addError('districtAssociationId', 'Choose a district body belonging to this association.');

                return;
            }
        }

        $duplicate = Event::query()
            ->where('slug', $validated['slug'])
            ->when($this->event?->exists, fn ($query) => $query->whereKeyNot($this->event->getKey()))
            ->exists();

        if ($duplicate) {
            $this->addError('slug', 'An event with this web address already exists.');

            return;
        }

        $record = $this->event ?? new Event();

        $heroPath = null;

        if ($this->heroUpload !== null) {
            $heroPath = $this->heroUpload->store('events/heroes', 'public');
        }

        $record->fill([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'event_type' => $validated['eventType'],
            'level' => $validated['level'],
            'summary' => $this->nullify($validated['summary'] ?? null),
            'description' => $this->nullify($validated['description'] ?? null),
            'starts_at' => $validated['startsAt'],
            'ends_at' => $this->nullify($validated['endsAt'] ?? null),
            'venue_name' => $this->nullify($validated['venueName'] ?? null),
            'venue_address' => $this->nullify($validated['venueAddress'] ?? null),
            'map_lat' => $this->nullify($validated['mapLat'] ?? null),
            'map_lng' => $this->nullify($validated['mapLng'] ?? null),
            'hero_image' => $validated['heroImage'],
            'theme' => $validated['theme'],
            'accent_color' => $this->nullify($validated['accentColor'] ?? null),
            'registration_url' => $this->nullify($validated['registrationUrl'] ?? null),
            'contact_email' => $this->nullify($validated['contactEmail'] ?? null),
            'contact_phone' => $this->nullify($validated['contactPhone'] ?? null),
            'is_featured' => $this->isFeatured,
            'district_association_id' => $validated['districtAssociationId'] ?? null,
            'sport_id' => $validated['sportId'] ?? null,
        ]);

        if ($heroPath !== null) {
            $record->hero_image_path = $heroPath;
        }

        if (! $record->exists) {
            $record->status = EventStatus::Draft;
            $record->created_by = auth()->id();
        }

        $wasNew = ! $record->exists;
        $record->save();
        $this->event = $record;
        $this->hasUploadedHero = $record->hasUploadedHero();
        $this->heroUpload = null;

        session()->flash('status', $wasNew ? 'Event created.' : 'Event saved.');

        return $this->redirectRoute('admin.events.edit', $record, navigate: true);
    }

    public function render()
    {
        $association = app(AssociationContext::class)->require();

        return view('programme::livewire.events.form', [
            'bodyOptions' => DistrictAssociation::query()->orderBy('name')->get(['id', 'name']),
            'sportOptions' => $association->enabledSports()->get(['sports.id', 'sports.name']),
            'typeOptions' => EventType::options(),
            'levelOptions' => EventLevel::options(),
            'themeOptions' => Event::THEMES,
            'heroOptions' => Event::HERO_IMAGES,
            'status' => $this->event?->status,
            'isEditing' => $this->event?->exists ?? false,
        ]);
    }

    private function nullify(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
