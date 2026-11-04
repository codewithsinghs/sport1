@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $about = $association->setting('about');
    $firstSport = $sports->first();
    $sportUrl = $firstSport ? route('sports.show', $firstSport->slug) : route('sports.index');
    $instagram = $association->setting(\App\Modules\Association\Services\AssociationSettings::INSTAGRAM_URL);
    $nationalBody = $association->setting(\App\Modules\Association\Services\AssociationSettings::NATIONAL_BODY_URL);
    $ticker = [$shortName, $stateName, 'Six a side', '30 × 20 m court', 'National Championship 2025', 'Bhopal', 'Junior · Sub-junior · Mixed'];
@endphp

@section('title', $association->name)
@section('description', $about ?: 'The official state association for Targetball in '.$stateName.'.')
@section('og_image', asset('images/site/hero-stadium.webp'))

@section('content')
    <section class="relative isolate flex min-h-[44rem] flex-col overflow-hidden bg-ink-950 text-white lg:min-h-[48rem]">
        <div class="absolute inset-0">
            <x-public.photo
                name="hero-stadium"
                alt="State teams marching at the 2025 National Targetball Championship in Bhopal"
                :width="1600"
                :height="900"
                :small-width="800"
                sizes="100vw"
                class="h-full w-full scale-[1.02] object-cover object-center"
                eager
            />
            <div class="photo-veil absolute inset-0"></div>
            <div class="absolute inset-x-0 bottom-0 h-56 bg-gradient-to-t from-ink-950 to-transparent"></div>
        </div>

        <div class="relative mx-auto flex w-full max-w-7xl flex-1 flex-col justify-end px-4 pb-10 pt-20 sm:px-6 lg:px-8 lg:pb-12 lg:pt-28">
            <div class="grid gap-12 lg:grid-cols-[1.45fr_0.55fr] lg:items-end">
                <div class="max-w-3xl">
                    <p class="reveal eyebrow-luxe text-gold">Official state association &middot; {{ $stateName }}</p>

                    <h1 class="reveal reveal-1 display-serif mt-6 text-[clamp(3.4rem,7.6vw,6.75rem)] leading-[0.93]">
                        Targetball.<br>
                        <span class="italic text-gold">Played statewide.</span>
                    </h1>

                    <p class="reveal reveal-2 mt-7 max-w-xl text-base leading-relaxed text-white/65 sm:text-lg">
                        {{ $about ?: 'The official home of '.$shortName.' — connecting players, officials and every district through one state association.' }}
                    </p>

                    <div class="reveal reveal-3 mt-9 flex flex-wrap items-center gap-3">
                        <a href="{{ $sportUrl }}" class="btn btn-gold btn-lg btn-pill">
                            Discover the game
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                        <a href="{{ route('events.index') }}" class="btn btn-outline-light btn-lg btn-pill">
                            Championship 2025
                        </a>
                    </div>
                </div>

                <aside class="reveal reveal-3 hidden lg:block">
                    <div class="rounded-2xl border border-white/15 bg-ink-950/55 p-6 backdrop-blur-xl">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-gold">Hosted in Bhopal</p>
                        <p class="mt-4 font-display text-lg font-semibold leading-snug text-white">{{ $championship['name'] }}</p>
                        <p class="mt-2 text-sm text-white/55">{{ $championship['edition'] }}</p>
                        <div class="mt-6 space-y-3 border-t border-white/10 pt-5 text-sm text-white/65">
                            <p>{{ $championship['dates'] }}</p>
                            <p>{{ $championship['venue'] }}</p>
                        </div>
                        <a href="{{ route('events.index') }}" class="mt-6 inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-gold transition hover:text-gold-soft">
                            Championship story
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                </aside>
            </div>

            <dl class="mt-14 grid grid-cols-2 gap-x-6 gap-y-8 border-t border-white/15 pt-8 sm:grid-cols-4 lg:mt-20">
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">Districts</dt>
                    <dd class="mt-2 font-display text-3xl font-semibold tracking-tight text-white">{{ number_format($districtCount) }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">Players a side</dt>
                    <dd class="mt-2 font-display text-3xl font-semibold tracking-tight text-white">6</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">Game founded</dt>
                    <dd class="mt-2 font-display text-3xl font-semibold tracking-tight text-white">2012</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">Nationals hosted</dt>
                    <dd class="mt-2 font-display text-3xl font-semibold tracking-tight text-white">2025</dd>
                </div>
            </dl>
        </div>
    </section>

    <div class="marquee border-b border-line bg-cream py-4" aria-hidden="true">
        <div class="marquee-track">
            @foreach ([...$ticker, ...$ticker] as $item)
                <span class="flex items-center gap-7 px-7 font-display text-[11px] font-semibold uppercase tracking-[0.24em] text-ink-800/60">
                    {{ $item }}
                    <span class="h-1 w-1 rounded-full bg-gold"></span>
                </span>
            @endforeach
        </div>
    </div>

    <section class="paper-grid relative overflow-hidden py-20 lg:py-28">
        <div class="mx-auto grid max-w-7xl items-center gap-14 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:gap-20 lg:px-8">
            <figure class="relative order-2 lg:order-1">
                <div class="absolute -left-4 -top-4 h-full w-full rounded-[2rem] border border-gold/40"></div>
                <div class="photo-grain relative aspect-[4/3] overflow-hidden rounded-[2rem] border border-white/10 bg-ink-900 shadow-lift">
                    <x-public.photo
                        name="championship-lineup"
                        alt="Tamil Nadu squad at the 2025 National Targetball Championship in Bhopal"
                        :width="1400"
                        :height="933"
                        :small-width="700"
                        sizes="(min-width: 1024px) 45vw, 100vw"
                        class="h-full w-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/60 to-transparent"></div>
                    <figcaption class="absolute bottom-5 left-5 rounded-full border border-white/20 bg-ink-950/70 px-4 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em] text-white/75 backdrop-blur-md">
                        National Championship 2025 &middot; Bhopal
                    </figcaption>
                </div>
            </figure>

            <div class="order-1 lg:order-2">
                <p class="eyebrow-luxe">The game</p>
                <h2 class="display-serif display-serif-lg mt-4 text-ink-950">
                    Six players. One target.<br><span class="italic" style="color: var(--brand-deep)">Everything to play for.</span>
                </h2>
                <p class="mt-6 text-base leading-relaxed text-slate-600">
                    Targetball is a fast, skilful game in which two teams of six strive to keep or gain possession of the ball. Through running, jumping, throwing and catching, the team with the ball moves it into its goal circle — from where a target may be scored — while the opposition defends and looks to turn the game around.
                </p>
                <p class="mt-4 text-sm leading-relaxed text-slate-500">
                    Formalised by Dr. Sonu Sharma in Mathura in 2012, the modern game grew out of a traditional sport played in the Braj region. It is now organised through state associations and a national federation.
                </p>

                <dl class="mt-10 grid gap-x-8 gap-y-7 sm:grid-cols-2">
                    @foreach ($gameFacts as $fact)
                        <div class="border-t border-line pt-4">
                            <dt class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">{{ $fact['label'] }}</dt>
                            <dd class="mt-1.5 font-display text-3xl font-semibold tracking-tight text-ink-950">{{ $fact['value'] }}</dd>
                            <dd class="mt-2 text-xs leading-relaxed text-slate-500">{{ $fact['copy'] }}</dd>
                        </div>
                    @endforeach
                </dl>

                <a href="{{ $sportUrl }}" class="mt-9 inline-flex items-center gap-2 text-sm font-semibold transition hover:gap-3.5" style="color: var(--brand-deep)">
                    Inside the sport — disciplines &amp; categories
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="relative isolate overflow-hidden bg-ink-950 py-20 text-white lg:py-28">
        <div class="court-grid absolute inset-0 opacity-70"></div>
        <div class="pointer-events-none absolute -left-32 top-1/3 h-96 w-96 rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, var(--brand) 0%, transparent 70%)"></div>
        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-4 sm:px-6 lg:grid-cols-[1fr_1fr] lg:gap-20 lg:px-8">
            <div>
                <p class="eyebrow-luxe text-gold">{{ $championship['dates'] }} &middot; Bhopal</p>
                <h2 class="display-serif display-serif-lg mt-5 text-white">
                    The national stage<br><span class="italic text-gold">came to Madhya Pradesh.</span>
                </h2>
                <p class="mt-6 max-w-xl text-base leading-relaxed text-white/60">
                    {{ $shortName }} hosted the {{ $championship['name'] }}, bringing state contingents, technical officials and association delegates together for four days of competition at Tatya Tope Stadium.
                </p>

                <dl class="mt-9 grid gap-5 sm:grid-cols-2">
                    <div class="border-t border-white/15 pt-4">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/40">Competitions</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-white/80">{{ $championship['edition'] }}</dd>
                    </div>
                    <div class="border-t border-white/15 pt-4">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/40">Venue</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-white/80">{{ $championship['venue'] }}</dd>
                    </div>
                    <div class="border-t border-white/15 pt-4 sm:col-span-2">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/40">Organised by</dt>
                        <dd class="mt-2 text-sm leading-relaxed text-white/80">{{ $championship['organiser'] }}</dd>
                    </div>
                </dl>

                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="{{ route('events.index') }}" class="btn btn-gold btn-pill">Championship details</a>
                    <a href="{{ route('gallery.index') }}" class="btn btn-outline-light btn-pill">View the gallery</a>
                </div>
            </div>

            <figure class="relative">
                <div class="absolute -right-3 -bottom-3 h-full w-full rounded-[2rem] border border-gold/30"></div>
                <div class="photo-grain relative aspect-[4/3] overflow-hidden rounded-[2rem] border border-white/10">
                    <x-public.photo
                        name="championship-stage"
                        alt="Opening ceremony stage at the 2025 National Targetball Championship in Bhopal"
                        :width="1400"
                        :height="933"
                        :small-width="700"
                        sizes="(min-width: 1024px) 46vw, 100vw"
                        class="h-full w-full object-cover"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-transparent to-transparent"></div>
                </div>
                <figcaption class="absolute -left-4 bottom-6 max-w-[13rem] rounded-xl border border-white/15 bg-ink-950/85 p-4 backdrop-blur-md sm:-left-8">
                    <p class="font-display text-2xl font-semibold text-gold">27–30</p>
                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.18em] text-white/55">October 2025</p>
                    <p class="mt-2 text-xs leading-relaxed text-white/65">Four days of junior, sub-junior and mixed competition.</p>
                </figcaption>
            </figure>
        </div>
    </section>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="The pathway"
                title="From a district court to national colours."
                copy="One association connects the grassroots game to the state programme and the national stage."
            >
                <x-slot:actions>
                    <a href="{{ route('about') }}" class="btn btn-secondary btn-pill">About {{ $shortName }}</a>
                </x-slot:actions>
            </x-public.section-heading>

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                @php
                    $pathway = [
                        ['number' => '01', 'title' => 'District network', 'copy' => 'A statewide network of district bodies, each linked to the association and represented in one directory.', 'url' => route('districts.index'), 'label' => 'Explore districts'],
                        ['number' => '02', 'title' => 'State programme', 'copy' => 'Disciplines, age groups and gender categories configured by the association for the game.', 'url' => $sportUrl, 'label' => 'The sport programme'],
                        ['number' => '03', 'title' => 'National representation', 'copy' => 'State competition feeds the national pathway, with results and notices published by the association.', 'url' => route('events.index'), 'label' => 'Events & results'],
                    ];
                @endphp

                @foreach ($pathway as $step)
                    <a href="{{ $step['url'] }}" class="luxe-card group flex flex-col p-7 transition duration-300 ease-swift hover:-translate-y-1">
                        <div class="flex items-start justify-between gap-4">
                            <p class="kicker-number">{{ $step['number'] }}</p>
                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-line text-slate-400 transition group-hover:border-gold group-hover:text-gold">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                            </span>
                        </div>
                        <h3 class="mt-8 font-display text-xl font-semibold text-ink-950">{{ $step['title'] }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $step['copy'] }}</p>
                        <span class="mt-7 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-slate-500 transition group-hover:text-ink-950">
                            {{ $step['label'] }}
                            <span class="h-px w-6 bg-gold transition-all duration-300 group-hover:w-10"></span>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream py-20 lg:py-28">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:items-center lg:gap-20 lg:px-8">
            <div>
                <p class="eyebrow-luxe">District network</p>
                <h2 class="display-serif display-serif-lg mt-4 text-ink-950">
                    {{ number_format($districtCount) }} districts.<br><span class="italic" style="color: var(--brand-deep)">One association.</span>
                </h2>
                <p class="mt-6 max-w-md text-base leading-relaxed text-slate-600">
                    Every district has a place in the state structure. The directory connects players, schools, clubs and district bodies to {{ $shortName }} and to the wider competition pathway.
                </p>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-slate-500">
                    District profiles are published as information is confirmed by the association office.
                </p>
                <a href="{{ route('districts.index') }}" class="btn btn-ink btn-pill mt-8">
                    Open the district directory
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>

            <div class="relative overflow-hidden rounded-[1.75rem] border border-gold/30 bg-paper p-7 shadow-soft sm:p-9">
                <div class="pointer-events-none absolute -right-16 -top-16 h-44 w-44 rounded-full border border-gold/25"></div>
                <div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full border border-gold/20"></div>

                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">Statewide coverage</p>
                        <p class="display-serif mt-2 text-6xl leading-none text-ink-950">{{ number_format($districtCount) }}</p>
                    </div>
                    <p class="text-right text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Madhya Pradesh<br>district bodies</p>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-x-6 gap-y-3 border-t border-line pt-6 sm:grid-cols-3">
                    @foreach ($featuredDistricts as $district)
                        <a href="{{ route('districts.show', $district->slug) }}" class="group flex items-center gap-2 text-sm text-slate-600 transition hover:text-ink-950">
                            <span class="h-1 w-1 rounded-full bg-gold"></span>
                            <span class="truncate">{{ $district->name }}</span>
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('districts.index') }}" class="mt-7 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-ink-800 transition hover:gap-3">
                    All districts
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Membership"
                title="Find your place in the game."
                copy="Categories, fees and approval requirements are set by the association. Every application is reviewed against the published criteria."
            >
                <x-slot:actions>
                    <a href="{{ route('membership') }}" class="btn btn-secondary btn-pill">Compare categories</a>
                </x-slot:actions>
            </x-public.section-heading>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($membershipCategories as $category)
                    <article class="luxe-card group flex flex-col p-6 transition duration-300 ease-swift hover:-translate-y-1">
                        <span class="absolute inset-x-0 top-0 h-0.5" style="background: linear-gradient(90deg, var(--brand), var(--color-gold))"></span>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="badge badge-slate">{{ $category->billingPeriodLabel() }}</span>
                            <span class="badge {{ $category->requires_approval ? 'badge-amber' : 'badge-green' }}">{{ $category->requires_approval ? 'Approval' : 'Instant' }}</span>
                        </div>
                        <h3 class="mt-5 font-display text-lg font-semibold text-ink-950">{{ $category->name }}</h3>
                        <p class="mt-2 flex-1 text-sm leading-relaxed text-slate-600">{{ $category->description ?: $category->billingPeriodLabel().' membership for the association.' }}</p>
                        <div class="mt-6 border-t border-line pt-4">
                            <p class="font-display text-2xl font-semibold tracking-tight text-ink-950">{{ $category->feeLabel() }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $category->requires_approval ? 'Reviewed by the association' : 'No approval step configured' }}</p>
                        </div>
                        <a href="{{ route('register') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-[0.14em] transition group-hover:gap-2.5" style="color: var(--brand-deep)">
                            How to register
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </article>
                @empty
                    <div class="luxe-card p-10 text-center sm:col-span-2 lg:col-span-4">
                        <p class="font-display text-lg font-semibold text-ink-950">Membership categories are being configured.</p>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">Categories and fees will appear here once published by {{ $shortName }}.</p>
                    </div>
                @endforelse
            </div>

            <p class="mt-8 text-xs leading-relaxed text-slate-400">
                Fees and approval rules shown are configured by the association. Registration is completed through the association office.
            </p>
        </div>
    </section>

    <section class="border-y border-line bg-white py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="From the court"
                title="The championship, in frames."
                copy="Four days at Tatya Tope Stadium — state squads, opening ceremony and the people behind the game."
            >
                <x-slot:actions>
                    <a href="{{ route('gallery.index') }}" class="btn btn-secondary btn-pill">Full gallery</a>
                </x-slot:actions>
            </x-public.section-heading>

            <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (array_slice($gallery, 0, 5) as $photo)
                    <a href="{{ route('gallery.index') }}" class="gallery-tile {{ $loop->first ? 'sm:col-span-2 lg:row-span-2' : '' }} {{ $loop->first ? 'aspect-[4/3] lg:aspect-auto lg:min-h-[28rem]' : 'aspect-[4/3]' }}">
                        <x-public.photo
                            :name="$photo['name']"
                            :alt="$photo['alt']"
                            :width="1200"
                            :height="800"
                            :small-width="600"
                            sizes="{{ $loop->first ? '(min-width: 1024px) 50vw, 100vw' : '(min-width: 1024px) 25vw, 50vw' }}"
                            class="h-full w-full object-cover"
                        />
                        <span class="absolute inset-x-0 bottom-0 z-10 p-4 text-[10px] font-bold uppercase tracking-[0.16em] text-white/80">{{ $photo['caption'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="paper-grid py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Latest"
                title="News from the association."
                copy="Verified notices and championship updates published by the association office."
            >
                <x-slot:actions>
                    <a href="{{ route('news.index') }}" class="btn btn-secondary btn-pill">All news &amp; notices</a>
                </x-slot:actions>
            </x-public.section-heading>

            <div class="mt-12 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
                <a href="{{ route('news.index') }}" class="luxe-card group grid overflow-hidden sm:grid-cols-[0.9fr_1.1fr]">
                    <div class="relative aspect-[4/3] overflow-hidden bg-ink-900 sm:aspect-auto">
                        <x-public.photo
                            name="ceremony"
                            alt="Opening ceremony at the 2025 National Targetball Championship"
                            :width="1400"
                            :height="933"
                            :small-width="700"
                            sizes="(min-width: 1024px) 30vw, 100vw"
                            class="h-full w-full object-cover transition duration-700 group-hover:scale-[1.04]"
                        />
                    </div>
                    <div class="flex flex-col p-6 sm:p-8">
                        <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold uppercase tracking-[0.16em]">
                            <span class="text-gold">Championship</span>
                            <span class="text-slate-400">{{ \Illuminate\Support\Carbon::parse($story['date'])->format('j F Y') }}</span>
                        </div>
                        <h3 class="display-serif mt-4 text-2xl leading-tight text-ink-950 sm:text-3xl">{{ $story['title'] }}</h3>
                        <p class="mt-4 flex-1 text-sm leading-relaxed text-slate-600">{{ $story['excerpt'] }}</p>
                        <span class="mt-7 inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.16em] text-ink-950 transition group-hover:gap-3">
                            Read the update
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </span>
                    </div>
                </a>

                <div class="luxe-panel photo-grain flex flex-col p-7 sm:p-8">
                    <p class="eyebrow-luxe text-gold">Official channels</p>
                    <h3 class="display-serif mt-4 text-3xl text-white">Follow the game.</h3>
                    <p class="mt-3 text-sm leading-relaxed text-white/55">Association announcements, championship photography and national updates.</p>

                    <div class="mt-7 space-y-2">
                        @if ($instagram)
                            <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-4 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                                <span>Instagram &middot; {{ $shortName }}</span>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                            </a>
                        @endif
                        @if ($nationalBody)
                            <a href="{{ $nationalBody }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-4 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                                <span>Targetball India</span>
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                            </a>
                        @endif
                        <a href="{{ route('results.index') }}" class="flex items-center justify-between gap-4 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                            <span>Results &amp; standings</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                        </a>
                        <a href="{{ route('downloads.index') }}" class="flex items-center justify-between gap-4 rounded-xl border border-white/12 px-4 py-3 text-sm text-white/75 transition hover:border-gold/40 hover:bg-white/5 hover:text-white">
                            <span>Forms &amp; downloads</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                        </a>
                    </div>

                    <p class="mt-auto pt-7 text-[10px] font-bold uppercase tracking-[0.16em] text-white/30">Published by the association office</p>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Take part"
        title="Be part of the state's Targetball story."
        copy="Whether you play, coach, officiate or run a district body, there is a place for you in the association."
    >
        <a href="{{ route('register') }}" class="btn btn-gold btn-lg btn-pill">
            Join the association
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Contact the office</a>
    </x-public.cta-band>
@endsection
