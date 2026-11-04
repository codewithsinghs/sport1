<?php

namespace App\Modules\Sport\Livewire;

use App\Modules\Association\Models\AssociationSport;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Sport\Models\Sport;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SportIndex extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $description = '';

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('viewAny', Sport::class);
    }

    public function create(): void
    {
        $this->authorize('create', Sport::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $sportId): void
    {
        $sport = Sport::query()->findOrFail($sportId);
        $this->authorize('update', $sport);

        $this->editingId = $sport->getKey();
        $this->name = $sport->name;
        $this->description = $sport->description ?? '';
        $this->isActive = $sport->is_active;
        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $sport = $this->editingId
            ? Sport::query()->findOrFail($this->editingId)
            : new Sport();

        $this->authorize($sport->exists ? 'update' : 'create', $sport->exists ? $sport : Sport::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'isActive' => ['boolean'],
        ]);

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            $this->addError('name', 'Enter a sport name containing letters or numbers.');

            return;
        }

        $duplicate = Sport::query()
            ->where('slug', $slug)
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('name', 'A sport with this name already exists.');

            return;
        }

        $wasCreating = ! $sport->exists;

        $sport->fill([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?: null,
            'is_active' => $this->isActive,
        ])->save();

        if ($wasCreating) {
            AssociationSport::query()->updateOrCreate(
                [
                    'association_id' => app(AssociationContext::class)->require()->getKey(),
                    'sport_id' => $sport->getKey(),
                ],
                ['is_active' => true]
            );
        }

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasCreating ? 'Sport created and enabled.' : 'Sport updated.');
    }

    public function toggle(int $sportId): void
    {
        $this->authorize('toggleForAssociation', Sport::class);

        $sport = Sport::query()->findOrFail($sportId);

        if (! $sport->is_active) {
            $this->addError('toggle', 'Activate this sport in the master list before enabling it for the association.');

            return;
        }

        $association = app(AssociationContext::class)->require();

        $link = AssociationSport::query()->firstOrNew([
            'association_id' => $association->getKey(),
            'sport_id' => $sport->getKey(),
        ]);

        $link->is_active = $link->exists ? ! $link->is_active : true;
        $link->save();

        session()->flash(
            'status',
            $link->is_active
                ? "{$sport->name} enabled for {$association->short_name}."
                : "{$sport->name} disabled for {$association->short_name}."
        );
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $association = app(AssociationContext::class)->require();

        $enabled = AssociationSport::query()
            ->where('association_id', $association->getKey())
            ->pluck('is_active', 'sport_id');

        return view('sport::livewire.sports.index', [
            'sports' => Sport::query()->orderBy('name')->get(),
            'enabled' => $enabled,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->description = '';
        $this->isActive = true;
        $this->resetErrorBag();
    }
}
