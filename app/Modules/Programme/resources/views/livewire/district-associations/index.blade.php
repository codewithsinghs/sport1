<div>
    <x-page-header
        title="District associations"
        subtitle="Affiliation records and supervision for every district body under the association."
    >
        @can('create', App\Modules\Programme\Models\DistrictAssociation::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm">Add district body</button>
        @endcan
    </x-page-header>

    <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
        @foreach ($stats as $stat)
            <div class="card px-4 py-3.5">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $stat['label'] }}</p>
                <p class="mt-1.5 text-2xl font-semibold tabular-nums text-slate-900">{{ number_format((int) $stat['value']) }}</p>
            </div>
        @endforeach
    </div>

    <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
        {{ $coverage['covered'] }} of {{ $coverage['covered'] + $coverage['unclaimed'] }} districts in the state have a body on record.
        @if ($coverage['unclaimed'] > 0)
            {{ $coverage['unclaimed'] }} still need affiliation details.
        @else
            Every district is covered.
        @endif
    </div>

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6">
            <div class="flex items-center gap-3">
                <p class="text-sm font-semibold text-slate-900">{{ $bodies->total() }} district {{ Str::plural('body', $bodies->total()) }}</p>
                <span class="text-xs text-slate-500">Supervision notes stay internal.</span>
            </div>
            <div class="flex flex-1 flex-col gap-3 sm:flex-row lg:flex-none">
                <div class="w-full sm:w-56">
                    <input
                        type="search"
                        wire:model.live.debounce.300ms="search"
                        class="input"
                        placeholder="Search body or contact"
                        aria-label="Search district associations"
                    >
                </div>
                <div class="w-full sm:w-44">
                    <select wire:model.live="statusFilter" class="input" aria-label="Filter by status">
                        <option value="">All statuses</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">District body</th>
                        <th class="px-5 py-3">Contact</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Players</th>
                        <th class="px-5 py-3">Events</th>
                        <th class="px-5 py-3">Last reviewed</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($bodies as $body)
                        <tr wire:key="district-body-{{ $body->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $body->name }}</p>
                                <p class="text-xs text-slate-500">{{ $body->district?->name ?: 'District not linked' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($body->contactLine())
                                    <p class="text-slate-700">{{ $body->contactLine() }}</p>
                                    <p class="text-xs text-slate-500">{{ collect([$body->email, $body->phone])->filter()->implode(' · ') ?: '—' }}</p>
                                @else
                                    <span class="text-xs text-amber-600">Contact pending</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $body->status->badgeTone() }}">{{ $body->status->label() }}</span>
                            </td>
                            <td class="px-5 py-3.5 tabular-nums text-slate-700">
                                {{ $body->players_registered ?: '—' }}
                                <span class="block text-xs text-slate-400">{{ $body->coaches_registered ?: 0 }} coaches</span>
                            </td>
                            <td class="px-5 py-3.5 tabular-nums text-slate-700">{{ $body->events_count }}</td>
                            <td class="px-5 py-3.5">
                                @if ($body->last_reviewed_on)
                                    <p class="text-slate-700">{{ $body->last_reviewed_on->format('j M Y') }}</p>
                                    @if ($body->last_reviewed_on->lt(now()->subDays(180)))
                                        <p class="text-xs text-amber-600">Review overdue</p>
                                    @endif
                                @else
                                    <span class="text-xs text-amber-600">Never reviewed</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $body)
                                        <button type="button" wire:click="edit({{ $body->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    @endcan
                                    @can('delete', $body)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $body->id }})"
                                            wire:confirm="Remove {{ $body->name }} from the association register?"
                                            class="btn btn-danger btn-sm"
                                        >Delete</button>
                                    @endcan
                                    @cannot('update', $body)
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No district bodies recorded yet. Add the first one to start tracking affiliation and supervision.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($bodies->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $bodies->links() }}
            </div>
        @endif
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit district body' : 'Add district body'">
        <form wire:submit="save" class="space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="body-district" class="label">District</label>
                    <select id="body-district" class="input" wire:model="districtId" required>
                        <option value="">Choose a district</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                    @error('districtId')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-status" class="label">Affiliation status</label>
                    <select id="body-status" class="input" wire:model="status">
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="body-name" class="label">Registered name</label>
                <input id="body-name" type="text" class="input" wire:model="name" placeholder="e.g. Targetball Association Bhopal" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="body-contact-name" class="label">Contact person <span class="font-normal text-slate-400">(optional)</span></label>
                    <input id="body-contact-name" type="text" class="input" wire:model="contactName">
                    @error('contactName')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-contact-role" class="label">Designation</label>
                    <input id="body-contact-role" type="text" class="input" wire:model="contactDesignation" placeholder="Secretary / President">
                    @error('contactDesignation')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-email" class="label">Email</label>
                    <input id="body-email" type="email" class="input" wire:model="email">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-phone" class="label">Phone</label>
                    <input id="body-phone" type="tel" class="input" wire:model="phone">
                    @error('phone')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="body-address" class="label">Office address</label>
                <textarea id="body-address" class="input" rows="2" wire:model="address"></textarea>
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label for="body-players" class="label">Players registered</label>
                    <input id="body-players" type="number" min="0" class="input tabular-nums" wire:model="playersRegistered" placeholder="0">
                    @error('playersRegistered')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-coaches" class="label">Coaches registered</label>
                    <input id="body-coaches" type="number" min="0" class="input tabular-nums" wire:model="coachesRegistered" placeholder="0">
                    @error('coachesRegistered')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="body-reviewed" class="label">Last reviewed</label>
                    <input id="body-reviewed" type="date" class="input" wire:model="lastReviewedOn">
                    @error('lastReviewedOn')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="body-notes" class="label">Supervision notes <span class="font-normal text-slate-400">(internal)</span></label>
                <textarea id="body-notes" class="input" rows="3" wire:model="supervisionNotes" placeholder="Compliance, affiliations fees, event conduct, follow-ups."></textarea>
                @error('supervisionNotes')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Record district body' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
