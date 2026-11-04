@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
@endphp

@section('title', 'Events & championships — '.$shortName)
@section('description', 'Competition calendar and national championship updates from '.$association->name.'.')
@section('og_image', asset('images/site/championship-stage.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Competition programme"
        title="Events that carry the game forward."
        intro="National championships, state competitions and district activity, published by the association office."
        image="championship-stage"
        image-alt="The championship stage at the 2025 National Targetball Championship in Bhopal"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="Season 2025"
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Events']]"
    >
        <x-slot:actions>
            <a href="{{ route('results.index') }}" class="btn btn-gold btn-lg btn-pill">
                Results &amp; standings
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Host an event</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Featured</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white">Nationals 2025</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Host city</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white">Bhopal</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Dates</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white">27–30 Oct</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="relative overflow-hidden py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Headline event</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">{{ $championship['name'] }}</h2>
                    <p class="mt-4 text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $championship['edition'] }}</p>

                    <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-600">
                        Four days of junior, sub-junior and mixed competition hosted by {{ $championship['organiser'] }}. State contingents, technical officials and association delegates gathered at {{ $championship['venue'] }}.
                    </p>

                    <dl class="mt-10 grid gap-px overflow-hidden rounded-2xl border border-line bg-line sm:grid-cols-2">
                        <div class="bg-white px-5 py-5">
                            <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Dates</dt>
                            <dd class="mt-2 font-display text-base font-semibold text-ink-950">{{ $championship['dates'] }}</dd>
                        </div>
                        <div class="bg-white px-5 py-5">
                            <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Venue</dt>
                            <dd class="mt-2 font-display text-base font-semibold text-ink-950">{{ $championship['venue'] }}</dd>
                        </div>
                        <div class="bg-white px-5 py-5">
                            <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Organiser</dt>
                            <dd class="mt-2 font-display text-base font-semibold text-ink-950">{{ $championship['organiser'] }}</dd>
                        </div>
                        <div class="bg-white px-5 py-5">
                            <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Status</dt>
                            <dd class="mt-2 inline-flex items-center gap-2 font-display text-base font-semibold text-ink-950">
                                <span class="h-2 w-2 rounded-full bg-gold"></span>
                                Completed
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('results.index') }}" class="btn btn-brand btn-pill">Championship results</a>
                        <a href="{{ route('gallery.index') }}" class="btn btn-ghost btn-pill">View photography</a>
                    </div>
                </div>

                <figure class="relative">
                    <div class="luxe-frame relative aspect-[4/5] overflow-hidden rounded-[2rem]">
                        <x-public.photo
                            name="championship-lineup"
                            alt="State teams lined up at the National Targetball Championship 2025 in Bhopal"
                            :width="1400"
                            :height="933"
                            :small-width="700"
                            sizes="(min-width: 1024px) 42vw, 100vw"
                        />
                        <div class="photo-veil-soft absolute inset-0"></div>
                    </div>
                    <figcaption class="absolute bottom-5 left-5 right-5 rounded-xl border border-white/15 bg-ink-950/70 px-5 py-4 backdrop-blur">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-gold">Tatya Tope Stadium</p>
                        <p class="mt-1.5 text-sm text-white/80">Opening march past, 27 October 2025</p>
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="The competition year"
                title="A calendar built around the nationals."
                copy="The association programme follows the national competition cycle, with district activity feeding into state selection."
            />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
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

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Milestones"
                title="How the championship programme developed."
            />

            <ol class="mt-12 grid gap-px overflow-hidden rounded-[2rem] border border-line bg-line sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($milestones as $milestone)
                    <li class="bg-white p-7">
                        <p class="display-serif text-4xl text-gold">{{ $milestone['year'] }}</p>
                        <h3 class="mt-4 font-display text-base font-semibold text-ink-950">{{ $milestone['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $milestone['copy'] }}</p>
                    </li>
                @endforeach
            </ol>

            <div class="mt-10 rounded-2xl border border-dashed border-line bg-white/60 px-6 py-5 text-sm text-slate-500">
                <span class="font-semibold text-ink-950">Next event:</span>
                the 2026 competition calendar will be published here once confirmed by the association office.
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Host with us"
        title="Bring a competition to your district."
        copy="District associations can register interest in hosting state-level events and coaching programmes."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">
            Contact the office
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-pill">Join the association</a>
    </x-public.cta-band>
@endsection
