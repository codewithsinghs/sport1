@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $about = $association->setting('about');
    $firstSport = $sports->first();
    $otherSports = $sports->slice(1);
@endphp

@section('title', $association->name)
@section('description', $about ?: $association->name.' official portal')

@section('content')
    <section class="court-grid relative isolate overflow-hidden bg-ink-950 text-white">
        <div class="pointer-events-none absolute -left-40 top-[-12rem] h-[32rem] w-[32rem] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, var(--brand) 0%, transparent 68%)"></div>
        <div class="pointer-events-none absolute -right-32 bottom-[-16rem] h-[30rem] w-[30rem] rounded-full opacity-20 blur-3xl" style="background: radial-gradient(circle, var(--brand-accent) 0%, transparent 70%)"></div>
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/25 to-transparent"></div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-14 px-4 py-16 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:gap-10 lg:px-8 lg:py-24">
            <div class="max-w-2xl">
                <p class="reveal eyebrow text-white/55">
                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--brand-accent)"></span>
                    Official state association &middot; {{ number_format($districtCount) }} districts
                </p>

                <h1 class="reveal reveal-1 display-xl mt-6">
                    {{ $association->name }}
                </h1>

                <p class="reveal reveal-2 mt-6 max-w-xl text-base leading-relaxed text-white/65 sm:text-lg">
                    {{ $about ?: 'The official home of '.$shortName.' — district network, membership, and the sports we govern across '.$stateName.'.' }}
                </p>

                <div class="reveal reveal-3 mt-9 flex flex-wrap items-center gap-3">
                    <a href="#sport" class="btn btn-light btn-lg btn-pill">
                        Explore the sport
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-lg btn-pill border border-white/20 text-white transition hover:bg-white/10">
                        Member login
                    </a>
                </div>

                <dl class="reveal reveal-4 mt-12 grid max-w-xl grid-cols-3 gap-3">
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Districts</dt>
                        <dd class="stat-value mt-2 text-white">{{ number_format($districtCount) }}</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Sports</dt>
                        <dd class="stat-value mt-2 text-white">{{ number_format($sports->count()) }}</dd>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Member types</dt>
                        <dd class="stat-value mt-2 text-white">{{ number_format($membershipCategories->count()) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="reveal reveal-2 relative mx-auto w-full max-w-md lg:max-w-none">
                <div class="relative aspect-square">
                    <div class="absolute inset-6 rounded-full border border-white/10"></div>
                    <x-target-motif class="mx-auto h-full w-full text-white" />

                    @if ($firstSport)
                        <div class="absolute left-0 top-10 rounded-2xl border border-white/12 bg-ink-900/80 px-4 py-3 backdrop-blur-md">
                            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Sport</p>
                            <p class="mt-1 font-display text-sm font-semibold text-white">{{ $firstSport->name }}</p>
                        </div>
                    @endif

                    <div class="absolute bottom-10 right-0 rounded-2xl border border-white/12 bg-ink-900/80 px-4 py-3 backdrop-blur-md">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Statewide</p>
                        <p class="mt-1 font-display text-sm font-semibold text-white">{{ $stateName }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="marquee border-y border-line bg-white py-4" aria-hidden="true">
        <div class="marquee-track">
            @foreach ([$shortName, $stateName, $firstSport?->name ?? 'Sports programme', 'Membership', 'District network', 'Governance'] as $item)
                <span class="flex items-center gap-6 px-6 font-display text-sm font-medium uppercase tracking-[0.2em] text-slate-400">
                    {{ $item }}
                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--brand-accent)"></span>
                </span>
            @endforeach
            @foreach ([$shortName, $stateName, $firstSport?->name ?? 'Sports programme', 'Membership', 'District network', 'Governance'] as $item)
                <span class="flex items-center gap-6 px-6 font-display text-sm font-medium uppercase tracking-[0.2em] text-slate-400">
                    {{ $item }}
                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--brand-accent)"></span>
                </span>
            @endforeach
        </div>
    </div>

    <section id="association" class="paper-grid relative py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[1fr_1.4fr] lg:items-end">
                <div>
                    <p class="eyebrow" style="color: var(--brand-deep)">The association</p>
                    <h2 class="display-lg mt-4 text-ink-950">Built for every district.<br>Focused on the game.</h2>
                </div>
                <p class="max-w-xl text-base leading-relaxed text-slate-600 lg:justify-self-end">
                    {{ $shortName }} brings the state's districts, clubs, officials and players onto one organised platform — clear membership pathways, a single sports programme, and association-level administration.
                </p>
            </div>

            <div class="mt-14 grid gap-5 md:grid-cols-3">
                @php
                    $pillars = [
                        [
                            'number' => '01',
                            'title' => 'District network',
                            'copy' => $districtCount > 0
                                ? 'Coverage across '.number_format($districtCount).' districts of '.$stateName.', connecting local bodies to the state association.'
                                : 'A district network connecting local bodies to the state association.',
                        ],
                        [
                            'number' => '02',
                            'title' => 'Membership',
                            'copy' => $membershipCategories->isNotEmpty()
                                ? $membershipCategories->count().' '.\Illuminate\Support\Str::plural('category', $membershipCategories->count()).' with fees and approval rules set by the association.'
                                : 'Membership categories with fees and approval rules set by the association.',
                        ],
                        [
                            'number' => '03',
                            'title' => 'Competition',
                            'copy' => $sports->isNotEmpty()
                                ? 'A configured sports programme with disciplines, categories, and competition administration.'
                                : 'A configurable sports programme with disciplines, categories, and competition administration.',
                        ],
                    ];
                @endphp

                @foreach ($pillars as $pillar)
                    <article class="group relative overflow-hidden rounded-3xl border border-line bg-white p-6 shadow-soft transition duration-300 ease-swift hover:-translate-y-1 hover:shadow-lift sm:p-7">
                        <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full border transition duration-500 group-hover:scale-110" style="border-color: var(--brand-ring)"></div>
                        <p class="font-display text-sm font-semibold tracking-[0.2em]" style="color: var(--brand-deep)">{{ $pillar['number'] }}</p>
                        <h3 class="mt-6 text-xl font-semibold text-ink-950">{{ $pillar['title'] }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $pillar['copy'] }}</p>
                        <span class="mt-8 block h-px w-12 transition-all duration-500 group-hover:w-20" style="background: var(--brand)"></span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="sport" class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow" style="color: var(--brand-deep)">Sports programme</p>
                    <h2 class="display-lg mt-4 text-ink-950">The game we govern.</h2>
                </div>
                <p class="text-sm text-slate-500">
                    {{ $sports->count() }} {{ \Illuminate\Support\Str::plural('sport', $sports->count()) }} enabled &middot; {{ $stateName }}
                </p>
            </div>

            @if ($firstSport)
                <article class="surface-dark mt-12 grid gap-8 p-7 sm:p-10 lg:grid-cols-[1.35fr_0.65fr] lg:items-center">
                    <div class="relative z-10">
                        <span class="badge border border-white/15 bg-white/10 text-white/80">Enabled for {{ $shortName }}</span>
                        <h3 class="mt-6 font-display text-4xl font-semibold tracking-tight text-white sm:text-5xl">{{ $firstSport->name }}</h3>
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/65 sm:text-base">
                            {{ $firstSport->description ?: 'The association\'s flagship programme, organised across the district network with age and gender categories configured by the state body.' }}
                        </p>

                        <div class="mt-8 flex flex-wrap gap-2">
                            <span class="rounded-full border border-white/12 bg-white/[0.06] px-3 py-1.5 text-xs font-medium text-white/70">
                                {{ number_format($disciplineCount) }} {{ \Illuminate\Support\Str::plural('discipline', $disciplineCount) }}
                            </span>
                            <span class="rounded-full border border-white/12 bg-white/[0.06] px-3 py-1.5 text-xs font-medium text-white/70">
                                {{ number_format($categoryCount) }} {{ \Illuminate\Support\Str::plural('category', $categoryCount) }}
                            </span>
                            <span class="rounded-full border border-white/12 bg-white/[0.06] px-3 py-1.5 text-xs font-medium text-white/70">
                                {{ number_format($districtCount) }} district reach
                            </span>
                        </div>
                    </div>

                    <div class="relative z-10 mx-auto w-full max-w-[15rem] lg:max-w-none">
                        <x-target-motif class="w-full text-white" />
                        <span class="absolute bottom-2 right-0 rounded-full border border-white/12 bg-ink-950/70 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-white/60">
                            Official programme
                        </span>
                    </div>
                </article>

                @if ($otherSports->isNotEmpty())
                    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($otherSports as $sport)
                            <article class="card card-body group">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl font-display text-sm font-bold" style="background: var(--brand-soft); color: var(--brand-deep)">
                                        {{ mb_strtoupper(mb_substr($sport->name, 0, 1)) }}
                                    </span>
                                    <span class="badge badge-brand">Enabled</span>
                                </div>
                                <h3 class="mt-5 text-lg font-semibold text-ink-950">{{ $sport->name }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $sport->description ?: 'Sport programme configured by the association.' }}</p>
                            </article>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="mt-12 rounded-3xl border border-dashed border-line bg-white p-10 text-center">
                    <p class="font-display text-lg font-semibold text-ink-950">The sports programme is being configured.</p>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                        Sports, disciplines and categories are published here once the association enables them.
                    </p>
                </div>
            @endif
        </div>
    </section>

    <section id="membership" class="border-y border-line bg-white py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow" style="color: var(--brand-deep)">Membership</p>
                    <h2 class="display-lg mt-4 text-ink-950">Find your place in the game.</h2>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-slate-500">
                    Categories, fees and approval requirements are configured by {{ $shortName }}.
                </p>
            </div>

            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($membershipCategories as $category)
                    <article class="group relative flex flex-col overflow-hidden rounded-3xl border border-line bg-paper p-6 transition duration-300 ease-swift hover:-translate-y-1 hover:border-slate-300 hover:bg-white hover:shadow-lift">
                        <span class="absolute inset-x-0 top-0 h-1" style="background: linear-gradient(90deg, var(--brand), var(--brand-accent))"></span>

                        <div class="flex flex-wrap items-center gap-2">
                            <span class="badge badge-slate">{{ $category->billingPeriodLabel() }}</span>
                            <span class="badge {{ $category->requires_approval ? 'badge-amber' : 'badge-green' }}">
                                {{ $category->requires_approval ? 'Approval' : 'Instant' }}
                            </span>
                        </div>

                        <h3 class="mt-5 text-lg font-semibold text-ink-950">{{ $category->name }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $category->description ?: $category->billingPeriodLabel().' membership for the association.' }}</p>

                        <div class="mt-6 border-t border-line pt-4">
                            <p class="font-display text-2xl font-semibold tracking-tight text-ink-950">{{ $category->feeLabel() }}</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $category->requires_approval ? 'Application reviewed by the association' : 'No approval step configured' }}
                            </p>
                        </div>

                        @php
                            $subject = rawurlencode($category->name.' membership enquiry');
                        @endphp
                        <a
                            href="{{ $association->email ? 'mailto:'.$association->email.'?subject='.$subject : '#contact' }}"
                            class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold transition group-hover:gap-2.5"
                            style="color: var(--brand-deep)"
                        >
                            Register interest
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </a>
                    </article>
                @empty
                    <div class="rounded-3xl border border-dashed border-line bg-paper p-10 text-center sm:col-span-2 lg:col-span-4">
                        <p class="font-display text-lg font-semibold text-ink-950">Membership categories are being configured.</p>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                            Categories and fees will appear here once published by {{ $shortName }}.
                        </p>
                    </div>
                @endforelse
            </div>

            <p class="mt-8 text-xs leading-relaxed text-slate-400">
                Fees and approval rules shown are configured by the association. Registration is completed through the association office.
            </p>
        </div>
    </section>

    <section id="contact" class="relative overflow-hidden py-20 lg:py-28">
        <div class="pointer-events-none absolute -left-24 top-10 h-72 w-72 rounded-full border opacity-40" style="border-color: var(--brand-ring)"></div>
        <div class="relative mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-[1fr_1.1fr] lg:items-start lg:px-8">
            <div>
                <p class="eyebrow" style="color: var(--brand-deep)">Contact</p>
                <h2 class="display-lg mt-4 text-ink-950">Talk to the association office.</h2>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-600">
                    For membership, district affiliation, competition and general association information, contact {{ $shortName }} directly.
                </p>

                @if ($association->email)
                    <a href="mailto:{{ $association->email }}" class="btn btn-brand btn-lg btn-pill mt-8">
                        Email the association
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4zM4 7l8 6 8-6" />
                        </svg>
                    </a>
                @else
                    <p class="mt-8 rounded-2xl border border-dashed border-line bg-white px-5 py-4 text-sm text-slate-500">
                        Contact details are being configured by the association.
                    </p>
                @endif
            </div>

            <dl class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-line bg-white p-5 shadow-soft">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Office</dt>
                    <dd class="mt-3 text-sm leading-relaxed text-slate-700">{{ $association->address ?: 'Address not configured' }}</dd>
                </div>
                <div class="rounded-2xl border border-line bg-white p-5 shadow-soft">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Phone</dt>
                    <dd class="mt-3 text-sm leading-relaxed text-slate-700">
                        @if ($association->phone)
                            <a href="tel:{{ $association->phone }}" class="link-brand">{{ $association->phone }}</a>
                        @else
                            Not configured
                        @endif
                    </dd>
                </div>
                <div class="rounded-2xl border border-line bg-white p-5 shadow-soft">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Email</dt>
                    <dd class="mt-3 break-words text-sm leading-relaxed text-slate-700">
                        @if ($association->email)
                            <a href="mailto:{{ $association->email }}" class="link-brand">{{ $association->email }}</a>
                        @else
                            Not configured
                        @endif
                    </dd>
                </div>
                <div class="rounded-2xl border border-line bg-white p-5 shadow-soft">
                    <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Website</dt>
                    <dd class="mt-3 break-words text-sm leading-relaxed text-slate-700">
                        @if ($association->website)
                            <a href="{{ $association->website }}" target="_blank" rel="noopener noreferrer" class="link-brand">{{ $association->website }}</a>
                        @else
                            Not configured
                        @endif
                    </dd>
                </div>
            </dl>
        </div>
    </section>
@endsection
