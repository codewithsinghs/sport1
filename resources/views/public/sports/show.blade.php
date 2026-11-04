@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $disciplineCount = $sport->disciplines->count();
    $isTargetball = $sport->slug === 'targetball';
    $photo = $isTargetball ? 'championship-team' : 'hero-stadium';
    $facts = \App\Modules\PublicSite\Support\PublicContent::gameFacts();
@endphp

@section('title', $sport->name.' — '.$shortName)
@section('description', $sport->description ?: $sport->name.' disciplines, categories and membership pathway for '.$association->name.'.')
@section('og_image', asset('images/site/'.$photo.'.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Sports programme"
        :title="$sport->name"
        :intro="$sport->description ?: 'The '.$sport->name.' programme configured by '.$shortName.' across '.$stateName.'.'"
        :image="$photo"
        :image-alt="$sport->name.' competition at the 2025 National Championship'"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        :badge="'Enabled for '.$shortName"
        compact
        :breadcrumb="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Sports programme', 'url' => route('sports.index')],
            ['label' => $sport->name],
        ]"
    >
        <x-slot:actions>
            <a href="{{ route('membership') }}" class="btn btn-gold btn-pill">Membership</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-pill">Contact the office</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Disciplines</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($disciplineCount) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Categories</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($categories->count()) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">District reach</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($districtCount) }}</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Sport structure"
                title="Disciplines and categories."
                :copy="'The '.$shortName.' configures the competitive structure for '.$sport->name.'. Categories define the age and gender groups used for competition entries.'"
            />

            @if ($sport->disciplines->isNotEmpty())
                <div class="mt-12 space-y-6">
                    @foreach ($sport->disciplines as $discipline)
                        <article class="luxe-card">
                            <div class="flex flex-col gap-4 border-b border-line px-6 py-6 sm:flex-row sm:items-start sm:justify-between sm:px-8">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h3 class="display-serif display-serif-md text-ink-950">{{ $discipline->name }}</h3>
                                        <span class="badge badge-brand">Discipline</span>
                                    </div>
                                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-slate-600">
                                        {{ $discipline->description ?: 'Competition discipline configured by the association.' }}
                                    </p>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-gold">
                                    {{ number_format($discipline->categories->count()) }} {{ \Illuminate\Support\Str::plural('category', $discipline->categories->count()) }}
                                </span>
                            </div>

                            @if ($discipline->categories->isNotEmpty())
                                <div class="grid gap-px bg-line sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($discipline->categories as $category)
                                        <div class="bg-white px-6 py-5">
                                            <p class="text-sm font-semibold text-ink-950">{{ $category->name }}</p>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                <span class="badge badge-slate">{{ $category->genderLabel() }}</span>
                                                <span class="badge badge-slate">{{ $category->ageLabel() }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="px-6 py-6 text-sm text-slate-500 sm:px-8">Categories for this discipline are being configured.</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-12 rounded-3xl border border-dashed border-line bg-white p-10 text-center">
                    <p class="font-display text-lg font-semibold text-ink-950">Disciplines are being configured.</p>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                        Discipline and category information is published here once the association enables it.
                    </p>
                </div>
            @endif
        </div>
    </section>

    @if ($isTargetball)
        <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-[1fr_0.9fr] lg:gap-16">
                    <div>
                        <p class="eyebrow-luxe" style="color: var(--brand-deep)">The game</p>
                        <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Six a side, on a 30 × 20 metre court.</h2>
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600">
                            Targetball was formalised by Dr. Sonu Sharma in Mathura on 25 May 2012, drawing on a traditional game played in the Braj region. The modern game is contested by two teams of six, with diamond zones framing each end line and two goal circles per end.
                        </p>
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-500">
                            The association publishes its own competition structure for {{ $stateName }} through the disciplines and categories above.
                        </p>

                        <div class="mt-8 grid gap-3 sm:grid-cols-2">
                            @foreach ($facts as $fact)
                                <div class="rounded-xl border border-line bg-white px-5 py-4">
                                    <p class="display-serif text-2xl text-gold">{{ $fact['value'] }}</p>
                                    <p class="mt-1 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $fact['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <figure class="relative overflow-hidden rounded-[2rem] border border-line shadow-lift">
                        <x-public.photo
                            name="championship-lineup"
                            alt="State teams lined up at the National Targetball Championship 2025"
                            :width="1400"
                            :height="933"
                            :small-width="700"
                            sizes="(min-width: 1024px) 42vw, 100vw"
                            class="aspect-[4/3] w-full object-cover"
                        />
                        <div class="photo-veil-soft absolute inset-0"></div>
                        <figcaption class="absolute bottom-4 left-4 right-4 rounded-xl border border-white/15 bg-ink-950/70 px-4 py-3 text-xs text-white/80 backdrop-blur">
                            National Targetball Championship 2025 &middot; Bhopal
                        </figcaption>
                    </figure>
                </div>
            </div>
        </section>
    @endif

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Competition publication</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">What is published, and when.</h2>
                    <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600">
                        The portal publishes competition information only after the association approves it. Sports
                        structure is available now; fixtures, entries and results appear on the competition pages as they are confirmed.
                    </p>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-slate-500">
                        Nothing on this page represents an entry opening, schedule or result that the association has
                        not confirmed.
                    </p>
                </div>

                <div class="overflow-hidden rounded-2xl border border-line bg-white">
                    @foreach ([
                        ['label' => 'Disciplines & categories', 'status' => 'Published', 'tone' => 'badge-green'],
                        ['label' => 'Event calendar', 'status' => 'Published', 'tone' => 'badge-green'],
                        ['label' => 'Fixtures & schedules', 'status' => 'Awaiting confirmation', 'tone' => 'badge-amber'],
                        ['label' => 'Approved results', 'status' => 'Pending verification', 'tone' => 'badge-amber'],
                    ] as $row)
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-line px-5 py-4 last:border-0 sm:px-6">
                            <p class="text-sm font-semibold text-ink-950">{{ $row['label'] }}</p>
                            <span class="badge {{ $row['tone'] }}">{{ $row['status'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="border-t border-line bg-cream/60 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Membership pathway"
                title="Register through the association."
                copy="Membership categories are set by the association. Fees and approval requirements shown are the current configured values."
            >
                <x-slot:actions>
                    <a href="{{ route('membership') }}" class="btn btn-brand btn-pill">Compare categories</a>
                </x-slot:actions>
            </x-public.section-heading>

            @if ($membershipCategories->isNotEmpty())
                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($membershipCategories as $category)
                        <a
                            href="{{ $association->email ? 'mailto:'.$association->email.'?subject='.rawurlencode($category->name.' enquiry — '.$sport->name) : route('contact') }}"
                            class="luxe-card group p-5 transition duration-300 ease-swift hover:-translate-y-0.5"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-ink-950">{{ $category->name }}</p>
                                <span class="badge badge-slate">{{ $category->billingPeriodLabel() }}</span>
                            </div>
                            <p class="mt-4 font-display text-xl font-semibold text-ink-950">{{ $category->feeLabel() }}</p>
                            <p class="mt-2 text-xs text-slate-500">{{ $category->requires_approval ? 'Association approval required' : 'No approval step configured' }}</p>
                        </a>
                    @endforeach
                </div>
                <p class="mt-6 text-xs leading-relaxed text-slate-500">
                    Registration is completed through the association office. Online application and payment are enabled when the membership module launches.
                </p>
            @else
                <p class="mt-10 rounded-2xl border border-dashed border-line bg-white p-8 text-sm text-slate-500">
                    Membership categories are being configured by the association.
                </p>
            @endif
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Questions"
        :title="'Questions about '.$sport->name.'?'"
        copy="The association office can help with membership, district participation and competition information."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">Contact the association</a>
        <a href="{{ route('districts.index') }}" class="btn btn-outline-light btn-lg btn-pill">Find your district</a>
    </x-public.cta-band>
@endsection
