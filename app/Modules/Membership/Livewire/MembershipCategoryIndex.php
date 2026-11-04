<?php

namespace App\Modules\Membership\Livewire;

use App\Modules\Membership\Models\MembershipCategory;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class MembershipCategoryIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $fee = '0';

    public string $billingPeriod = 'annual';

    public bool $requiresApproval = true;

    public string $description = '';

    public bool $isActive = true;

    public function mount(): void
    {
        $this->authorize('viewAny', MembershipCategory::class);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->authorize('create', MembershipCategory::class);

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $categoryId): void
    {
        $category = MembershipCategory::query()->findOrFail($categoryId);
        $this->authorize('update', $category);

        $this->editingId = $category->getKey();
        $this->name = $category->name;
        $this->fee = (string) $category->fee;
        $this->billingPeriod = $category->billing_period;
        $this->requiresApproval = $category->requires_approval;
        $this->description = $category->description ?? '';
        $this->isActive = $category->is_active;
        $this->showForm = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $category = $this->editingId
            ? MembershipCategory::query()->findOrFail($this->editingId)
            : new MembershipCategory();

        $this->authorize($category->exists ? 'update' : 'create', $category->exists ? $category : MembershipCategory::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'fee' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'billingPeriod' => ['required', Rule::in(array_keys(MembershipCategory::BILLING_PERIODS))],
            'description' => ['nullable', 'string', 'max:1000'],
            'requiresApproval' => ['boolean'],
            'isActive' => ['boolean'],
        ]);

        $slug = Str::slug($validated['name']);

        if ($slug === '') {
            $this->addError('name', 'Enter a category name containing letters or numbers.');

            return;
        }

        $duplicate = MembershipCategory::query()
            ->where('slug', $slug)
            ->when($this->editingId, fn ($query) => $query->whereKeyNot($this->editingId))
            ->exists();

        if ($duplicate) {
            $this->addError('name', 'A membership category with this name already exists.');

            return;
        }

        $wasEditing = $category->exists;

        $category->fill([
            'name' => $validated['name'],
            'slug' => $slug,
            'fee' => number_format((float) $validated['fee'], 2, '.', ''),
            'billing_period' => $this->billingPeriod,
            'requires_approval' => $this->requiresApproval,
            'description' => $validated['description'] ?: null,
            'is_active' => $this->isActive,
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        session()->flash('status', $wasEditing ? 'Membership category updated.' : 'Membership category created.');
    }

    public function delete(int $categoryId): void
    {
        $category = MembershipCategory::query()->findOrFail($categoryId);
        $this->authorize('delete', $category);

        $category->delete();

        $this->resetPage();
        session()->flash('status', 'Membership category removed.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $categories = MembershipCategory::query()
            ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
            ->orderBy('name')
            ->paginate(12);

        return view('membership::livewire.membership-categories.index', [
            'categories' => $categories,
        ]);
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->fee = '0';
        $this->billingPeriod = 'annual';
        $this->requiresApproval = true;
        $this->description = '';
        $this->isActive = true;
        $this->resetErrorBag();
    }
}
