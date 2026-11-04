<div
    x-data="{
        heroBase: @js(rtrim(asset('images/site/'), '/').'/'),
        uploadedUrl: @js($event?->exists && $event->hasUploadedHero() ? $event->heroImageUrl() : null),
        get uploaded() { return $wire.hasUploadedHero === true || this.uploadedUrl !== null; },
        get heroImage() { return $wire.heroImage; },
        get theme() { return $wire.theme; },
        get heroPreview() {
            if (this.uploaded) return this.uploadedUrl || @js($event?->heroImageUrl());
            return this.heroBase + this.heroImage + '.webp';
        },
        mapSrc() {
            const la = parseFloat($wire.mapLat), lo = parseFloat($wire.mapLng);
            if (Number.isNaN(la) || Number.isNaN(lo) || Math.abs(la) > 90 || Math.abs(lo) > 180) return null;
            const d = 0.012;
            return 'https://www.openstreetmap.org/export/embed.html?bbox='
                + encodeURIComponent((lo - d) + ',' + (la - d) + ',' + (lo + d) + ',' + (la + d))
                + '&layer=mapnik&marker=' + encodeURIComponent(la + ',' + lo);
        },
    }"
>
    <x-page-header
        :title="$isEditing ? 'Edit event' : 'New event'"
        subtitle="Everything here drives the public event page — schedule, venue map, branding and detail copy."
    >
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm">All events</a>
    </x-page-header>

    @if ($status)
        <div class="mb-6 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm">
            <span class="badge {{ $status->badgeTone() }}">{{ $status->label() }}</span>
            <span class="text-slate-500">
                @if ($status->isPubliclyVisible())
                    Live at
                    <a href="{{ route('events.show', $event->slug) }}" target="_blank" rel="noopener" class="font-medium text-slate-800 underline decoration-slate-300 underline-offset-2">{{ route('events.show', $event->slug) }}</a>
                @else
                    Not visible on the public site yet. Use the workflow buttons on the events list.
                @endif
            </span>
            @if ($event->approved_at)
                <span class="ml-auto text-xs text-slate-400">Approved {{ $event->approved_at->format('j M Y') }} by {{ $event->approver?->name }}</span>
            @endif
        </div>
    @endif

    <form wire:submit="save" class="grid gap-6 lg:grid-cols-[1.35fr_1fr] lg:items-start">
        <div class="space-y-6">
            <section class="card p-5 sm:p-6">
                <h2 class="section-title">Listing</h2>
                <p class="mt-1 text-sm text-slate-500">The title and summary appear in cards across the public site.</p>

                <div class="mt-5 space-y-4">
                    <div>
                        <label for="event-title" class="label">Event title</label>
                        <input id="event-title" type="text" class="input" wire:model.blur="title" required maxlength="180"
                            placeholder="12th Junior &amp; 11th Sub Junior National Targetball Championship">
                        @error('title')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event-slug" class="label">Web address</label>
                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-3">
                            <span class="shrink-0 text-xs text-slate-500">/events/</span>
                            <input id="event-slug" type="text" class="w-full border-0 bg-transparent py-2.5 text-sm focus:ring-0" wire:model.blur="slug" required>
                        </div>
                        @error('slug')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="event-type" class="label">Type</label>
                            <select id="event-type" class="input" wire:model="eventType">
                                @foreach ($typeOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('eventType')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="event-level" class="label">Level</label>
                            <select id="event-level" class="input" wire:model="level">
                                @foreach ($levelOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('level')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="event-summary" class="label">Summary <span class="font-normal text-slate-400">(max 280 characters)</span></label>
                        <textarea id="event-summary" class="input" rows="2" wire:model="summary" maxlength="280"
                            placeholder="One or two lines used in the calendar card, meta description and search results."></textarea>
                        <p class="mt-1 text-right text-xs tabular-nums text-slate-400"><span x-text="($wire.summary ?? '').length"></span>/280</p>
                        @error('summary')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="card p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h2 class="section-title">Detail copy</h2>
                        <p class="mt-1 text-sm text-slate-500">Format with the toolbar. Headings, lists, quotes and links are kept; anything else is stripped on save.</p>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Rich text</p>
                </div>

                <div wire:ignore class="mt-5"
                    x-data="{}"
                    x-init="
                        const quill = new Quill($refs.editor, {
                            theme: 'snow',
                            placeholder: 'Format, categories, entry rules, venue notes…',
                            modules: {
                                toolbar: [
                                    [{ header: [2, 3, false] }],
                                    ['bold', 'italic', 'underline', 'link', 'clean'],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['blockquote'],
                                    [{ align: [] }],
                                ],
                            },
                        });
                        quill.root.innerHTML = $wire.description || '';
                        quill.on('text-change', () => {
                            const html = quill.root.innerHTML;
                            $wire.set('description', html === '<p><br></p>' ? '' : html, false);
                        });
                    "
                >
                    <div x-ref="editor" class="min-h-[320px]"></div>
                    <p class="mt-2 text-xs text-slate-400">Links open in a new tab and are marked safe automatically.</p>
                </div>
                @error('description')
                    <p class="error mt-3">{{ $message }}</p>
                @enderror
            </section>

            <section class="card p-5 sm:p-6">
                <h2 class="section-title">Schedule</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="event-start" class="label">Starts</label>
                        <input id="event-start" type="datetime-local" class="input" wire:model="startsAt" required>
                        @error('startsAt')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event-end" class="label">Ends <span class="font-normal text-slate-400">(optional)</span></label>
                        <input id="event-end" type="datetime-local" class="input" wire:model="endsAt">
                        @error('endsAt')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <label class="mt-4 flex items-center gap-2.5 text-sm text-slate-700">
                    <input type="checkbox" wire:model="isFeatured" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                    Feature on the homepage season board
                </label>
            </section>
        </div>

        <div class="space-y-6">
            <section class="card sticky top-6 p-5">
                <h2 class="section-title">Save</h2>
                <p class="mt-1 text-sm text-slate-500">Saving never changes the workflow status. Approve and publish from the events list.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $isEditing ? 'Save changes' : 'Create event' }}</span>
                        <span wire:loading>Saving&hellip;</span>
                    </button>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Back to list</a>
                </div>
                @if ($isEditing)
                    <div class="mt-4 space-y-1.5 border-t border-slate-200 pt-4 text-sm">
                        <a href="{{ route('admin.events.entries', $event) }}" class="flex items-center justify-between text-slate-600 hover:text-slate-900">
                            <span>Roster &amp; results snapshot</span><span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('admin.events.media', $event) }}" class="flex items-center justify-between text-slate-600 hover:text-slate-900">
                            <span>Gallery images</span><span aria-hidden="true">→</span>
                        </a>
                        <a href="{{ route('contact', ['event' => $event->slug]) }}" target="_blank" rel="noopener" class="flex items-center justify-between text-slate-600 hover:text-slate-900">
                            <span>Enquiry form for this event</span><span aria-hidden="true">↗</span>
                        </a>
                    </div>
                @endif
            </section>

            <section class="card p-5">
                <h2 class="section-title">Host &amp; sport</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="event-body" class="label">District association</label>
                        <select id="event-body" class="input" wire:model="districtAssociationId">
                            <option value="">State association (host)</option>
                            @foreach ($bodyOptions as $body)
                                <option value="{{ $body->id }}">{{ $body->name }}</option>
                            @endforeach
                        </select>
                        @error('districtAssociationId')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event-sport" class="label">Sport</label>
                        <select id="event-sport" class="input" wire:model="sportId">
                            <option value="">Not specified</option>
                            @foreach ($sportOptions as $sport)
                                <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                            @endforeach
                        </select>
                        @error('sportId')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="card p-5">
                <h2 class="section-title">Venue &amp; map</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="event-venue" class="label">Venue name</label>
                        <input id="event-venue" type="text" class="input" wire:model.blur="venueName" placeholder="Tatya Tope Stadium">
                        @error('venueName')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event-address" class="label">Address</label>
                        <textarea id="event-address" class="input" rows="2" wire:model="venueAddress" placeholder="T.T. Nagar, Bhopal, Madhya Pradesh"></textarea>
                        @error('venueAddress')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="event-lat" class="label">Latitude</label>
                            <input id="event-lat" type="text" inputmode="decimal" class="input" wire:model.blur="mapLat" placeholder="23.2415">
                            @error('mapLat')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="event-lng" class="label">Longitude</label>
                            <input id="event-lng" type="text" inputmode="decimal" class="input" wire:model.blur="mapLng" placeholder="77.4137">
                            @error('mapLng')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <template x-if="mapSrc()">
                        <div class="overflow-hidden rounded-xl border border-slate-200">
                            <iframe :src="mapSrc()" class="h-52 w-full" loading="lazy" title="Venue map preview" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </template>
                    <template x-if="!mapSrc()">
                        <p class="rounded-xl border border-dashed border-slate-300 px-4 py-5 text-center text-xs text-slate-500">
                            Add coordinates to show a map on the public page. Paste them from Google Maps or OpenStreetMap.
                        </p>
                    </template>
                </div>
            </section>

            <section class="card p-5">
                <h2 class="section-title">Branding</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <p class="label">Page theme</p>
                        <div class="mt-2 grid gap-2">
                            @foreach ($themeOptions as $value => $label)
                                <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 px-3 py-2.5 text-sm transition hover:border-slate-300" :class="theme === '{{ $value }}' ? 'border-slate-900 bg-slate-50' : ''">
                                    <input type="radio" wire:model="theme" value="{{ $value }}" class="border-slate-300 text-slate-900 focus:ring-slate-400">
                                    <span class="text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('theme')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event-accent" class="label">Accent colour <span class="font-normal text-slate-400">(optional)</span></label>
                        <div class="flex items-center gap-2">
                            <input id="event-accent" type="color" class="h-10 w-12 cursor-pointer rounded-lg border border-slate-200 bg-white p-1" wire:model.blur="accentColor" value="#c1a26a">
                            <input type="text" class="input flex-1" wire:model.blur="accentColor" placeholder="#c1a26a" maxlength="7" aria-label="Accent colour hex">
                        </div>
                        @error('accentColor')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <p class="label">Header image</p>
                        <div class="mt-2 overflow-hidden rounded-xl border border-slate-200">
                            <img :src="heroPreview" alt="" class="h-32 w-full object-cover" loading="lazy">
                        </div>

                        <template x-if="uploaded">
                            <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs text-slate-600">
                                Using your uploaded photograph.
                                <button type="button" wire:click="useCuratedHero" class="ml-1 font-semibold text-slate-900 underline underline-offset-2">Switch back to curated imagery</button>
                            </div>
                        </template>

                        <template x-if="!uploaded">
                            <div class="mt-3 space-y-3">
                                <select class="input" wire:model="heroImage" aria-label="Curated header image">
                                    @foreach ($heroOptions as $name)
                                        <option value="{{ $name }}">{{ ucwords(str_replace('-', ' ', $name)) }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-slate-500">Curated set is verified Targetball photography already shipped with the site.</p>
                            </div>
                        </template>

                        <div class="mt-3">
                            <label for="event-hero-upload" class="label">Or upload a new header</label>
                            <input id="event-hero-upload" type="file" class="input" wire:model="heroUpload" accept="image/jpeg,image/png,image/webp">
                            @error('heroUpload')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            @if ($heroUpload)
                                <div class="mt-3 overflow-hidden rounded-xl border border-slate-200">
                                    <img src="{{ $heroUpload->temporaryUrl() }}" alt="Upload preview" class="h-28 w-full object-cover">
                                    <p class="bg-slate-50 px-3 py-2 text-xs text-slate-500">Saved as the header when you press Save.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <section class="card p-5">
                <h2 class="section-title">Links &amp; contact</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label for="event-registration" class="label">Registration link</label>
                        <input id="event-registration" type="url" class="input" wire:model.blur="registrationUrl" placeholder="https://…">
                        @error('registrationUrl')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event-email" class="label">Event email</label>
                        <input id="event-email" type="email" class="input" wire:model.blur="contactEmail">
                        @error('contactEmail')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="event-phone" class="label">Event phone</label>
                        <input id="event-phone" type="tel" class="input" wire:model.blur="contactPhone">
                        @error('contactPhone')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>
        </div>
    </form>
</div>
