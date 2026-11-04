<?php

namespace App\Modules\Sport\Livewire;

use App\Modules\Core\Association\AssociationContext;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class DisciplineIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $sportId = null;

    public string $name = '';

    public string $description = '';

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('viewAny', Discipline::class);

        $this->sportId = $this->availableSports()->first()?->getKey();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', Discipline::class);

        $this->resetForm();
        $this->sportId = $this->availableSports()->first()?->getKey();
        $this->showForm = true;
    }

    public function edit(int $disciplineId): void
    {
        $discipline = Discipline::query()->findOrFail($disciplineId);
        $this->authorize('update', $discipline);

        $this->editingId = $discipline->getKey();
        $this->sportId = $discipline->sport_id;
        $this->name = $discipline->name;
        $this->description = $discipline->description ?? '';
        $this->isActive = $discipline->is_active;
        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $discipline = $this->editingId
            ? Discipline::query()->findOrFail($this->editingId)
            : new Discipline();

        $this->authorize($discipline->exists ? 'update' : 'create', $discipline->exists ? $discipline : Discipline::class);

        $validated = $this->validate([
            'sportId' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'isActive' => ['boolean'],
        ]);

        $sport = $this->availableSports()->firstWhere('id', $this->sportId);

        if ($sport === null) {
            $this->addError('sportId', 'Select a sport that is enabled for this association.');

            return;
        }

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            $this->addError('name', 'Enter a discipline name containing letters or numbers.');

            return;
        }

        $duplicate = Discipline::query()
            ->where('sport_id', $sport->getKey())
            ->where('slug', $slug)
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('name', 'A discipline with this name already exists for the selected sport.');

            return;
        }

        $wasEditing = $discipline->exists;

        $discipline->fill([
            'sport_id' => $sport->getKey(),
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?: null,
            'is_active' => $this->isActive,
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'Discipline updated.' : 'Discipline created.');
    }

    public function delete(int $disciplineId): void
    {
        $discipline = Discipline::query()->findOrFail($disciplineId);
        $this->authorize('delete', $discipline);

        $discipline->delete();

        $this->resetPage();
        session()->flash('status', 'Discipline removed.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $disciplines = Discipline::query()
            ->with('sport')
            ->withCount('categories')
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(12);

        return view('sport::livewire.disciplines.index', [
            'disciplines' => $disciplines,
            'sports' => $this->availableSports(),
        ]);
    }

    private function availableSports(): Collection
    {
        return app(AssociationContext::class)
            ->require()
            ->enabledSports()
            ->where('sports.is_active', true)
            ->orderBy('sports.name')
            ->get();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->isActive = true;
        $this->sportId = null;
        $this->resetErrorBag();
    }
}
