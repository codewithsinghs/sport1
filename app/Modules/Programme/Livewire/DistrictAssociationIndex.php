<?php

namespace App\Modules\Programme\Livewire;

use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use App\Modules\Programme\Enums\DistrictAssociationStatus;
use App\Modules\Programme\Models\DistrictAssociation;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DistrictAssociationIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public ?int $districtId = null;

    public string $contactName = '';

    public string $contactDesignation = '';

    public string $email = '';

    public string $phone = '';

    public string $address = '';

    public string $status = DistrictAssociationStatus::Pending->value;

    public string $playersRegistered = '';

    public string $coachesRegistered = '';

    public string $lastReviewedOn = '';

    public string $supervisionNotes = '';

    public function mount(): void
    {
        $this->authorize('viewAny', DistrictAssociation::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', DistrictAssociation::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $record = $this->findRecord($id);
        $this->authorize('update', $record);

        $this->editingId = $record->getKey();
        $this->name = $record->name;
        $this->districtId = $record->district_id;
        $this->contactName = (string) $record->contact_name;
        $this->contactDesignation = (string) $record->contact_designation;
        $this->email = (string) $record->email;
        $this->phone = (string) $record->phone;
        $this->address = (string) $record->address;
        $this->status = $record->status->value;
        $this->playersRegistered = (string) $record->players_registered;
        $this->coachesRegistered = (string) $record->coaches_registered;
        $this->lastReviewedOn = $record->last_reviewed_on?->format('Y-m-d') ?? '';
        $this->supervisionNotes = (string) $record->supervision_notes;

        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $record = $this->editingId ? $this->findRecord($this->editingId) : new DistrictAssociation();

        $this->authorize($record->exists ? 'update' : 'create', $record);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:180'],
            'districtId' => ['required', 'integer', 'exists:districts,id'],
            'contactName' => ['nullable', 'string', 'max:120'],
            'contactDesignation' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email:rfc', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:'.implode(',', array_column(DistrictAssociationStatus::cases(), 'value'))],
            'playersRegistered' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'coachesRegistered' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'lastReviewedOn' => ['nullable', 'date'],
            'supervisionNotes' => ['nullable', 'string', 'max:4000'],
        ]);

        $district = $this->districtOptions()->firstWhere('id', $validated['districtId']);

        if ($district === null) {
            $this->addError('districtId', 'Choose a district inside this association’s state.');

            return;
        }

        $duplicate = DistrictAssociation::query()
            ->where('district_id', $validated['districtId'])
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('districtId', 'This district already has a body on record.');

            return;
        }

        $wasEditing = $record->exists;

        $record->fill([
            'district_id' => $validated['districtId'],
            'name' => $validated['name'],
            'contact_name' => $this->blankToNull($validated['contactName'] ?? null),
            'contact_designation' => $this->blankToNull($validated['contactDesignation'] ?? null),
            'email' => $this->blankToNull($validated['email'] ?? null),
            'phone' => $this->blankToNull($validated['phone'] ?? null),
            'address' => $this->blankToNull($validated['address'] ?? null),
            'status' => $validated['status'],
            'players_registered' => $this->blankToNull($validated['playersRegistered'] ?? null) ?? 0,
            'coaches_registered' => $this->blankToNull($validated['coachesRegistered'] ?? null) ?? 0,
            'last_reviewed_on' => $this->blankToNull($validated['lastReviewedOn'] ?? null),
            'supervision_notes' => $this->blankToNull($validated['supervisionNotes'] ?? null),
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'District association updated.' : 'District association recorded.');
    }

    public function delete(int $id): void
    {
        $record = $this->findRecord($id);
        $this->authorize('delete', $record);

        if ($record->events()->exists()) {
            session()->flash('error', 'Reassign the events for this body before removing it.');
            $this->showForm = false;

            return;
        }

        $record->delete();
        $this->resetPage();
        session()->flash('status', 'District association removed.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $association = app(AssociationContext::class)->require();

        $bodies = DistrictAssociation::query()
            ->with('district')
            ->withCount(['events'])
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';
                $query->where(function ($inner) use ($term): void {
                    $inner->where('name', 'like', $term)
                        ->orWhere('contact_name', 'like', $term);
                });
            })
            ->when($this->statusFilter !== '', fn ($query) => $query->where('status', $this->statusFilter))
            ->orderBy('name')
            ->paginate(15);

        $coveredDistrictIds = DistrictAssociation::query()
            ->pluck('district_id')
            ->all();

        $districts = $this->districtOptions();
        $covered = $districts->whereIn('id', $coveredDistrictIds)->count();
        $unclaimed = $districts->count() - $covered;

        return view('programme::livewire.district-associations.index', [
            'bodies' => $bodies,
            'districts' => $districts,
            'statusOptions' => DistrictAssociationStatus::options(),
            'stats' => $this->stats($association),
            'coverage' => ['covered' => $covered, 'unclaimed' => $unclaimed],
        ]);
    }

    /**
     * @return Collection<int, District>
     */
    private function districtOptions(): Collection
    {
        $association = app(AssociationContext::class)->require();

        return District::query()
            ->where('state_id', $association->state_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * @return array<int, array{label:string,value:string|int}>
     */
    private function stats(Association $association): array
    {
        $active = DistrictAssociation::query()->where('status', DistrictAssociationStatus::Active->value)->count();
        $pending = DistrictAssociation::query()->where('status', DistrictAssociationStatus::Pending->value)->count();
        $players = (int) DistrictAssociation::query()->sum('players_registered');
        $stale = DistrictAssociation::query()
            ->where(function ($query): void {
                $query->whereNull('last_reviewed_on')
                    ->orWhere('last_reviewed_on', '<', now()->subDays(180)->toDateString());
            })
            ->count();

        return [
            ['label' => 'Bodies on record', 'value' => DistrictAssociation::query()->count(), 'route' => null],
            ['label' => 'Affiliated', 'value' => $active, 'route' => null],
            ['label' => 'Pending review', 'value' => $pending, 'route' => null],
            ['label' => 'Players reported', 'value' => $players, 'route' => null],
            ['label' => 'Reviews overdue', 'value' => $stale, 'route' => null],
        ];
    }

    private function findRecord(int $id): DistrictAssociation
    {
        return DistrictAssociation::query()->findOrFail($id);
    }

    private function blankToNull(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->districtId = null;
        $this->contactName = '';
        $this->contactDesignation = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->status = DistrictAssociationStatus::Pending->value;
        $this->playersRegistered = '';
        $this->coachesRegistered = '';
        $this->lastReviewedOn = '';
        $this->supervisionNotes = '';
        $this->resetErrorBag();
    }
}
