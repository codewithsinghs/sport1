@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $email = $association->email;
@endphp

@section('title', 'Register — '.$shortName)
@section('description', 'Expression of interest for membership with '.$association->name.'.')
@section('og_image', asset('images/site/championship-lineup.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Expression of interest"
        title="Join the association."
        intro="Membership begins with an expression of interest. The office will confirm the right category, eligibility and documents before any fee is collected."
        image="championship-lineup"
        image-alt="State teams lined up at the National Targetball Championship 2025"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Register']]"
    >
        <x-slot:actions>
            <a href="{{ route('membership') }}" class="btn btn-gold btn-lg btn-pill">Compare categories</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Talk to the office</a>
        </x-slot:actions>
    </x-public.page-hero>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">Before you apply</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Four steps to membership.</h2>
                    <p class="mt-5 max-w-md text-base leading-relaxed text-slate-600">
                        The association reviews every expression of interest against its membership categories. Approval and payment instructions follow only after eligibility is confirmed.
                    </p>

                    <ol class="mt-10 space-y-6">
                        @foreach ([
                            ['title' => 'Choose a category', 'copy' => 'Review the current categories, fees and approval requirements.'],
                            ['title' => 'Send your details', 'copy' => 'Write to the office with your name, district and the category you are interested in.'],
                            ['title' => 'Eligibility review', 'copy' => 'The association confirms your category and any supporting documents required.'],
                            ['title' => 'Complete membership', 'copy' => 'Payment instructions and the signed application follow from the office.'],
                        ] as $index => $step)
                            <li class="flex gap-4">
                                <span class="mt-0.5 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-gold/40 bg-white font-display text-xs font-semibold text-gold">{{ sprintf('%02d', $index + 1) }}</span>
                                <div>
                                    <h3 class="font-display text-base font-semibold text-ink-950">{{ $step['title'] }}</h3>
                                    <p class="mt-1 text-sm leading-relaxed text-slate-600">{{ $step['copy'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="rounded-[2rem] border border-line bg-white p-6 shadow-soft sm:p-9">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">How to register interest</p>
                    <h2 class="display-serif display-serif-md mt-3 text-ink-950">Write to the association office.</h2>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Include the following in your message so the office can respond accurately. Nothing is submitted or stored through this page.
                    </p>

                    <ul class="mt-6 space-y-3 text-sm text-slate-700">
                        @foreach ([
                            'Full name and contact number',
                            'District of residence or operation',
                            'Membership category of interest',
                            'Role — player, coach, official, institution or district body',
                            'Any relevant affiliation or participation history',
                        ] as $item)
                            <li class="flex gap-3">
                                <svg class="mt-0.5 h-4 w-4 shrink-0 text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @if ($email)
                            <a href="mailto:{{ $email }}?subject={{ rawurlencode('Membership expression of interest') }}" class="btn btn-brand btn-pill">
                                Email the office
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="btn btn-ghost btn-pill">Contact details</a>
                    </div>

                    <p class="mt-6 border-t border-line pt-5 text-xs leading-relaxed text-slate-500">
                        The association does not collect payments on this site. Always confirm the office contact and bank details before paying any fee.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @if ($membershipCategories->isNotEmpty())
        <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="eyebrow-luxe" style="color: var(--brand-deep)">Current categories</p>
                        <h2 class="display-serif display-serif-md mt-4 text-ink-950">The options at a glance.</h2>
                    </div>
                    <a href="{{ route('membership') }}" class="link-brand text-sm">See full details</a>
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($membershipCategories as $category)
                        <article class="luxe-card bg-white p-6">
                            <span class="badge badge-slate">{{ $category->billingPeriodLabel() }}</span>
                            <h3 class="mt-4 font-display text-lg font-semibold text-ink-950">{{ $category->name }}</h3>
                            <p class="mt-2 font-display text-xl font-semibold text-ink-950">{{ $category->feeLabel() }}</p>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">
                                {{ $category->requires_approval ? 'Association approval required' : 'No approval step configured' }}
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-public.cta-band
        eyebrow="Districts"
        title="Joining through a district body?"
        copy="District associations coordinate local members and events. Find your district page for the local route into the state programme."
    >
        <a href="{{ route('districts.index') }}" class="btn btn-gold btn-lg btn-pill">Find your district</a>
        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg btn-pill">About the association</a>
    </x-public.cta-band>
@endsection
