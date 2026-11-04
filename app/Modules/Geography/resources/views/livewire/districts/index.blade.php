<div>
    <x-page-header
        title="Districts"
        :subtitle="$stateName ? 'Reusable district master for '.$stateName.'.' : 'District master data.'"
    >
        @can('create', App\Modules\Geography\Models\District::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm">Add district</button>
        @endcan
    </x-page-header>

    @if ($stateId === null)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            This association does not have a state configured yet. Set the state on the association profile before managing districts.
        </div>
    @endif

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $districts->total() }} districts</p>
                <p class="text-xs text-slate-500">District records are shared master data and are not duplicated per association.</p>
            </div>
            <div class="w-full sm:w-72">
                <input
                    type="search"
                    wire:model.live.debounce.300ms="search"
                    class="input"
                    placeholder="Search by name or code"
                    aria-label="Search districts"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">District</th>
                        <th class="px-5 py-3">Code</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($districts as $district)
                        <tr wire:key="district-{{ $district->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $district->name }}</p>
                                <p class="text-xs text-slate-500">{{ $district->slug }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $district->code ?: '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $district->is_active ? 'badge-green' : 'badge-slate' }}">
                                    {{ $district->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $district)
                                        <button type="button" wire:click="edit({{ $district->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    @endcan
                                    @can('delete', $district)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $district->id }})"
                                            wire:confirm="Remove {{ $district->name }} from the district master?"
                                            class="btn btn-danger btn-sm"
                                        >Delete</button>
                                    @endcan
                                    @cannot('update', $district)
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No districts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($districts->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $districts->links() }}
            </div>
        @endif
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit district' : 'Add district'">
        <form wire:submit="save" class="space-y-5">
            <div>
                <label for="district-name" class="label">District name</label>
                <input id="district-name" type="text" class="input" wire:model="name" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="district-code" class="label">District code <span class="font-normal text-slate-400">(optional)</span></label>
                <input id="district-code" type="text" class="input" wire:model="code">
                @error('code')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                Active district
            </label>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Create district' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
