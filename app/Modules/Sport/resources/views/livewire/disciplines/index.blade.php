<div>
    <x-page-header
        title="Disciplines"
        subtitle="Competition disciplines configured for this association."
    >
        @can('create', App\Modules\Sport\Models\Discipline::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm" @disabled($sports->isEmpty())>Add discipline</button>
        @endcan
    </x-page-header>

    @if ($sports->isEmpty())
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Enable at least one sport for this association before adding disciplines.
            <a href="{{ route('admin.sports.index') }}" class="font-semibold underline">Manage sports</a>
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $disciplines->total() }} disciplines</p>
                <p class="text-xs text-slate-500">Disciplines and their categories are owned by this association.</p>
            </div>
            <div class="w-full sm:w-72">
                <input type="search" wire:model.live.debounce.300ms="search" class="input" placeholder="Search disciplines" aria-label="Search disciplines">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Discipline</th>
                        <th class="px-5 py-3">Sport</th>
                        <th class="px-5 py-3">Categories</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($disciplines as $discipline)
                        <tr wire:key="discipline-{{ $discipline->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $discipline->name }}</p>
                                <p class="max-w-md truncate text-xs text-slate-500">{{ $discipline->description ?: 'No description' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $discipline->sport?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $discipline->categories_count }}</td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $discipline->is_active ? 'badge-green' : 'badge-slate' }}">
                                    {{ $discipline->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $discipline)
                                        <button type="button" wire:click="edit({{ $discipline->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    @endcan
                                    @can('delete', $discipline)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $discipline->id }})"
                                            wire:confirm="Remove {{ $discipline->name }} and its categories?"
                                            class="btn btn-danger btn-sm"
                                        >Delete</button>
                                    @endcan
                                    @cannot('update', $discipline)
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No disciplines configured yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($disciplines->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $disciplines->links() }}
            </div>
        @endif
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit discipline' : 'Add discipline'">
        <form wire:submit="save" class="space-y-5">
            <div>
                <label for="discipline-sport" class="label">Sport</label>
                <select id="discipline-sport" class="input" wire:model="sportId" required>
                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                    @endforeach
                </select>
                @error('sportId')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discipline-name" class="label">Discipline name</label>
                <input id="discipline-name" type="text" class="input" wire:model="name" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="discipline-description" class="label">Description <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="discipline-description" rows="3" class="input" wire:model="description"></textarea>
                @error('description')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                Active discipline
            </label>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Create discipline' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
