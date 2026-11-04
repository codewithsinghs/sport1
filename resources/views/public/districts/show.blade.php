@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $initial = mb_strtoupper(mb_substr($district->name, 0, 1));
@endphp

@section('title', $district->name.' district — '.$shortName)
@section('description', $district->name.' is listed in the '.$association->name.' district master for '.$stateName.'. District contact and activity details are published as they are confirmed.')
@section('og_image', asset('images/site/hero-stadium.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="District"
        :title="$district->name"
        :intro="'A district in '.$stateName.' listed in the '.$shortName.' state master. Verified district contacts and local activity will be published on this page.'"
        image="hero-stadium"
        image-alt="State contingents at the National Targetball Championship 2025"
        :image-width="1600"
        :image-height="900"
        :image-small-width="800"
        compact
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Districts', 'url' => route('districts.index')],
            ['label' => $district->name],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('districts.index') }}" class="btn btn-outline-light btn-pill">All districts</a>
            <a href="{{ route('contact') }}" class="btn btn-gold btn-pill">Contact the office</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">District code</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white">{{ $district->code ?: '—' }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">State</dt>
                <dd class="mt-2 font-display text-lg font-semibold leading-tight text-white">{{ $stateName }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Status</dt>
                <dd class="mt-2"><span class="badge badge-green">Listed</span></dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="py-16 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1.15fr_0.85fr] lg:gap-10 lg:px-8">
            <div class="luxe-card p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="eyebrow-luxe" style="color: var(--brand-deep)">District profile</p>
                        <h2 class="display-serif display-serif-md mt-4 text-ink-950">Profile details are being compiled.</h2>
                    </div>
                    <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-cream font-display text-lg font-bold text-gold">{{ $initial }}</span>
                </div>
                <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-600">
                    {{ $shortName }} publishes a district profile once its contact details, assigned administrator and
                    local body information are confirmed. No provisional contact details are shown here.
                </p>

                <div class="mt-8 space-y-3">
                    @foreach (['District contact details', 'Assigned district administrator', 'Local affiliated bodies'] as $item)
                        <div class="flex items-center justify-between gap-3 rounded-xl border border-dashed border-line bg-cream/40 px-4 py-3">
                            <p class="text-sm font-medium text-slate-600">{{ $item }}</p>
                            <span class="badge badge-amber">Awaiting confirmation</span>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('contact') }}" class="btn btn-brand mt-8">
                    Ask about {{ $district->name }}
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <aside class="space-y-6">
                <div class="luxe-panel photo-grain p-6 sm:p-7">
                    <p class="eyebrow-luxe text-gold">At a glance</p>
                    <dl class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-4">
                            <dt class="text-white/50">District</dt>
                            <dd class="font-semibold text-white">{{ $district->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-4">
                            <dt class="text-white/50">Code</dt>
                            <dd class="font-semibold text-white">{{ $district->code ?: 'Not assigned' }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4 border-b border-white/10 pb-4">
                            <dt class="text-white/50">Association</dt>
                            <dd class="font-semibold text-white">{{ $shortName }}</dd>
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-white/50">Districts listed</dt>
                            <dd class="font-semibold text-white">{{ number_format($districtCount) }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="luxe-card p-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Directory</p>
                    <div class="mt-4 space-y-3">
                        @if ($previous)
                            <a href="{{ route('districts.show', $previous->slug) }}" class="group flex items-center gap-3 rounded-xl border border-line px-4 py-3 transition hover:border-gold/40 hover:bg-cream/40">
                                <svg class="h-4 w-4 text-slate-400 transition group-hover:text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6-6-6 6 6 6"/></svg>
                                <span class="text-sm font-medium text-slate-700">{{ $previous->name }}</span>
                            </a>
                        @endif
                        @if ($next)
                            <a href="{{ route('districts.show', $next->slug) }}" class="group flex items-center justify-between gap-3 rounded-xl border border-line px-4 py-3 transition hover:border-gold/40 hover:bg-cream/40">
                                <span class="text-sm font-medium text-slate-700">{{ $next->name }}</span>
                                <svg class="h-4 w-4 text-slate-400 transition group-hover:text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                            </a>
                        @endif
                        <a href="{{ route('districts.index') }}" class="flex items-center justify-between gap-3 rounded-xl bg-cream/60 px-4 py-3 text-sm font-semibold text-ink-950 transition hover:bg-cream">
                            All {{ number_format($districtCount) }} districts
                            <svg class="h-4 w-4 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6"/></svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Local activity"
                title="What will be published here."
                copy="District-level organizations, events and results appear once the relevant association module is live and the records are approved for publication."
            />

            <div class="mt-10 grid gap-5 md:grid-cols-3">
                @foreach ([
                    ['title' => 'Organizations', 'copy' => 'Affiliated clubs and district bodies approved for the public directory.'],
                    ['title' => 'Events', 'copy' => 'District competitions and entry information once events are published.'],
                    ['title' => 'Results', 'copy' => 'Approved district results, published with their event details.'],
                ] as $module)
                    <article class="luxe-card bg-white p-6">
                        <span class="badge badge-slate">Not yet published</span>
                        <h3 class="mt-5 font-display text-lg font-semibold text-ink-950">{{ $module['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $module['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Get involved"
        :title="'Representing '.$district->name.'?'"
        copy="District bodies, coaches and players can contact the association office about local participation and membership."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">Contact the association</a>
        <a href="{{ route('membership') }}" class="btn btn-outline-light btn-lg btn-pill">Membership categories</a>
    </x-public.cta-band>
@endsection
