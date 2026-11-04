@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $email = $association->email;
@endphp

@section('title', 'Governance — '.$shortName)
@section('description', 'How '.$association->name.' is organised: office bearers, committees and district coordination across '.$stateName.'.')
@section('og_image', asset('images/site/officials.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Governance"
        title="Leadership and association structure."
        :intro="'How '.$association->name.' organises its office bearers, committees and district network. Appointments are published after the association confirms its records.'"
        image="officials"
        image-alt="Association delegates at the 2025 National Targetball Championship in Bhopal"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="Records verified before publication"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Governance']]"
    >
        <x-slot:actions>
            @if ($email)
                <a href="mailto:{{ $email }}?subject={{ rawurlencode('Governance enquiry') }}" class="btn btn-gold btn-lg btn-pill">Email the association</a>
            @endif
            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg btn-pill">About the association</a>
        </x-slot:actions>
    </x-public.page-hero>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="How it is organised"
                title="Three levels of association work."
                copy="The platform records governance information in defined areas. Nothing is published as an appointment until the association approves it."
            />

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                @php
                    $structures = [
                        [
                            'icon' => 'M12 3 4 7v5c0 4.4 3.4 8.4 8 9 4.6-.6 8-4.6 8-9V7l-8-4Z',
                            'title' => 'Office bearers',
                            'copy' => 'President, secretary, treasurer and other office bearers, with the designation and term recorded by the association.',
                        ],
                        [
                            'icon' => 'M17 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7-1a3 3 0 1 0 0-6m4 17v-1a4 4 0 0 0-3-3.9',
                            'title' => 'Committees',
                            'copy' => 'Committee purpose, tenure and membership, maintained so historical terms are preserved rather than overwritten.',
                        ],
                        [
                            'icon' => 'M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 11h.01M15 11h.01',
                            'title' => 'District bodies',
                            'copy' => $districtCount > 0
                                ? 'Coordination across '.number_format($districtCount).' districts of '.$stateName.', with a public page for each district.'
                                : 'Coordination across the state district network.',
                        ],
                    ];
                @endphp

                @foreach ($structures as $structure)
                    <article class="luxe-card bg-white p-7">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-gold/40 bg-cream/60 text-gold">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $structure['icon'] }}" />
                            </svg>
                        </span>
                        <h3 class="mt-6 font-display text-xl font-semibold text-ink-950">{{ $structure['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $structure['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Publication status</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Appointments are being verified.</h2>
                    <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600">
                        The association is the source of every governance record. Names, designations, terms and
                        photographs will be published here once they have been confirmed. The portal does not publish
                        provisional or assumed appointments.
                    </p>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-500">
                        For a governance query in the meantime, write to the association office.
                    </p>
                    @if ($email)
                        <a href="mailto:{{ $email }}?subject={{ rawurlencode('Governance enquiry') }}" class="btn btn-brand btn-pill mt-7">Contact the office</a>
                    @endif
                </div>

                <div class="overflow-hidden rounded-2xl border border-line bg-white">
                    @foreach ([
                        ['label' => 'Office bearers', 'status' => 'Awaiting confirmation', 'tone' => 'badge-amber'],
                        ['label' => 'Committees', 'status' => 'Awaiting confirmation', 'tone' => 'badge-amber'],
                        ['label' => 'District network', 'status' => number_format($districtCount).' districts listed', 'tone' => 'badge-green'],
                        ['label' => 'Sports programme', 'status' => number_format($sportsCount).' '.\Illuminate\Support\Str::plural('sport', $sportsCount).' enabled', 'tone' => 'badge-green'],
                    ] as $record)
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4 last:border-0 sm:px-6">
                            <p class="text-sm font-semibold text-ink-950">{{ $record['label'] }}</p>
                            <span class="badge {{ $record['tone'] }}">{{ $record['status'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16">
                <figure class="relative order-2 overflow-hidden rounded-[2rem] border border-line shadow-lift lg:order-1">
                    <x-public.photo
                        name="speaker"
                        alt="Association representative addressing delegates at the national championship"
                        :width="900"
                        :height="1125"
                        :small-width="600"
                        sizes="(min-width: 1024px) 42vw, 100vw"
                        class="aspect-[4/5] w-full object-cover"
                    />
                    <figcaption class="absolute bottom-4 left-4 right-4 rounded-xl border border-white/15 bg-ink-950/70 px-4 py-3 text-xs text-white/80 backdrop-blur">
                        Association delegates, National Championship 2025
                    </figcaption>
                </figure>

                <div class="order-1 lg:order-2">
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">How records are published</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Confirmation before publication.</h2>
                    <ol class="mt-8 space-y-6">
                        @foreach ([
                            ['title' => 'Association confirms the record', 'copy' => 'The office approves each appointment, committee term and designation.'],
                            ['title' => 'Portal records the change', 'copy' => 'Authorised administrators maintain the association record in the administrative area.'],
                            ['title' => 'Public page is updated', 'copy' => 'Confirmed information appears on the public site with the current association context.'],
                        ] as $index => $step)
                            <li class="flex gap-4">
                                <span class="mt-0.5 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-gold/40 bg-white font-display text-[11px] font-semibold text-gold">{{ sprintf('%02d', $index + 1) }}</span>
                                <div>
                                    <h3 class="font-display text-base font-semibold text-ink-950">{{ $step['title'] }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $step['copy'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="District network"
        title="Looking for a district contact?"
        copy="The district directory lists every district in the state master. Verified district profiles are published as they are confirmed."
    >
        <a href="{{ route('districts.index') }}" class="btn btn-gold btn-lg btn-pill">Open the district directory</a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Contact the office</a>
    </x-public.cta-band>
@endsection
