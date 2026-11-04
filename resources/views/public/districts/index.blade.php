@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
@endphp

@section('title', 'District directory — '.$shortName)
@section('description', 'Browse the '.$districtCount.' districts covered by '.$association->name.' across '.$stateName.'.')
@section('og_image', asset('images/site/hero-stadium.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="District network"
        title="Targetball across the state."
        :intro="'The association\'s district master lists '.number_format($districtCount).' active '.Illuminate\Support\Str::plural('district', $districtCount).' across '.$stateName.'. Find your district and its public page.'"
        image="hero-stadium"
        image-alt="State teams marching at the 2025 National Targetball Championship in Bhopal"
        :image-width="1600"
        :image-height="900"
        :image-small-width="800"
        badge="Statewide coverage"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Districts']]"
    >
        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Districts</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($districtCount) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">State</dt>
                <dd class="mt-2 font-display text-lg font-semibold leading-tight text-white">{{ $stateName }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Association</dt>
                <dd class="mt-2 font-display text-lg font-semibold leading-tight text-white">{{ $shortName }}</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="py-16 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Official district list"
                title="Find your district."
                copy="Search the state master by district name or code. Each district page is published from the association's district records."
            />

            <x-public.district-search :districts="$districts" :state-name="$stateName" />
        </div>
    </section>

    <section class="border-t border-line bg-cream/60 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[1fr_0.8fr] lg:items-center">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">District pages</p>
                    <h2 class="display-serif display-serif-md mt-4 text-ink-950">Published only when confirmed.</h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-600">
                        District contacts, affiliated bodies and local activity appear on a district page once the association confirms the record. Until then, the directory confirms that the district is part of the state master and nothing more.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['label' => 'District record', 'value' => 'Listed in state master'],
                        ['label' => 'Public profile', 'value' => 'As confirmed'],
                        ['label' => 'Local events', 'value' => 'When published'],
                        ['label' => 'District contact', 'value' => 'After verification'],
                    ] as $item)
                        <div class="rounded-xl border border-line bg-white px-4 py-4">
                            <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-1.5 text-sm font-semibold text-ink-950">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Corrections"
        title="District information missing?"
        copy="District profiles, contacts and local activity are published as the association confirms them. Write to the office if something needs correction."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">Contact the association</a>
        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg btn-pill">About the association</a>
    </x-public.cta-band>
@endsection
