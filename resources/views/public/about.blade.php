@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $about = $association->setting('about');
    $firstSport = $sports->first();
@endphp

@section('title', 'About the association — '.$shortName)
@section('description', $about ?: 'About '.$association->name.', the state association for Targetball across '.$stateName.'.')
@section('og_image', asset('images/site/championship-team.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="About the association"
        :title="'The state body for Targetball in '.$stateName.'.'"
        :intro="$about ?: $association->name.' is the official association for Targetball across '.$stateName.', connecting districts, members and the competition programme.'"
        image="championship-team"
        image-alt="Team captains and officials at the 2025 National Targetball Championship in Bhopal"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="One association · Every district"
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'About']]"
    >
        <x-slot:actions>
            @if ($firstSport)
                <a href="{{ route('sports.show', $firstSport->slug) }}" class="btn btn-gold btn-lg btn-pill">
                    Explore {{ $firstSport->name }}
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @endif
            <a href="{{ route('districts.index') }}" class="btn btn-outline-light btn-lg btn-pill">District directory</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Districts</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($districtCount) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Sports</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($sports->count()) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Member types</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($membershipCategories->count()) }}</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="py-20 lg:py-28">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16 lg:px-8">
            <figure class="relative order-2 lg:order-1">
                <div class="relative aspect-[4/3] overflow-hidden rounded-[2rem] border border-line shadow-lift">
                    <x-public.photo
                        name="championship-lineup"
                        alt="State contingents lined up at the National Targetball Championship 2025"
                        :width="1400"
                        :height="933"
                        :small-width="700"
                        sizes="(min-width: 1024px) 45vw, 100vw"
                    />
                    <div class="photo-veil-soft absolute inset-0"></div>
                </div>
                <div class="absolute -bottom-6 left-5 right-5 rounded-2xl border border-line bg-white px-5 py-4 shadow-soft sm:right-auto sm:max-w-xs">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Hosted in 2025</p>
                    <p class="mt-1 font-display text-lg font-semibold text-ink-950">National championship, Bhopal</p>
                </div>
            </figure>

            <div class="order-1 lg:order-2">
                <p class="eyebrow-luxe" style="color: var(--brand-deep)">Who we are</p>
                <h2 class="display-serif display-serif-lg mt-5 text-ink-950">One association for the whole state.</h2>

                <div class="mt-6 space-y-4 text-base leading-relaxed text-slate-600">
                    <p>
                        {{ $association->name }} is the official state association for Targetball across {{ $stateName }}.
                        The association coordinates the sport's district network and maintains the membership and
                        competition records published on this portal.
                    </p>
                    <p>
                        This site is the association's public reference: the districts it covers, the sport it governs,
                        the membership categories it offers, and how to reach the association office.
                    </p>
                    <p class="border-l-2 border-gold pl-4 text-sm text-slate-500">
                        Appointments, confirmed policies and official governance records are published only after the
                        association approves them.
                    </p>
                </div>

                <ul class="mt-8 grid gap-3 sm:grid-cols-2">
                    @foreach (['District coordination', 'Membership records', 'Competition programme', 'Official communication'] as $item)
                        <li class="flex items-center gap-3 rounded-xl border border-line bg-white px-4 py-3 text-sm font-medium text-slate-700">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-cream text-gold">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4 10-10"/></svg>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="What the association does"
                title="Structure behind the sport."
                copy="Three areas of work connect the state association, its districts and everyone who plays the game."
            />

            <div class="mt-12 grid gap-5 md:grid-cols-3">
                @php
                    $work = [
                        [
                            'number' => '01',
                            'title' => 'District coordination',
                            'copy' => $districtCount > 0
                                ? 'A state network spanning '.number_format($districtCount).' districts, giving local bodies a single point of contact.'
                                : 'A state network that gives local bodies a single point of contact.',
                        ],
                        [
                            'number' => '02',
                            'title' => 'Membership & records',
                            'copy' => $membershipCategories->isNotEmpty()
                                ? number_format($membershipCategories->count()).' membership '.\Illuminate\Support\Str::plural('category', $membershipCategories->count()).' with fees and approval requirements set by the association.'
                                : 'Membership categories with fees and approval requirements set by the association.',
                        ],
                        [
                            'number' => '03',
                            'title' => 'Competition & results',
                            'copy' => $sports->isNotEmpty()
                                ? 'A published '.$firstSport->name.' programme with '.number_format($disciplineCount).' '.\Illuminate\Support\Str::plural('discipline', $disciplineCount).' and '.number_format($categoryCount).' competition '.\Illuminate\Support\Str::plural('category', $categoryCount).'.'
                                : 'A published sports programme with disciplines and competition categories.',
                        ],
                    ];
                @endphp

                @foreach ($work as $item)
                    <article class="luxe-card bg-white p-7">
                        <p class="kicker-number">{{ $item['number'] }}</p>
                        <h3 class="mt-6 font-display text-xl font-semibold text-ink-950">{{ $item['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $item['copy'] }}</p>
                        <span class="mt-7 block h-px w-12 bg-gold/60"></span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="The journey"
                title="A game formalised, a programme built."
                copy="Targetball's modern chapter began in 2012. The state association now carries that programme across Madhya Pradesh."
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
        </div>
    </section>

    <section class="pb-20 lg:pb-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="luxe-panel photo-grain grid gap-10 p-7 sm:p-10 lg:grid-cols-[1fr_0.9fr] lg:items-center">
                <div class="relative">
                    <p class="eyebrow-luxe text-gold">Coverage</p>
                    <h2 class="display-serif display-serif-lg mt-4 text-white">From Bhopal to every district.</h2>
                    <p class="mt-5 max-w-xl text-sm leading-relaxed text-white/60 sm:text-base">
                        The association's district master currently lists {{ number_format($districtCount) }} active
                        {{ \Illuminate\Support\Str::plural('district', $districtCount) }} across {{ $stateName }}. Each district
                        has a public page in the directory.
                    </p>

                    @if ($featuredDistricts->isNotEmpty())
                        <div class="mt-8 flex flex-wrap gap-2">
                            @foreach ($featuredDistricts as $district)
                                <a href="{{ route('districts.show', $district->slug) }}" class="rounded-full border border-white/12 bg-white/[0.06] px-3 py-1.5 text-xs font-medium text-white/70 transition hover:border-gold/50 hover:text-white">
                                    {{ $district->name }}
                                </a>
                            @endforeach
                            <a href="{{ route('districts.index') }}" class="rounded-full px-3 py-1.5 text-xs font-semibold text-gold underline decoration-gold/40 underline-offset-4 transition hover:text-white">
                                All {{ number_format($districtCount) }} districts
                            </a>
                        </div>
                    @endif
                </div>

                <figure class="relative overflow-hidden rounded-2xl border border-white/12">
                    <x-public.photo
                        name="india-champions"
                        alt="Indian team celebrating with the trophy at an international targetball event"
                        :width="1600"
                        :height="900"
                        :small-width="800"
                        sizes="(min-width: 1024px) 40vw, 100vw"
                        class="aspect-[4/3] w-full object-cover"
                    />
                    <figcaption class="absolute bottom-3 left-3 rounded-full bg-ink-950/70 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/70 backdrop-blur-md">
                        India on the international stage
                    </figcaption>
                </figure>
            </div>
        </div>
    </section>

    <section class="border-t border-line bg-white py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">The archive</p>
                    <h2 class="display-serif display-serif-md mt-4 text-ink-950">National championship 2025.</h2>
                </div>
                <a href="{{ route('gallery.index') }}" class="link-brand text-sm">Open the gallery</a>
            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($gallery as $photo)
                    <a href="{{ route('gallery.index') }}" class="gallery-tile aspect-[4/3]">
                        <x-public.photo
                            :name="$photo['name']"
                            :alt="$photo['alt']"
                            :width="1200"
                            :height="800"
                            :small-width="600"
                            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                        />
                        <span class="absolute inset-x-0 bottom-0 z-10 p-4 text-xs font-semibold text-white">{{ $photo['caption'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Governance"
        title="See how the association is structured."
        copy="Office bearers, committees and district coordination are documented in the governance section."
    >
        <a href="{{ route('governance') }}" class="btn btn-gold btn-lg btn-pill">Governance &amp; office bearers</a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Contact the office</a>
    </x-public.cta-band>
@endsection
