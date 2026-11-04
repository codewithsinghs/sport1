<div>
    <x-page-header
        title="Events &amp; championships"
        subtitle="Build the public event page, control the branding, and move each listing through approval."
    >
        <a href="{{ route('admin.events.create') }}" class="btn btn-brand btn-sm">New event</a>
    </x-page-header>

    <div class="mb-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ($counts as $label => $value)
            <div class="card px-4 py-3.5">
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ ucfirst(str_replace('_', ' ', $label)) }}</p>
                <p class="mt-1.5 text-2xl font-semibold tabular-nums text-slate-900">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6">
            <p class="text-sm font-semibold text-slate-900">{{ $events->total() }} {{ Str::plural('listing', $events->total()) }}</p>
            <div class="flex flex-1 flex-col gap-3 sm:flex-row lg:flex-none">
                <div class="w-full sm:w-56">
                    <input type="search" wire:model.live.debounce.300ms="search" class="input" placeholder="Search title or venue" aria-label="Search events">
                </div>
                <div class="w-full sm:w-40">
                    <select wire:model.live="statusFilter" class="input" aria-label="Filter by status">
                        <option value="">All statuses</option>
                        @foreach ($statusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-40">
                    <select wire:model.live="typeFilter" class="input" aria-label="Filter by type">
                        <option value="">All types</option>
                        @foreach ($typeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($search !== '' || $statusFilter !== '' || $typeFilter !== '')
                    <button type="button" wire:click="clearFilters" class="btn btn-secondary btn-sm">Clear</button>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Event</th>
                        <th class="px-5 py-3">Dates</th>
                        <th class="px-5 py-3">Host</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($events as $event)
                        <tr wire:key="event-{{ $event->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <div class="flex items-start gap-3">
                                    <img src="{{ $event->heroImageUrl() }}" alt="" class="h-11 w-16 shrink-0 rounded-lg object-cover" loading="lazy" width="64" height="44">
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.events.edit', $event) }}" class="font-medium text-slate-900 hover:text-slate-700" wire:navigate>{{ $event->title }}</a>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $event->event_type->label() }} · {{ $event->level->label() }}
                                            @if ($event->is_featured)
                                                <span class="ml-1 text-gold">★ featured</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700">
                                {{ $event->dateRangeLabel() }}
                                <span class="block text-xs text-slate-400">{{ $event->isUpcoming() ? 'Upcoming' : 'Past' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700">
                                {{ $event->hostName() ?: ($event->venue_name ?: '—') }}
                                <span class="block text-xs text-slate-400">{{ $event->venue_name && $event->hostName() ? $event->venue_name : '' }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $event->status->badgeTone() }}">{{ $event->status->label() }}</span>
                            </td>
                            <td class="px-5 py-3.5 sm:px-6">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @can('update', $event)
                                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm" wire:navigate>Edit</a>
                                    @endcan

                                    @can('view', $event)
                                        <a href="{{ route('admin.events.entries', $event) }}" class="btn btn-secondary btn-sm">Roster</a>
                                        <a href="{{ route('admin.events.media', $event) }}" class="btn btn-secondary btn-sm">Gallery</a>
                                    @endcan

                                    @if ($canManage)
                                        @if ($event->status === App\Modules\Programme\Enums\EventStatus::Draft)
                                            <button type="button" wire:click="submit({{ $event->id }})" class="btn btn-secondary btn-sm">Submit</button>
                                        @endif

                                        @if ($event->status === App\Modules\Programme\Enums\EventStatus::Submitted)
                                            <button type="button" wire:click="approve({{ $event->id }})" class="btn btn-brand btn-sm">Approve</button>
                                        @endif

                                        @if ($event->status === App\Modules\Programme\Enums\EventStatus::Approved)
                                            <button type="button" wire:click="publish({{ $event->id }})" class="btn btn-brand btn-sm">Publish</button>
                                        @endif

                                        @if ($event->status->isPubliclyVisible())
                                            <a href="{{ route('events.show', $event->slug) }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">View live</a>
                                            @if ($event->status === App\Modules\Programme\Enums\EventStatus::Published)
                                                <button type="button" wire:click="complete({{ $event->id }})" class="btn btn-secondary btn-sm">Mark complete</button>
                                            @endif
                                            <button type="button" wire:click="unpublish({{ $event->id }})" wire:confirm="Move {{ $event->title }} back to draft? The public page will be hidden." class="btn btn-secondary btn-sm">Unpublish</button>
                                        @endif

                                        @if (! in_array($event->status, [App\Modules\Programme\Enums\EventStatus::Cancelled, App\Modules\Programme\Enums\EventStatus::Completed], true))
                                            <button type="button" wire:click="cancel({{ $event->id }})" wire:confirm="Cancel {{ $event->title }}? It will be hidden from the public site." class="btn btn-danger btn-sm">Cancel</button>
                                        @endif

                                        @can('delete', $event)
                                            <button type="button" wire:click="delete({{ $event->id }})" wire:confirm="Permanently delete {{ $event->title }} with its entries and gallery?" class="btn btn-danger btn-sm">Delete</button>
                                        @endcan
                                    @else
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center sm:px-6">
                                <p class="text-sm text-slate-600">No events match this view.</p>
                                @can('create', App\Modules\Programme\Models\Event::class)
                                    <a href="{{ route('admin.events.create') }}" class="btn btn-brand btn-sm mt-4">Create the first event</a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
