@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
    $theme = $event->theme ?: 'ink';
    $accent = $event->accentColor() ?: $association->secondaryColor();
    $hero = $event->heroImageUrl();
    $isDark = in_array($theme, ['ink', 'brand'], true);
    $dateLabel = $event->dateRangeLabel();
@endphp

@section('title', $event->title.' — '.$shortName)
@section('description', $event->summary ?: $event->title.' · '.$dateLabel.' · '.($event->venue_name ?: 'venue to be announced'))
@section('og_image', $hero)

@push('head')
    <script type="application/ld+json">{!! json_encode($event->structuredData(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')
    <section
        class="relative isolate overflow-hidden {{ $isDark ? 'text-white' : 'text-ink-950' }}"
        style="background: {{ $theme === 'brand' ? 'linear-gradient(135deg, '.$association->primaryColor().' 0%, '.$accent.' 140%)' : ($theme === 'cream' ? '#ece4d3' : '#06110e') }};"
    >
        <div class="absolute inset-0">
            <img src="{{ $hero }}" alt="{{ $event->title }}" width="1600" height="900"
                {{ $isDark ? '' : 'opacity-30' }}
                class="h-full w-full object-cover {{ $isDark ? 'opacity-40' : '' }}">
            <div class="absolute inset-0 {{ $isDark ? 'photo-veil' : 'bg-gradient-to-b from-white/70 via-white/40 to-transparent' }}"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 pb-14 pt-28 sm:px-6 lg:px-8 lg:pb-20 lg:pt-36">
            <nav aria-label="Breadcrumb" class="flex flex-wrap items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.14em] {{ $isDark ? 'text-white/55' : 'text-slate-500' }}">
                <a href="{{ route('home') }}" class="transition hover:text-gold">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('events.index') }}" class="transition hover:text-gold">Events</a>
                <span aria-hidden="true">/</span>
                <span class="{{ $isDark ? 'text-white/80' : 'text-ink-950' }}">{{ $event->event_type->label() }}</span>
            </nav>

            <div class="mt-8 flex flex-wrap items-center gap-2.5 text-[10px] font-bold uppercase tracking-[0.16em]">
                <span class="rounded-full px-3 py-1.5 ring-1 {{ $isDark ? 'bg-white/10 text-white ring-white/25' : 'bg-white/70 text-ink-950 ring-ink-950/10' }}">{{ $event->event_type->label() }}</span>
                <span class="rounded-full px-3 py-1.5" style="background: {{ $accent }}; color: {{ $isDark ? '#06110e' : '#ffffff' }}">{{ $event->level->label() }}</span>
                @if ($event->status === \App\Modules\Programme\Enums\EventStatus::Completed)
                    <span class="rounded-full px-3 py-1.5 ring-1 {{ $isDark ? 'text-white/80 ring-white/25' : 'text-slate-600 ring-ink-950/10' }}">Concluded</span>
                @endif
                @if ($event->is_featured)
                    <span class="rounded-full px-3 py-1.5 ring-1 text-gold ring-gold/40">Featured</span>
                @endif
            </div>

            <h1 class="display-serif display-serif-xl mt-6 max-w-4xl {{ $isDark ? 'text-white' : 'text-ink-950' }}">{{ $event->title }}</h1>

            @if ($event->summary)
                <p class="mt-5 max-w-2xl text-base leading-relaxed {{ $isDark ? 'text-white/70' : 'text-slate-700' }}">{{ $event->summary }}</p>
            @endif

            <dl class="mt-10 grid max-w-3xl gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border px-4 py-3.5 {{ $isDark ? 'border-white/12 bg-white/[0.05]' : 'border-ink-950/10 bg-white/70' }}">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] {{ $isDark ? 'text-white/45' : 'text-slate-500' }}">Dates</dt>
                    <dd class="mt-1.5 font-display text-sm font-semibold {{ $isDark ? 'text-white' : 'text-ink-950' }}">{{ $dateLabel }}</dd>
                </div>
                <div class="rounded-2xl border px-4 py-3.5 {{ $isDark ? 'border-white/12 bg-white/[0.05]' : 'border-ink-950/10 bg-white/70' }}">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] {{ $isDark ? 'text-white/45' : 'text-slate-500' }}">Venue</dt>
                    <dd class="mt-1.5 font-display text-sm font-semibold {{ $isDark ? 'text-white' : 'text-ink-950' }}">{{ $event->venue_name ?: 'To be announced' }}</dd>
                </div>
                <div class="rounded-2xl border px-4 py-3.5 {{ $isDark ? 'border-white/12 bg-white/[0.05]' : 'border-ink-950/10 bg-white/70' }}">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] {{ $isDark ? 'text-white/45' : 'text-slate-500' }}">Hosted by</dt>
                    <dd class="mt-1.5 font-display text-sm font-semibold {{ $isDark ? 'text-white' : 'text-ink-950' }}">{{ $event->hostName() ?: $association->name }}</dd>
                </div>
            </dl>

            <div class="mt-9 flex flex-wrap gap-3">
                @if ($event->registration_url)
                    <a href="{{ $event->registration_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-gold btn-lg btn-pill">
                        Registration
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                    </a>
                @endif
                @if ($event->mapLink())
                    <a href="{{ $event->mapLink() }}" target="_blank" rel="noopener noreferrer" class="btn {{ $isDark ? 'btn-outline-light' : 'btn-brand' }} btn-lg btn-pill">Directions</a>
                @endif
                <a href="{{ route('contact', ['event' => $event->slug, 'topic' => 'tournament']) }}" class="btn {{ $isDark ? 'btn-outline-light' : 'btn-ghost' }} btn-lg btn-pill">Ask about this event</a>
            </div>
        </div>
    </section>

    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.5fr_1fr] lg:items-start">
                <div>
                    @if ($event->descriptionHtml() !== '')
                        <div class="rte">
                            {!! $event->descriptionHtml() !!}
                        </div>
                    @else
                        <p class="rounded-2xl border border-dashed border-line bg-white/60 px-6 py-8 text-sm leading-relaxed text-slate-600">
                            The full prospectus for this {{ strtolower($event->event_type->label()) }} is being finalised by the association office.
                            Categories, entry limits and schedule will be published here.
                        </p>
                    @endif

                    @if ($media->isNotEmpty())
                        <div id="gallery" class="scroll-mt-24 mt-14">
                            <x-public.section-heading
                                eyebrow="Gallery"
                                title="From the {{ $event->event_type->label() }}."
                                copy="Photographs published by the association media desk."
                            />

                            <div class="mt-8">
                                <x-public.photo-grid
                                    :items="$media->map(fn ($item): array => [
                                        'full' => $item->url(),
                                        'thumb' => $item->url(),
                                        'alt' => $item->labelOrFallback(),
                                        'caption' => $item->caption,
                                        'credit' => $item->credit,
                                        'width' => $item->width,
                                        'height' => $item->height,
                                    ])->values()->all()"
                                    label="{{ $event->title }} photographs"
                                />
                            </div>
                        </div>
                    @endif

                    @if ($entries->isNotEmpty())
                        <div class="mt-14">
                            <x-public.section-heading
                                eyebrow="{{ $event->status === \App\Modules\Programme\Enums\EventStatus::Completed ? 'Results' : 'Entries' }}"
                                title="Who took part."
                                copy="Recorded by the association office at the time of the event."
                            />

                            <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-line bg-white">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-line text-sm">
                                        <thead class="table-head">
                                            <tr>
                                                <th class="px-5 py-3 text-left">Placing</th>
                                                <th class="px-5 py-3 text-left">Name</th>
                                                <th class="px-5 py-3 text-left">Team</th>
                                                <th class="px-5 py-3 text-left">Category</th>
                                                <th class="px-5 py-3 text-left">Role</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-line/70">
                                            @foreach ($entries as $entry)
                                                <tr wire:key="entry-{{ $entry->id }}">
                                                    <td class="px-5 py-3.5">
                                                        @if ($entry->placement)
                                                            <span class="display-serif text-lg text-gold">{{ $entry->placement }}</span>
                                                        @else
                                                            <span class="text-slate-400">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3.5 font-medium text-ink-950">
                                                        {{ $entry->displayName() }}
                                                        @if ($entry->jersey_number)
                                                            <span class="ml-1.5 text-xs text-slate-400">#{{ $entry->jersey_number }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3.5 text-slate-600">{{ $entry->team_name ?: ($entry->districtAssociation?->name ?: '—') }}</td>
                                                    <td class="px-5 py-3.5 text-slate-600">{{ $entry->category?->name ?: '—' }}</td>
                                                    <td class="px-5 py-3.5">
                                                        <span class="badge badge-slate">{{ $entry->role->label() }}</span>
                                                        <span class="ml-2 text-xs text-slate-500">{{ $entry->status->label() }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="space-y-6 lg:sticky lg:top-24">
                    <div class="luxe-panel photo-grain rounded-[1.75rem] p-6 text-white">
                        <p class="eyebrow-luxe text-gold">Event information</p>
                        <dl class="mt-5 space-y-4 text-sm">
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/40">Schedule</dt>
                                <dd class="mt-1 text-white/90">{{ $dateLabel }}@if ($event->timeRangeLabel()) · {{ $event->timeRangeLabel() }}@endif</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/40">Venue</dt>
                                <dd class="mt-1 text-white/90">{{ $event->venue_name ?: 'To be announced' }}</dd>
                                @if ($event->venue_address)
                                    <dd class="mt-1 text-white/55">{{ $event->venue_address }}</dd>
                                @endif
                            </div>
                            <div>
                                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/40">Organiser</dt>
                                <dd class="mt-1 text-white/90">{{ $event->hostName() ?: $association->name }}</dd>
                            </div>
                            @if ($event->sport)
                                <div>
                                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/40">Sport</dt>
                                    <dd class="mt-1 text-white/90">{{ $event->sport->name }}</dd>
                                </div>
                            @endif
                            @if ($event->contact_email || $event->contact_phone)
                                <div>
                                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/40">Contact</dt>
                                    <dd class="mt-1 space-y-1">
                                        @if ($event->contact_email)
                                            <a href="mailto:{{ $event->contact_email }}" class="block text-gold-soft transition hover:text-white">{{ $event->contact_email }}</a>
                                        @endif
                                        @if ($event->contact_phone)
                                            <a href="tel:{{ $event->contact_phone }}" class="block text-white/80 transition hover:text-white">{{ $event->contact_phone }}</a>
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    @if ($event->mapEmbedUrl())
                        <div class="overflow-hidden rounded-[1.75rem] border border-line bg-white">
                            <iframe
                                src="{{ $event->mapEmbedUrl() }}"
                                class="h-72 w-full"
                                loading="lazy"
                                title="Map showing {{ $event->venue_name ?: $event->title }}"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                            <div class="flex items-center justify-between gap-3 px-5 py-4">
                                <p class="text-xs text-slate-500">{{ $event->venue_name ?: 'Venue map' }}</p>
                                <a href="{{ $event->mapLink() }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm">Open in Maps</a>
                            </div>
                        </div>
                    @endif

                    <div class="rounded-[1.75rem] border border-line bg-white p-6">
                        <p class="eyebrow-luxe" style="color: var(--brand-deep)">Competing or hosting?</p>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">
                            Send the office your entry, your district proposal or a request for the prospectus and we will respond with the official forms.
                        </p>
                        <a href="{{ route('contact', ['event' => $event->slug, 'topic' => 'tournament']) }}" class="btn btn-brand btn-pill mt-5 w-full justify-center">
                            Make an enquiry
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="border-t border-line bg-cream/60 py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-public.section-heading eyebrow="More from the calendar" title="Other events on the programme." />
                <div class="mt-10 grid gap-6 lg:grid-cols-3">
                    @foreach ($related as $sibling)
                        @include('public.events.partials.card', ['event' => $sibling])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
