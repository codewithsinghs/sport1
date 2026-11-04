<div>
    <x-page-header
        title="Categories"
        subtitle="Age, gender and competition categories for this association's disciplines."
    >
        @can('create', App\Modules\Sport\Models\Category::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm" @disabled($disciplines->isEmpty())>Add category</button>
        @endcan
    </x-page-header>

    @if ($disciplines->isEmpty())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Create at least one discipline before adding categories.
            <a href="{{ route('admin.disciplines.index') }}" class="font-semibold underline">Manage disciplines</a>
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $categories->total() }} categories</p>
                <p class="text-xs text-slate-500">Categories inherit their association scope from the parent discipline.</p>
            </div>
            <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
                <select wire:model.live="disciplineFilter" class="input sm:w-56" aria-label="Filter by discipline">
                    <option value="">All disciplines</option>
                    @foreach ($disciplines as $discipline)
                        <option value="{{ $discipline->id }}">{{ $discipline->name }}</option>
                    @endforeach
                </select>
                <input type="search" wire:model.live.debounce.300ms="search" class="input sm:w-56" placeholder="Search categories" aria-label="Search categories">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Category</th>
                        <th class="px-5 py-3">Discipline</th>
                        <th class="px-5 py-3">Gender</th>
                        <th class="px-5 py-3">Age</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($categories as $category)
                        <tr wire:key="category-{{ $category->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $category->name }}</p>
                                <p class="text-xs text-slate-500">{{ $category->slug }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $category->discipline?->name ?? '—' }}
                                <p class="text-xs text-slate-400">{{ $category->discipline?->sport?->name }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $category->genderLabel() }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $category->ageLabel() }}</td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $category->is_active ? 'badge-green' : 'badge-slate' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $category)
                                        <button type="button" wire:click="edit({{ $category->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    @endcan
                                    @can('delete', $category)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $category->id }})"
                                            wire:confirm="Remove the {{ $category->name }} category?"
                                            class="btn btn-danger btn-sm"
                                        >Delete</button>
                                    @endcan
                                    @cannot('update', $category)
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No categories configured yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit category' : 'Add category'">
        <form wire:submit="save" class="space-y-5">
            <div>
                <label for="category-discipline" class="label">Discipline</label>
                <select id="category-discipline" class="input" wire:model="disciplineId" required>
                    @foreach ($disciplines as $discipline)
                        <option value="{{ $discipline->id }}">
                            {{ $discipline->name }} &middot; {{ $discipline->sport?->name }}
                        </option>
                    @endforeach
                </select>
                @error('disciplineId')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category-name" class="label">Category name</label>
                <input id="category-name" type="text" class="input" wire:model="name" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category-gender" class="label">Gender</label>
                <select id="category-gender" class="input" wire:model="gender">
                    @foreach (App\Modules\Sport\Models\Category::GENDERS as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="category-min-age" class="label">Minimum age <span class="font-normal text-slate-400">(optional)</span></label>
                    <input id="category-min-age" type="number" min="1" max="100" class="input" wire:model="minAge">
                    @error('minAge')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="category-max-age" class="label">Maximum age <span class="font-normal text-slate-400">(optional)</span></label>
                    <input id="category-max-age" type="number" min="1" max="100" class="input" wire:model="maxAge">
                    @error('maxAge')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                Active category
            </label>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Create category' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
