@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
    $featured = $upcoming->firstWhere('is_featured', true) ?: $upcoming->first();
@endphp

@section('title', 'Events & championships — '.$shortName)
@section('description', 'Upcoming tournaments, camps and national championship updates published by '.$association->name.'.')
@section('og_image', asset('images/site/championship-stage.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Competition programme"
        title="Events that carry the game forward."
        intro="National championships, state competitions, district tournaments and coaching camps — published by the association office."
        image="championship-stage"
        image-alt="The championship stage at the National Targetball Championship in Bhopal"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="{{ $upcoming->count() }} upcoming"
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Events']]"
    >
        <x-slot:actions>
            @if ($featured)
                <a href="{{ route('events.show', $featured->slug) }}" class="btn btn-gold btn-lg btn-pill">
                    Next: {{ $featured->shortDateLabel() }}
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @endif
            <a href="#calendar" class="btn btn-outline-light btn-lg btn-pill">Full calendar</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Upcoming</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white tabular-nums">{{ $upcoming->count() }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Completed</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white tabular-nums">{{ $past->count() }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Seasons on record</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white tabular-nums">{{ count($years) }}</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section id="calendar" class="scroll-mt-24 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 border-b border-line pb-8 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Calendar</p>
                    <h2 class="display-serif display-serif-lg mt-4 text-ink-950">What is coming up.</h2>
                </div>

                <form method="GET" action="{{ route('events.index') }}" class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="type" class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Type</label>
                        <select id="type" name="type" class="input mt-1.5 w-full min-w-[9.5rem]">
                            <option value="">All types</option>
                            @foreach ($typeOptions as $value => $label)
                                <option value="{{ $value }}" @selected($activeType === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="level" class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Level</label>
                        <select id="level" name="level" class="input mt-1.5 w-full min-w-[9.5rem]">
                            <option value="">All levels</option>
                            @foreach ($levelOptions as $value => $label)
                                <option value="{{ $value }}" @selected($activeLevel === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (count($years) > 0)
                        <div>
                            <label for="year" class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Season</label>
                            <select id="year" name="year" class="input mt-1.5 w-full min-w-[7.5rem]">
                                <option value="">Any</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" @selected($activeYear === $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-brand btn-sm">Filter</button>
                    @if ($activeType || $activeLevel || $activeYear)
                        <a href="{{ route('events.index') }}" class="btn btn-ghost btn-sm">Reset</a>
                    @endif
                </form>
            </div>

            @if ($upcoming->isNotEmpty())
                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    @foreach ($upcoming as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>
            @else
                <div class="mt-10 rounded-[1.75rem] border border-dashed border-line bg-white/70 px-6 py-12 text-center">
                    <p class="display-serif text-2xl text-ink-950">The next calendar is being confirmed.</p>
                    <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-600">
                        Tournament dates are published here as soon as they are approved by the association office. District associations can register interest in hosting at any time.
                    </p>
                    <div class="mt-7 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('contact', ['topic' => 'tournament']) }}" class="btn btn-brand btn-pill">Register to host</a>
                        <a href="{{ route('results.index') }}" class="btn btn-ghost btn-pill">See past results</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    @foreach ($blocks as $block)
        @include('public.parts.block', ['block' => $block])
    @endforeach

    @if ($past->isNotEmpty())
        <section class="border-y border-line bg-cream/60 py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-public.section-heading
                    eyebrow="Recently concluded"
                    title="Results, squads and photographs."
                    copy="Each finished event keeps its entry list and gallery as an archive of the programme."
                />

                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    @foreach ($past->take(6) as $event)
                        @include('public.events.partials.card', ['event' => $event])
                    @endforeach
                </div>

                @if ($past->count() > 6)
                    <p class="mt-8 text-sm text-slate-500">
                        Showing the six most recent of {{ $past->count() }} archived events.
                        Filter by season to see earlier editions.
                    </p>
                @endif
            </div>
        </section>
    @endif

    @if ($upcoming->isEmpty() && $past->isEmpty())
        <section class="border-y border-line bg-cream/60 py-16 lg:py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <x-public.section-heading
                    eyebrow="The competition year"
                    title="A calendar built around the nationals."
                    copy="The association programme follows the national competition cycle, with district activity feeding into state selection."
                />

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ([
                        ['phase' => 'District', 'title' => 'District activity', 'copy' => 'Local competitions and selection trials across member districts.'],
                        ['phase' => 'State', 'title' => 'State selection', 'copy' => 'Squads and officials prepared for national competition.'],
                        ['phase' => 'National', 'title' => 'National championship', 'copy' => 'The 2025 edition was hosted in Bhopal from 27 to 30 October.'],
                        ['phase' => 'International', 'title' => 'International representation', 'copy' => 'Indian teams compete at international targetball events.'],
                    ] as $index => $item)
                        <article class="luxe-card flex flex-col bg-white p-6">
                            <span class="kicker-number">{{ sprintf('%02d', $index + 1) }}</span>
                            <p class="mt-6 text-[10px] font-bold uppercase tracking-[0.18em] text-gold">{{ $item['phase'] }}</p>
                            <h3 class="mt-2 font-display text-lg font-semibold text-ink-950">{{ $item['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['copy'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-public.cta-band
        eyebrow="Host with us"
        title="Bring a competition to your district."
        copy="District associations can register interest in hosting state-level events and coaching programmes."
    >
        <a href="{{ route('contact', ['topic' => 'tournament']) }}" class="btn btn-gold btn-lg btn-pill">
            Contact the office
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-pill">Join the association</a>
    </x-public.cta-band>
@endsection
