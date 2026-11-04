@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $email = $association->email;
@endphp

@section('title', 'Membership — '.$shortName)
@section('description', 'Membership categories, fees and approval requirements set by '.$association->name.'.')
@section('og_image', asset('images/site/india-women.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Membership"
        title="Find your place in the game."
        :intro="'Categories are set by '.$shortName.' for players, officials and association members. Review the current fees and approval requirements below.'"
        image="india-women"
        image-alt="Indian women's targetball team with their trophy"
        :image-width="1600"
        :image-height="900"
        :image-small-width="800"
        badge="Configured by the association"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Membership']]"
    >
        <x-slot:actions>
            @if ($email)
                <a href="mailto:{{ $email }}?subject={{ rawurlencode('Membership enquiry') }}" class="btn btn-gold btn-lg btn-pill">Email the association</a>
            @endif
            <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg btn-pill">How to register</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Categories</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($membershipCategories->count()) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Districts</dt>
                <dd class="stat-value mt-2 text-white">{{ number_format($districtCount) }}</dd>
            </div>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Approval</dt>
                <dd class="mt-2 font-display text-lg font-semibold text-white">Association</dd>
            </div>
        </x-slot:stats>
    </x-public.page-hero>

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <x-public.section-heading
                eyebrow="Current categories"
                title="Membership options."
                copy="Fees, periods and approval requirements below are the current values configured by the association. The office confirms your final category and eligibility."
            />

            @if ($membershipCategories->isNotEmpty())
                <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($membershipCategories as $category)
                        <article class="luxe-card group flex flex-col p-6">
                            <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-gold via-gold/60 to-transparent"></span>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="badge badge-slate">{{ $category->billingPeriodLabel() }}</span>
                                <span class="badge {{ $category->requires_approval ? 'badge-amber' : 'badge-green' }}">
                                    {{ $category->requires_approval ? 'Approval' : 'Instant' }}
                                </span>
                            </div>

                            <h2 class="mt-6 font-display text-xl font-semibold text-ink-950">{{ $category->name }}</h2>
                            <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $category->description ?: $category->billingPeriodLabel().' membership for the association.' }}</p>

                            <div class="mt-6 border-t border-line pt-5">
                                <p class="font-display text-2xl font-semibold tracking-tight text-ink-950">{{ $category->feeLabel() }}</p>
                                <p class="mt-2 text-xs leading-relaxed text-slate-500">
                                    {{ $category->requires_approval ? 'Application reviewed by the association' : 'No approval step configured' }}
                                </p>
                            </div>

                            <a
                                href="{{ $email ? 'mailto:'.$email.'?subject='.rawurlencode($category->name.' membership enquiry') : route('contact') }}"
                                class="mt-auto inline-flex items-center gap-1.5 pt-6 text-sm font-semibold text-ink-950 transition group-hover:gap-2.5 group-hover:text-gold"
                            >
                                Register interest
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="mt-12 rounded-3xl border border-dashed border-line bg-white p-10 text-center">
                    <p class="font-display text-lg font-semibold text-ink-950">Membership categories are being configured.</p>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                        Categories and fees will appear here once published by {{ $shortName }}.
                    </p>
                </div>
            @endif

            <p class="mt-8 text-xs leading-relaxed text-slate-500">
                Fees and approval rules shown are configured by the association. Registration is completed through the association office.
            </p>
        </div>
    </section>

    <section class="border-y border-line bg-cream/60 py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-start lg:gap-16">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">How it works</p>
                    <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Start with the office.</h2>
                    <p class="mt-5 text-base leading-relaxed text-slate-600">
                        Registration currently runs through the association office so your category and eligibility can
                        be confirmed before any fee is collected. Online applications and payment will follow when the
                        membership module is released.
                    </p>

                    <div class="mt-8 rounded-2xl border border-line bg-white p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Before you pay</p>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Always confirm the office contact and bank details before transferring any fee. This portal does not collect payments.
                        </p>
                    </div>

                    @if ($email)
                        <a href="mailto:{{ $email }}?subject={{ rawurlencode('Membership registration') }}" class="btn btn-brand btn-lg btn-pill mt-8">
                            Email about membership
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4zM4 7l8 6 8-6"/></svg>
                        </a>
                    @endif
                </div>

                <ol class="space-y-4">
                    @foreach ([
                        ['step' => '01', 'title' => 'Choose a category', 'copy' => 'Review the categories above and select the one that matches your role.'],
                        ['step' => '02', 'title' => 'Contact the association', 'copy' => 'Write to the office with your district and the category you are applying for.'],
                        ['step' => '03', 'title' => 'Confirmation & payment', 'copy' => 'The office confirms eligibility, the applicable fee and how to complete registration.'],
                        ['step' => '04', 'title' => 'Membership recorded', 'copy' => 'Once approved, your membership is recorded by the association office.'],
                    ] as $item)
                        <li class="flex gap-5 rounded-2xl border border-line bg-white p-5 sm:p-6">
                            <span class="font-display text-sm font-bold tracking-[0.16em] text-gold">{{ $item['step'] }}</span>
                            <div>
                                <h3 class="font-display text-base font-semibold text-ink-950">{{ $item['title'] }}</h3>
                                <p class="mt-1.5 text-sm leading-relaxed text-slate-600">{{ $item['copy'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="Not sure where you fit?"
        title="The office can explain the options."
        copy="Players, coaches, officials and institutions each have a route into the association."
    >
        <a href="{{ route('contact') }}" class="btn btn-gold btn-lg btn-pill">Contact the association</a>
        <a href="{{ route('governance') }}" class="btn btn-outline-light btn-lg btn-pill">Governance</a>
    </x-public.cta-band>
@endsection
