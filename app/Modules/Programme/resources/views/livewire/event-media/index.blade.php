<div>
    <x-page-header
        :title="$event->title.' — gallery'"
        subtitle="Photographs for this event. Drag to set the order used by the public lightbox."
    >
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">Event settings</a>
        <a href="{{ route('admin.events.entries', $event) }}" class="btn btn-secondary btn-sm">Roster</a>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr] lg:items-start">
        <section class="card overflow-hidden">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 sm:px-6">
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $media->count() }} {{ Str::plural('photograph', $media->count()) }}</p>
                    <p class="text-xs text-slate-500">{{ $publicCount }} visible on the public site.</p>
                </div>
                @if ($event->isPubliclyVisible())
                    <a href="{{ route('events.show', $event->slug) }}#gallery" target="_blank" rel="noopener" class="btn btn-secondary btn-sm">Public gallery</a>
                @endif
            </div>

            @if ($media->isEmpty())
                <div class="px-5 py-14 text-center sm:px-6">
                    <p class="text-sm text-slate-600">No photographs yet.</p>
                    <p class="mt-1 text-xs text-slate-500">Upload a batch on the right.</p>
                </div>
            @else
                <div x-ref="grid" x-init="Sortable.create($refs.grid, { handle: '[data-drag]', animation: 160, ghostClass: 'opacity-40', onEnd: () => { $wire.call('reorder', Array.from($refs.grid.children).map((el) => Number(el.dataset.id))); } })" class="grid gap-3 p-5 sm:grid-cols-2 sm:p-6">
                    @foreach ($media as $item)
                        <div data-id="{{ $item->id }}" wire:key="media-{{ $item->id }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
                            <div class="relative aspect-[3/2] bg-slate-100">
                                <img src="{{ $item->url() }}" alt="{{ $item->labelOrFallback() }}" class="h-full w-full object-cover" loading="lazy">
                                <button type="button" data-drag class="absolute left-2 top-2 cursor-grab rounded-lg bg-white/85 px-2 py-1.5 text-slate-600 shadow-sm backdrop-blur" aria-label="Drag to reorder">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" d="M8 7h8M8 12h8M8 17h8"/></svg>
                                </button>
                                @unless ($item->is_public)
                                    <span class="absolute right-2 top-2 rounded-full bg-ink-950/80 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-white">Internal</span>
                                @endunless
                            </div>

                            <div class="space-y-1.5 px-4 py-3">
                                <p class="truncate text-sm font-medium text-slate-900">{{ $item->caption ?: 'Untitled photograph' }}</p>
                                <p class="line-clamp-2 text-xs text-slate-500">{{ $item->alt }}</p>
                                <p class="text-[11px] text-slate-400">{{ $item->width && $item->height ? $item->width.'×'.$item->height : 'size unknown' }}@if ($item->credit) · {{ $item->credit }}@endif</p>
                            </div>

                            <div class="flex items-center justify-between gap-2 border-t border-slate-100 px-4 py-2.5">
                                <button type="button" wire:click="togglePublic({{ $item->id }})" class="text-xs font-semibold text-slate-600 underline underline-offset-2 hover:text-slate-900">
                                    {{ $item->is_public ? 'Hide from public' : 'Publish' }}
                                </button>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" wire:click="edit({{ $item->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Delete this photograph and its file?" class="btn btn-danger btn-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <div class="space-y-6">
            <section class="card p-5">
                <h2 class="section-title">Upload photographs</h2>
                <p class="mt-1 text-sm text-slate-500">JPG, PNG or WebP up to 6 MB each. Twelve files per batch.</p>

                <form wire:submit="save" class="mt-4 space-y-3">
                    @foreach (array_pad($uploads, 1, null) as $index => $upload)
                        <div wire:key="upload-{{ $index }}">
                            <label class="label" for="upload-{{ $index }}">File {{ $index + 1 }}</label>
                            <input id="upload-{{ $index }}" type="file" class="input" wire:model="uploads.{{ $index }}" accept="image/jpeg,image/png,image/webp">
                            @error("uploads.{{ $index }}")
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @if ($upload)
                                <img src="{{ $upload->temporaryUrl() }}" alt="Preview" class="mt-2 h-24 w-full rounded-xl border border-slate-200 object-cover">
                            @endif
                        </div>
                    @endforeach

                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" wire:click="addUpload" class="btn btn-secondary btn-sm">Add another file</button>
                        <button type="submit" class="btn btn-brand btn-sm" wire:loading.attr="disabled">
                            <span wire:loading.remove>Upload</span>
                            <span wire:loading>Uploading&hellip;</span>
                        </button>
                    </div>
                </form>
            </section>

            @if ($editingId)
                <section class="card p-5">
                    <h2 class="section-title">Photo details</h2>
                    <form wire:submit="update" class="mt-4 space-y-4">
                        <div>
                            <label for="media-caption" class="label">Caption</label>
                            <input id="media-caption" type="text" class="input" wire:model="caption" maxlength="180">
                            @error('caption')<p class="error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="media-alt" class="label">Alt text</label>
                            <input id="media-alt" type="text" class="input" wire:model="alt" required maxlength="255">
                            @error('alt')<p class="error">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="media-credit" class="label">Credit</label>
                            <input id="media-credit" type="text" class="input" wire:model="credit" maxlength="120">
                            @error('credit')<p class="error">{{ $message }}</p>@enderror
                        </div>
                        <label class="flex items-center gap-2.5 text-sm text-slate-700">
                            <input type="checkbox" wire:model="isPublic" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                            Show in the public gallery
                        </label>
                        <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                            <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                            <button type="submit" class="btn btn-brand">Save details</button>
                        </div>
                    </form>
                </section>
            @endif
        </div>
    </div>
</div>
