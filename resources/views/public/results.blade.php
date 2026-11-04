@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
@endphp

@section('title', 'Results & standings — '.$shortName)
@section('description', 'Championship results, standings and participation records published by '.$association->name.'.')
@section('og_image', asset('images/site/championship-team.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Results & standings"
        title="The record of competition."
        intro="Official results are published by the championship technical committee. Verified standings, participation records and final placements appear here once released."
        image="championship-team"
        image-alt="Team captains and officials at the 2025 National Targetball Championship"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Results']]"
    >
        <x-slot:actions>
            <a href="{{ route('events.index') }}" class="btn btn-outline-light btn-lg btn-pill">Championship programme</a>
            <a href="{{ route('downloads.index') }}" class="btn btn-gold btn-lg btn-pill">Forms &amp; downloads</a>
        </x-slot:actions>
    </x-public.page-hero>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Latest championship</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">{{ $championship['name'] }}</h2>
                    <p class="mt-4 text-sm font-semibold uppercase tracking-[0.14em] text-slate-500">{{ $championship['edition'] }}</p>
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-slate-600">
                        Held at {{ $championship['venue'] }} from {{ $championship['dates'] }}, organised by {{ $championship['organiser'] }}.
                    </p>

                    <div class="mt-10 rounded-2xl border border-line bg-cream/60 p-6">
                        <div class="flex items-start gap-3">
                            <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gold/40 bg-white text-gold">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M12 7v5l3 2"/></svg>
                            </span>
                            <div>
                                <p class="font-display text-base font-semibold text-ink-950">Official results pending publication</p>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-600">
                                    Final standings will be added once received from the technical committee. This portal publishes verified records only and does not report provisional scores.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Competition categories</p>
                    <div class="mt-4 divide-y divide-line rounded-2xl border border-line bg-white">
                        @foreach ([
                            ['title' => 'Junior', 'copy' => 'National junior competition, 12th edition hosted in Bhopal.'],
                            ['title' => 'Sub Junior', 'copy' => 'National sub-junior competition, 11th edition hosted in Bhopal.'],
                            ['title' => 'Mixed Junior', 'copy' => 'Mixed junior draw, 4th edition hosted in Bhopal.'],
                            ['title' => 'Mixed Sub Junior', 'copy' => 'Mixed sub-junior draw, 4th edition hosted in Bhopal.'],
                        ] as $category)
                            <div class="flex items-start justify-between gap-6 px-6 py-5">
                                <div>
                                    <h3 class="font-display text-base font-semibold text-ink-950">{{ $category['title'] }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $category['copy'] }}</p>
                                </div>
                                <span class="shrink-0 rounded-full border border-line px-3 py-1 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-500">Pending</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-xs leading-relaxed text-slate-500">
                        Category structure follows the national championship programme. State-level results are published after technical verification.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="What we publish"
                title="Verified, not provisional."
                copy="Results are released in a defined order so that every published record can be traced to the technical committee."
            />

            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['title' => 'Final standings', 'copy' => 'Team placements for each category after the technical committee signs off.'],
                    ['title' => 'Participation records', 'copy' => 'Confirmed state contingents, squad sizes and officials for the championship.'],
                    ['title' => 'Individual honours', 'copy' => 'Awards and recognitions announced by the organising committee.'],
                ] as $item)
                    <article class="luxe-card bg-white p-7">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gold/40 text-gold">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                        </span>
                        <h3 class="mt-5 font-display text-lg font-semibold text-ink-950">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 rounded-[2rem] border border-line bg-white p-6 sm:p-10 lg:grid-cols-[1fr_0.8fr] lg:gap-14">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Records office</p>
                    <h2 class="display-serif display-serif-md mt-4 text-ink-950">Need a verified result or participation certificate?</h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-600">
                        District bodies and member institutions can request official confirmation from the association office. Include the event, category and team details in your request.
                    </p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="btn btn-brand btn-pill">Request confirmation</a>
                        <a href="{{ route('downloads.index') }}" class="btn btn-ghost btn-pill">Download forms</a>
                    </div>
                </div>
                <figure class="overflow-hidden rounded-2xl border border-line">
                    <x-public.photo
                        name="officials"
                        alt="Association officials at the national championship in Bhopal"
                        :width="1400"
                        :height="933"
                        :small-width="700"
                        sizes="(min-width: 1024px) 32vw, 100vw"
                        class="aspect-[4/3] w-full object-cover"
                    />
                </figure>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Stay informed"
        title="Announcements go out through official channels."
        copy="Follow the association on Instagram and check this page for verified competition records."
    >
        <a href="{{ route('news.index') }}" class="btn btn-gold btn-lg btn-pill">News &amp; notices</a>
        <a href="{{ route('gallery.index') }}" class="btn btn-outline-light btn-lg btn-pill">Championship gallery</a>
    </x-public.cta-band>
@endsection
