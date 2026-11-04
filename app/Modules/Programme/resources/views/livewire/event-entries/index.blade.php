<div>
    <x-page-header
        :title="$event->title"
        subtitle="Participation snapshot — role, jersey and team are recorded as they were at this event."
    >
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">Event settings</a>
        <a href="{{ route('admin.events.media', $event) }}" class="btn btn-secondary btn-sm">Gallery</a>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr] lg:items-start">
        <div class="card overflow-hidden">
            <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 sm:px-6">
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $entries->total() }} recorded {{ Str::plural('entry', $entries->total()) }}</p>
                    <p class="text-xs text-slate-500">Snapshots stay even if a player later moves district or category.</p>
                </div>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm">Back</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="table-head">
                        <tr>
                            <th class="px-5 py-3 sm:px-6">Player</th>
                            <th class="px-5 py-3">Team / category</th>
                            <th class="px-5 py-3">Role</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Place</th>
                            <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($entries as $entry)
                            <tr wire:key="entry-{{ $entry->id }}">
                                <td class="px-5 py-3.5 sm:px-6">
                                    <p class="font-medium text-slate-900">{{ $entry->displayName() }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $entry->player?->reference_code ?: 'No code' }}
                                        @if ($entry->jersey_number)
                                            · #{{ $entry->jersey_number }}
                                        @endif
                                    </p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-slate-700">{{ $entry->team_name ?: '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ $entry->category?->name ?: 'Category not set' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $entry->role->label() }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="badge {{ $entry->status->badgeTone() }}">{{ $entry->status->label() }}</span>
                                </td>
                                <td class="px-5 py-3.5 tabular-nums text-slate-700">
                                    @if ($entry->placement)
                                        {{ $entry->placement }}
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right sm:px-6">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" wire:click="edit({{ $entry->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                        <button
                                            type="button"
                                            wire:click="delete({{ $entry->id }})"
                                            wire:confirm="Remove {{ $entry->displayName() }} from this event record?"
                                            class="btn btn-danger btn-sm"
                                        >Remove</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500 sm:px-6">
                                    No entries recorded yet. Search the registry on the right to add the first one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($entries->hasPages())
                <div class="border-t border-slate-200 px-5 py-4 sm:px-6">{{ $entries->links() }}</div>
            @endif
        </div>

        <section class="card p-5">
            <h2 class="section-title">{{ $editingId ? 'Update entry' : 'Record an entry' }}</h2>
            <p class="mt-1 text-sm text-slate-500">Pick a player already in the registry, then capture how they took part.</p>

            <form wire:submit="save" class="mt-5 space-y-4">
                <div>
                    <label for="entry-search" class="label">Find player</label>
                    <input id="entry-search" type="search" class="input" wire:model.live.debounce.350ms="playerSearch" placeholder="Name, code or city" autocomplete="off">
                </div>

                <div class="max-h-64 space-y-1.5 overflow-y-auto rounded-xl border border-slate-200 bg-slate-50/60 p-2">
                    @forelse ($candidates as $player)
                        <label wire:key="candidate-{{ $player->id }}"
                            class="flex cursor-pointer items-start gap-2.5 rounded-lg px-2.5 py-2 text-sm transition hover:bg-white"
                            :class="$wire.playerId === {{ $player->id }} ? 'bg-white ring-1 ring-slate-900' : ''">
                            <input type="radio" name="playerId" value="{{ $player->id }}" wire:model="playerId" class="mt-1 border-slate-300 text-slate-900 focus:ring-slate-400">
                            <span class="min-w-0">
                                <span class="block font-medium text-slate-900">{{ $player->name }}</span>
                                <span class="block truncate text-xs text-slate-500">
                                    {{ collect([$player->reference_code, $player->city, $player->primary_role?->label()])->filter()->implode(' · ') }}
                                </span>
                            </span>
                        </label>
                    @empty
                        <p class="px-2.5 py-4 text-sm text-slate-500">No matching player in the registry. Add them from Player registry first.</p>
                    @endforelse
                </div>

                @if ($candidates->hasPages())
                    <div class="rounded-lg bg-slate-50 px-2 py-1.5">{{ $candidates->links() }}</div>
                @endif

                @error('playerId')
                    <p class="error">{{ $message }}</p>
                @enderror

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="entry-role" class="label">Role at this event</label>
                        <select id="entry-role" class="input" wire:model="role">
                            @foreach ($roleOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="entry-status" class="label">Entry status</label>
                        <select id="entry-status" class="input" wire:model="status">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="entry-category" class="label">Category</label>
                        <select id="entry-category" class="input" wire:model="categoryId">
                            <option value="">Not specified</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('categoryId')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="entry-body" class="label">Representing</label>
                        <select id="entry-body" class="input" wire:model="districtAssociationId">
                            <option value="">State association</option>
                            @foreach ($bodies as $body)
                                <option value="{{ $body->id }}">{{ $body->name }}</option>
                            @endforeach
                        </select>
                        @error('districtAssociationId')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="entry-jersey" class="label">Jersey number</label>
                        <input id="entry-jersey" type="number" min="1" max="999" class="input tabular-nums" wire:model="jerseyNumber">
                        @error('jerseyNumber')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="entry-placement" class="label">Placement</label>
                        <input id="entry-placement" type="number" min="1" class="input tabular-nums" wire:model="placement" placeholder="1 = winner">
                        @error('placement')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="entry-team" class="label">Team name at this event</label>
                    <input id="entry-team" type="text" class="input" wire:model="teamName" maxlength="150">
                    @error('teamName')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="entry-remarks" class="label">Remarks</label>
                    <textarea id="entry-remarks" class="input" rows="2" wire:model="remarks" placeholder="Injury, substitute, best player award…"></textarea>
                    @error('remarks')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                    @if ($editingId)
                        <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                    @endif
                    <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editingId ? 'Save entry' : 'Add to record' }}</span>
                        <span wire:loading>Saving&hellip;</span>
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
