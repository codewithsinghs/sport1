<?php

namespace App\Modules\Sport\Livewire;

use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class CategoryIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $disciplineFilter = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $disciplineId = null;

    public string $name = '';

    public string $gender = 'any';

    public string $minAge = '';

    public string $maxAge = '';

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('viewAny', Category::class);

        $this->disciplineId = $this->disciplines()->first()?->getKey();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedDisciplineFilter(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', Category::class);

        $this->resetForm();
        $this->disciplineId = $this->disciplines()->first()?->getKey();
        $this->showForm = true;
    }

    public function edit(int $categoryId): void
    {
        $category = Category::query()->findOrFail($categoryId);
        $this->authorize('update', $category);

        $this->editingId = $category->getKey();
        $this->disciplineId = $category->discipline_id;
        $this->name = $category->name;
        $this->gender = $category->gender;
        $this->minAge = $category->min_age !== null ? (string) $category->min_age : '';
        $this->maxAge = $category->max_age !== null ? (string) $category->max_age : '';
        $this->isActive = $category->is_active;
        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $category = $this->editingId
            ? Category::query()->findOrFail($this->editingId)
            : new Category();

        $this->authorize($category->exists ? 'update' : 'create', $category->exists ? $category : Category::class);

        $validated = $this->validate([
            'disciplineId' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', Rule::in(array_keys(Category::GENDERS))],
            'minAge' => ['nullable', 'integer', 'between:1,100'],
            'maxAge' => ['nullable', 'integer', 'between:1,100'],
            'isActive' => ['boolean'],
        ]);

        $discipline = Discipline::query()->find($this->disciplineId);

        if ($discipline === null) {
            $this->addError('disciplineId', 'Select a discipline belonging to this association.');

            return;
        }

        if ($this->minAge !== '' && $this->maxAge !== '' && (int) $this->minAge > (int) $this->maxAge) {
            $this->addError('maxAge', 'The maximum age must be greater than or equal to the minimum age.');

            return;
        }

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            $this->addError('name', 'Enter a category name containing letters or numbers.');

            return;
        }

        $duplicate = Category::query()
            ->where('discipline_id', $discipline->getKey())
            ->where('slug', $slug)
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('name', 'A category with this name already exists for the selected discipline.');

            return;
        }

        $wasEditing = $category->exists;

        $category->fill([
            'discipline_id' => $discipline->getKey(),
            'name' => $validated['name'],
            'slug' => $slug,
            'gender' => $this->gender,
            'min_age' => $this->minAge === '' ? null : (int) $this->minAge,
            'max_age' => $this->maxAge === '' ? null : (int) $this->maxAge,
            'is_active' => $this->isActive,
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'Category updated.' : 'Category created.');
    }

    public function delete(int $categoryId): void
    {
        $category = Category::query()->findOrFail($categoryId);
        $this->authorize('delete', $category);

        $category->delete();

        $this->resetPage();
        session()->flash('status', 'Category removed.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $categories = Category::query()
            ->with('discipline.sport')
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->when(
                $this->disciplineFilter !== '' && ctype_digit($this->disciplineFilter),
                fn ($query) => $query->where('discipline_id', (int) $this->disciplineFilter)
            )
            ->orderBy('name')
            ->paginate(12);

        return view('sport::livewire.categories.index', [
            'categories' => $categories,
            'disciplines' => $this->disciplines(),
        ]);
    }

    private function disciplines(): Collection
    {
        return Discipline::query()
            ->with('sport')
            ->orderBy('name')
            ->get();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->gender = 'any';
        $this->minAge = '';
        $this->maxAge = '';
        $this->isActive = true;
        $this->disciplineId = null;
        $this->resetErrorBag();
    }
}
