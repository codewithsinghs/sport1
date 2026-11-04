<?php

namespace App\Modules\Programme\Livewire;

use App\Modules\Programme\Enums\EventEntryStatus;
use App\Modules\Programme\Models\DistrictAssociation;
use App\Modules\Programme\Models\Event;
use App\Modules\Programme\Models\EventEntry;
use App\Modules\Roster\Enums\PersonRole;
use App\Modules\Roster\Models\Player;
use App\Modules\Sport\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class EventEntryIndex extends Component
{
    use WithPagination;

    public Event $event;

    public ?int $editingId = null;

    public ?int $playerId = null;

    public string $playerSearch = '';

    public string $role = PersonRole::Player->value;

    public string $status = EventEntryStatus::Confirmed->value;

    public string $jerseyNumber = '';

    public string $teamName = '';

    public string $placement = '';

    public string $remarks = '';

    public ?int $categoryId = null;

    public ?int $districtAssociationId = null;

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->authorize('recordRoster', $event);

        $default = $event->district_association_id;
        $this->districtAssociationId = $default;
        $this->teamName = (string) ($default ? DistrictAssociation::query()->whereKey($default)->value('name') : $event->association?->name);
    }

    public function updatedPlayerSearch(): void
    {
        $this->resetPage();
    }

    public function edit(int $entryId): void
    {
        $entry = $this->findEntry($entryId);
        $this->authorize('update', $entry);

        $this->editingId = $entry->getKey();
        $this->playerId = $entry->player_id;
        $this->role = $entry->role->value;
        $this->status = $entry->status->value;
        $this->jerseyNumber = (string) $entry->jersey_number;
        $this->teamName = (string) $entry->team_name;
        $this->placement = (string) $entry->placement;
        $this->remarks = (string) $entry->remarks;
        $this->categoryId = $entry->category_id;
        $this->districtAssociationId = $entry->district_association_id;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->authorize('recordRoster', $this->event);

        $validated = $this->validate([
            'playerId' => ['required', 'integer'],
            'role' => ['required', 'in:'.implode(',', array_column(PersonRole::cases(), 'value'))],
            'status' => ['required', 'in:'.implode(',', array_column(EventEntryStatus::cases(), 'value'))],
            'jerseyNumber' => ['nullable', 'integer', 'between:1,999'],
            'teamName' => ['nullable', 'string', 'max:150'],
            'placement' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'categoryId' => ['nullable', 'integer'],
            'districtAssociationId' => ['nullable', 'integer'],
        ]);

        $player = Player::query()->visibleTo(auth()->user()->association_id)->whereKey($validated['playerId'])->first();

        if ($player === null) {
            $this->addError('playerId', 'Choose a player from the registry you can reference.');

            return;
        }

        $existing = EventEntry::query()
            ->where('event_id', $this->event->getKey())
            ->where('player_id', $player->getKey())
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($existing) {
            $this->addError('playerId', $player->name.' is already recorded for this event.');

            return;
        }

        $record = $this->editingId ? $this->findEntry($this->editingId) : new EventEntry();

        $record->fill([
            'event_id' => $this->event->getKey(),
            'player_id' => $player->getKey(),
            'district_association_id' => $validated['districtAssociationId'] ?? null,
            'category_id' => $validated['categoryId'] ?? null,
            'role' => $validated['role'],
            'status' => $validated['status'],
            'jersey_number' => $this->blankToNull($validated['jerseyNumber'] ?? null),
            'team_name' => $this->blankToNull($validated['teamName'] ?? null),
            'placement' => $this->blankToNull($validated['placement'] ?? null),
            'remarks' => $this->blankToNull($validated['remarks'] ?? null),
            'recorded_by' => auth()->id(),
            'recorded_at' => now(),
        ])->save();

        $wasEditing = $this->editingId !== null;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'Entry updated.' : $player->name.' added to the event record.');
    }

    public function delete(int $entryId): void
    {
        $entry = $this->findEntry($entryId);
        $this->authorize('delete', $entry);

        $entry->delete();
        session()->flash('status', 'Entry removed from the snapshot.');
    }

    public function closeForm(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        $entries = EventEntry::query()
            ->with(['player', 'category', 'districtAssociation'])
            ->where('event_id', $this->event->getKey())
            ->orderByRaw('placement is null, placement asc')
            ->orderBy('team_name')
            ->paginate(25);

        $candidates = Player::query()
            ->visibleTo(auth()->user()->association_id)
            ->where('is_active', true)
            ->when($this->playerSearch !== '', fn ($query) => $query->searchable($this->playerSearch))
            ->orderBy('name')
            ->paginate(8, ['id', 'name', 'reference_code', 'city', 'primary_role', 'is_public']);

        return view('programme::livewire.event-entries.index', [
            'entries' => $entries,
            'candidates' => $candidates,
            'roleOptions' => PersonRole::options(),
            'statusOptions' => EventEntryStatus::options(),
            'categories' => Category::query()->where('association_id', $this->event->association_id)->orderBy('name')->get(['id', 'name']),
            'bodies' => DistrictAssociation::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    private function findEntry(int $id): EventEntry
    {
        return EventEntry::query()->where('event_id', $this->event->getKey())->findOrFail($id);
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->playerId = null;
        $this->playerSearch = '';
        $this->role = PersonRole::Player->value;
        $this->status = EventEntryStatus::Confirmed->value;
        $this->jerseyNumber = '';
        $this->placement = '';
        $this->remarks = '';
        $this->categoryId = null;
        $this->resetErrorBag();
    }
}
