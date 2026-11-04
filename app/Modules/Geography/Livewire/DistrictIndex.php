<?php

namespace App\Modules\Geography\Livewire;

use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DistrictIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $code = '';

    public bool $isActive = true;

    public ?int $stateId = null;

    public string $stateName = '';

    public function mount(): void
    {
        $this->authorize('viewAny', District::class);

        $association = app(AssociationContext::class)->require();
        $this->stateId = $association->state_id;

        if ($this->stateId !== null) {
            $this->stateName = (string) State::query()->whereKey($this->stateId)->value('name');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', District::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $districtId): void
    {
        $district = District::query()->findOrFail($districtId);
        $this->authorize('update', $district);

        $this->editingId = $district->getKey();
        $this->name = $district->name;
        $this->code = $district->code ?? '';
        $this->isActive = $district->is_active;
        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $district = $this->editingId
            ? District::query()->findOrFail($this->editingId)
            : new District();

        $this->authorize($district->exists ? 'update' : 'create', $district->exists ? $district : District::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:20'],
            'isActive' => ['boolean'],
        ]);

        if ($this->stateId === null) {
            $this->addError('name', 'No state is configured for this association.');

            return;
        }

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            $this->addError('name', 'Enter a district name containing letters or numbers.');

            return;
        }

        $duplicate = District::query()
            ->where('state_id', $this->stateId)
            ->where('slug', $slug)
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('name', 'A district with this name already exists in the state.');

            return;
        }

        $wasEditing = $district->exists;

        $district->fill([
            'state_id' => $this->stateId,
            'name' => $validated['name'],
            'slug' => $slug,
            'code' => $validated['code'] ?: null,
            'is_active' => $this->isActive,
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'District updated.' : 'District created.');
    }

    public function delete(int $districtId): void
    {
        $district = District::query()->findOrFail($districtId);
        $this->authorize('delete', $district);

        $district->delete();

        $this->resetPage();
        session()->flash('status', 'District removed.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $districts = District::query()
            ->when($this->stateId !== null, fn ($query) => $query->where('state_id', $this->stateId))
            ->when($this->stateId === null, fn ($query) => $query->whereRaw('0 = 1'))
            ->when($this->search !== '', function ($query): void {
                $term = '%'.$this->search.'%';
                $query->where(function ($query) use ($term): void {
                    $query->where('name', 'like', $term)
                        ->orWhere('code', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate(15);

        return view('geography::livewire.districts.index', [
            'districts' => $districts,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->code = '';
        $this->isActive = true;
        $this->resetErrorBag();
    }
}
