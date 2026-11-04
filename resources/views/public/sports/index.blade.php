@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $disciplineTotal = $sports->sum(fn ($item) => $item->disciplines->count());
    $categoryTotal = $sports->sum(fn ($item) => $item->disciplines->sum(fn ($discipline) => $discipline->categories->count()));
    $facts = \App\Modules\PublicSite\Support\PublicContent::gameFacts();
@endphp

@section('title', 'Sports programme — '.$shortName)
@section('description', 'The sports programme enabled by '.$association->name.', including Targetball disciplines and competition categories.')
@section('og_image', asset('images/site/championship-lineup.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Sports programme"
        title="The game we govern."
        :intro="'The '.$shortName.' programme is configured by the association. Each enabled sport carries its own disciplines, competition categories and membership pathway.'"
        image="championship-lineup"
        image-alt="State teams lined up at the National Targetball Championship 2025"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="Official programme"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Sports programme']]"
    >
        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Sports</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($sportCount) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Disciplines</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($disciplineTotal) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Categories</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($categoryTotal) }}</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="border-b border-line bg-cream/60 py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($facts as $fact)
                    <div class="rounded-2xl border border-line bg-white px-5 py-5">
                        <p class="display-serif text-3xl text-gold">{{ $fact['value'] }}</p>
                        <p class="mt-2 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $fact['label'] }}</p>
                        <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $fact['copy'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Enabled for the association"
                title="Sports programme."
                :copy="'Each sport page lists the disciplines and categories configured by the association. Coverage spans '.number_format($districtCount).' districts.'"
            />

            @if ($sports->isNotEmpty())
                <div class="mt-12 grid gap-6 lg:grid-cols-2">
                    @foreach ($sports as $sport)
                        @php
                            $sportDisciplines = $sport->disciplines->count();
                            $sportCategories = $sport->disciplines->sum(fn ($discipline) => $discipline->categories->count());
                            $sportPhoto = $sport->slug === 'targetball' ? 'championship-lineup' : 'hero-stadium';
                        @endphp

                        <article class="group overflow-hidden rounded-[2rem] border border-line bg-white shadow-soft transition duration-300 ease-swift hover:-translate-y-1 hover:shadow-lift">
                            <div class="relative aspect-[16/9] overflow-hidden">
                                <x-public.photo
                                    :name="$sportPhoto"
                                    :alt="$sport->name.' at the 2025 National Targetball Championship'"
                                    :width="1400"
                                    :height="933"
                                    :small-width="700"
                                    sizes="(min-width: 1024px) 48vw, 100vw"
                                    class="transition duration-700 group-hover:scale-[1.03]"
                                />
                                <div class="photo-veil-soft absolute inset-0"></div>
                                <span class="absolute left-4 top-4 rounded-full border border-white/15 bg-ink-950/65 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.16em] text-white/70 backdrop-blur-md">
                                    Enabled for {{ $shortName }}
                                </span>
                                <div class="absolute inset-x-5 bottom-5">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-gold">Official sport</p>
                                    <h3 class="display-serif mt-1 text-3xl text-white">{{ $sport->name }}</h3>
                                </div>
                            </div>

                            <div class="p-6 sm:p-7">
                                <p class="text-sm leading-relaxed text-slate-600">
                                    {{ $sport->description ?: 'Sport programme configured by the association.' }}
                                </p>

                                <div class="mt-6 flex flex-wrap gap-2">
                                    <span class="badge badge-slate">{{ number_format($sportDisciplines) }} {{ \Illuminate\Support\Str::plural('discipline', $sportDisciplines) }}</span>
                                    <span class="badge badge-slate">{{ number_format($sportCategories) }} {{ \Illuminate\Support\Str::plural('category', $sportCategories) }}</span>
                                    <span class="badge badge-brand">{{ number_format($districtCount) }} districts</span>
                                </div>

                                <a href="{{ route('sports.show', $sport->slug) }}" class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold uppercase tracking-[0.14em] text-ink-950 transition group-hover:gap-2.5 group-hover:text-gold">
                                    Explore {{ $sport->name }}
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
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

    <x-public.cta-band
        eyebrow="Take part"
        title="Want to compete or officiate?"
        copy="Membership categories cover players, officials and association members. The office can explain the route for your district."
    >
        <a href="{{ route('membership') }}" class="btn btn-gold btn-lg btn-pill">Membership categories</a>
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Contact the office</a>
    </x-public.cta-band>
@endsection
