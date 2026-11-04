@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
    $instagram = $association->setting(\App\Modules\Association\Services\AssociationSettings::INSTAGRAM_URL);
    $nationalBody = $association->setting(\App\Modules\Association\Services\AssociationSettings::NATIONAL_BODY_URL);
@endphp

@section('title', 'News & notices — '.$shortName)
@section('description', 'Association notices, championship updates and official announcements from '.$association->name.'.')
@section('og_image', asset('images/site/speaker.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Newsroom"
        title="Notices from the association office."
        intro="Championship updates, member notices and official communications, published in one place."
        image="speaker"
        image-alt="Association representative speaking at the national championship ceremony"
        :image-width="900"
        :image-height="1125"
        :image-small-width="600"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'News & notices']]"
    />

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1.35fr_0.65fr] lg:gap-14">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Featured update</p>

                    <article class="mt-6 overflow-hidden rounded-[2rem] border border-line bg-white shadow-soft">
                        <div class="relative aspect-[16/9] overflow-hidden">
                            <x-public.photo
                                name="ceremony"
                                alt="Opening ceremony at the 2025 National Targetball Championship in Bhopal"
                                :width="1400"
                                :height="933"
                                :small-width="700"
                                sizes="(min-width: 1024px) 60vw, 100vw"
                                class="h-full w-full object-cover"
                            />
                            <div class="photo-veil-soft absolute inset-0"></div>
                            <span class="absolute left-5 top-5 rounded-full bg-white/95 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.16em] text-ink-950">Championship</span>
                        </div>
                        <div class="p-6 sm:p-9">
                            <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">
                                <time datetime="{{ $story['date'] }}">{{ \Illuminate\Support\Carbon::parse($story['date'])->format('j F Y') }}</time>
                                <span aria-hidden="true">&middot;</span>
                                <span>Association office</span>
                            </div>
                            <h2 class="display-serif display-serif-md mt-4 text-ink-950">{{ $story['title'] }}</h2>
                            <p class="mt-4 max-w-2xl text-base leading-relaxed text-slate-600">{{ $story['excerpt'] }}</p>

                            <div class="mt-6 space-y-4 border-t border-line pt-6 text-sm leading-relaxed text-slate-600 sm:text-base">
                                @foreach ($story['body'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>

                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ route('events.index') }}" class="btn btn-brand btn-pill">Event details</a>
                                <a href="{{ route('gallery.index') }}" class="btn btn-ghost btn-pill">View photography</a>
                            </div>
                        </div>
                    </article>

                    <div class="mt-8 rounded-2xl border border-dashed border-line bg-white/60 p-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Publication policy</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Only verified association communications appear on this page. Notices are published with a date and an issuing office so members can confirm their authenticity.
                        </p>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-2xl border border-line bg-cream/60 p-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Notice board</p>
                        <ul class="mt-4 space-y-4">
                            <li class="border-b border-line pb-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-gold">27 Oct 2025</p>
                                <p class="mt-1 text-sm font-semibold text-ink-950">National championship opens in Bhopal</p>
                            </li>
                            <li class="border-b border-line pb-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Pending</p>
                                <p class="mt-1 text-sm text-slate-600">Championship results to be published after technical verification.</p>
                            </li>
                            <li>
                                <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Pending</p>
                                <p class="mt-1 text-sm text-slate-600">2026 competition calendar and membership renewal notices.</p>
                            </li>
                        </ul>
                    </div>

                    <div class="luxe-panel photo-grain p-6">
                        <p class="eyebrow-luxe text-gold">Official channels</p>
                        <h3 class="display-serif mt-3 text-2xl text-white">Verify before you share.</h3>
                        <p class="mt-2 text-sm leading-relaxed text-white/55">Association announcements are also published on the official channels below.</p>
                        <div class="mt-6 space-y-2">
                            @if ($instagram)
                                <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                                    <span>Instagram &middot; {{ $shortName }}</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                                </a>
                            @endif
                            @if ($nationalBody)
                                <a href="{{ $nationalBody }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                                    <span>Targetball India</span>
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Media"
        title="Working on a story about the game?"
        copy="Association photography and factual background on Targetball are available to accredited media through the office."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">Contact the media desk</a>
        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg btn-pill">About the game</a>
    </x-public.cta-band>
@endsection
