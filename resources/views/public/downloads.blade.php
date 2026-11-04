@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
@endphp

@section('title', 'Forms & downloads — '.$shortName)
@section('description', 'Membership forms, event templates and official documents from '.$association->name.'.')
@section('og_image', asset('images/site/ceremony.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Forms & downloads"
        title="The paperwork, in one place."
        intro="Membership forms, event templates and official documents issued by the association office."
        image="ceremony"
        image-alt="Opening ceremony at the 2025 National Targetball Championship"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Downloads']]"
    />

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr] lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Document library</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Issued by the office, signed and dated.</h2>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-slate-600">
                        The association is preparing a public document library. Until a document is listed here as available, request a copy directly from the office so you can be certain it is current.
                    </p>

                    <div class="mt-8 rounded-2xl border border-line bg-cream/60 p-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Document status</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            All entries are marked <span class="font-semibold text-ink-950">on request</span> until the signed PDF is published. No undated or draft forms are distributed through this portal.
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach ([
                        ['title' => 'Membership application form', 'copy' => 'Individual, life, institutional and district membership categories.', 'tag' => 'Membership'],
                        ['title' => 'District affiliation form', 'copy' => 'For district bodies seeking affiliation with the state association.', 'tag' => 'Affiliation'],
                        ['title' => 'Event sanction request', 'copy' => 'Application to host a state-level or recognised competition.', 'tag' => 'Events'],
                        ['title' => 'Participation certificate request', 'copy' => 'Official confirmation of participation for players and officials.', 'tag' => 'Records'],
                        ['title' => 'Association profile & constitution', 'copy' => 'Governing documents of the state association.', 'tag' => 'Governance'],
                    ] as $document)
                        <article class="luxe-card flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="badge badge-slate">{{ $document['tag'] }}</span>
                                    <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-gold">On request</span>
                                </div>
                                <h3 class="mt-3 font-display text-lg font-semibold text-ink-950">{{ $document['title'] }}</h3>
                                <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-slate-600">{{ $document['copy'] }}</p>
                            </div>
                            <a href="{{ route('contact') }}" class="btn btn-ghost btn-sm btn-pill shrink-0">
                                Request copy
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="How requests work"
                title="Three steps, one office."
            />
            <div class="mt-12 grid gap-6 sm:grid-cols-3">
                @foreach ([
                    ['step' => '01', 'title' => 'Send your request', 'copy' => 'Use the contact page and name the document, member or event it relates to.'],
                    ['step' => '02', 'title' => 'Office verification', 'copy' => 'The office confirms your details and the current version of the document.'],
                    ['step' => '03', 'title' => 'Receive the copy', 'copy' => 'Signed documents are sent by the office through the contact details you provide.'],
                ] as $item)
                    <article class="luxe-card bg-white p-7">
                        <p class="display-serif text-4xl text-gold">{{ $item['step'] }}</p>
                        <h3 class="mt-4 font-display text-lg font-semibold text-ink-950">{{ $item['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $item['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Membership"
        title="Start with the right category."
        copy="Review membership categories and eligibility before requesting an application form."
    >
        <a href="{{ route('membership') }}" class="btn btn-gold btn-lg btn-pill">Membership categories</a>
        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-pill">Express interest</a>
    </x-public.cta-band>
@endsection
