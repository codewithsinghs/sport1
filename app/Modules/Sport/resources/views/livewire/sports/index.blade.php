<div>
    <x-page-header
        title="Sports"
        subtitle="The sport master is reusable. Enable the sports offered by this association."
    >
        @can('create', App\Modules\Sport\Models\Sport::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm">Add sport</button>
        @endcan
    </x-page-header>

    @error('toggle')
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
            {{ $message }}
        </div>
    @enderror

    <div class="card overflow-hidden">
        <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
            <p class="text-sm font-semibold text-slate-900">{{ $enabled->filter()->count() }} of {{ $sports->count() }} sports enabled</p>
            <p class="text-xs text-slate-500">Sports marked active in the master list can be enabled for this association.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Sport</th>
                        <th class="px-5 py-3">Master status</th>
                        <th class="px-5 py-3">Association access</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($sports as $sport)
                        @php($isEnabled = (bool) ($enabled[$sport->id] ?? false))
                        <tr wire:key="sport-{{ $sport->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $sport->name }}</p>
                                <p class="max-w-md truncate text-xs text-slate-500">{{ $sport->description ?: 'No description' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $sport->is_active ? 'badge-green' : 'badge-amber' }}">
                                    {{ $sport->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                @can('toggleForAssociation', App\Modules\Sport\Models\Sport::class)
                                    <button
                                        type="button"
                                        wire:click="toggle({{ $sport->id }})"
                                        role="switch"
                                        aria-checked="{{ $isEnabled ? 'true' : 'false' }}"
                                        class="relative inline-flex h-6 w-11 items-center rounded-full transition {{ $isEnabled ? '' : 'bg-slate-200' }}"
                                        @if ($isEnabled) style="background-color: var(--brand)" @endif
                                        title="{{ $isEnabled ? 'Disable for this association' : 'Enable for this association' }}"
                                    >
                                        <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition {{ $isEnabled ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                    </button>
                                    <span class="ml-2 text-xs text-slate-500">{{ $isEnabled ? 'Enabled' : 'Disabled' }}</span>
                                @else
                                    <span class="badge {{ $isEnabled ? 'badge-green' : 'badge-slate' }}">{{ $isEnabled ? 'Enabled' : 'Disabled' }}</span>
                                @endcan
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                @can('update', $sport)
                                    <button type="button" wire:click="edit({{ $sport->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                @else
                                    <span class="text-xs text-slate-400">Read only</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No sports exist in the master list yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit sport' : 'Add sport'">
        <form wire:submit="save" class="space-y-5">
            <div>
                <label for="sport-name" class="label">Sport name</label>
                <input id="sport-name" type="text" class="input" wire:model="name" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="sport-description" class="label">Description <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="sport-description" rows="3" class="input" wire:model="description"></textarea>
                @error('description')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                Active in master list
            </label>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Create sport' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
