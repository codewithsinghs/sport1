@extends('layouts.public')

@php
    $stateName = $association->state?->name ?? 'Madhya Pradesh';
    $shortName = $association->short_name ?: $association->name;
    $email = $association->email;
    $instagram = $association->setting(\App\Modules\Association\Services\AssociationSettings::INSTAGRAM_URL);
    $nationalBody = $association->setting(\App\Modules\Association\Services\AssociationSettings::NATIONAL_BODY_URL);
@endphp

@section('title', 'Contact — '.$shortName)
@section('description', 'Contact '.$association->name.' about membership, districts, competitions and association information.')
@section('og_image', asset('images/site/championship-stage.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Contact"
        title="Talk to the association office."
        :intro="'For membership, district participation, competition information or anything published on this portal, contact '.$shortName.' directly.'"
        image="championship-stage"
        image-alt="The championship stage at the 2025 National Targetball Championship in Bhopal"
        :image-width="1400"
        :image-height="933"
        :image-small-width="700"
        badge="Official association contact"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Contact']]"
    >
        <x-slot:actions>
            @if ($email)
                <a href="mailto:{{ $email }}" class="btn btn-gold btn-lg btn-pill">Email the association</a>
            @endif
            <a href="{{ route('membership') }}" class="btn btn-outline-light btn-lg btn-pill">Membership</a>
        </x-slot:actions>

        <x-slot:stats>
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-4 py-4 backdrop-blur-sm">
                <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/45">Coverage</dt>
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

    <section class="py-20 lg:py-24">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16 lg:px-8">
            <div>
                <p class="eyebrow-luxe" style="color: var(--brand-deep)">Association office</p>
                <h2 class="display-serif display-serif-lg mt-5 text-ink-950">Reach the right desk.</h2>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-slate-600">
                    The association office handles membership, district coordination and competition information. Use
                    the contact details below or start an email with the reason for your enquiry.
                </p>

                <dl class="mt-10 grid gap-4 sm:grid-cols-2">
                    <div class="luxe-card bg-white p-5">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Office</dt>
                        <dd class="mt-3 text-sm leading-relaxed text-slate-700">{{ $association->address ?: 'Address not configured' }}</dd>
                    </div>
                    <div class="luxe-card bg-white p-5">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Email</dt>
                        <dd class="mt-3 break-words text-sm leading-relaxed text-slate-700">
                            @if ($email)
                                <a href="mailto:{{ $email }}" class="link-brand">{{ $email }}</a>
                            @else
                                Not configured
                            @endif
                        </dd>
                    </div>
                    <div class="luxe-card bg-white p-5">
                        <dt class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Phone</dt>
                        <dd class="mt-3 text-sm leading-relaxed text-slate-700">
                            @if ($association->phone)
                                <a href="tel:{{ $association->phone }}" class="link-brand">{{ $association->phone }}</a>
                            @else
                                Not configured
                            @endif
                        </dd>
                    </div>
                    <div class="luxe-card bg-white p-5">
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

                <p class="mt-6 text-xs leading-relaxed text-slate-500">
                    Phone number and office hours are published once confirmed by the association.
                </p>

                @if ($instagram || $nationalBody)
                    <div class="mt-8 border-t border-line pt-6">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Official channels</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if ($instagram)
                                <a href="{{ $instagram }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-gold/50 hover:text-ink-950">
                                    Instagram
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                                </a>
                            @endif
                            @if ($nationalBody)
                                <a href="{{ $nationalBody }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full border border-line bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-gold/50 hover:text-ink-950">
                                    Targetball India
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-[2rem] border border-line bg-white p-6 shadow-soft sm:p-8">
                <p class="eyebrow-luxe" style="color: var(--brand-deep)">Start an email</p>
                <h2 class="display-serif display-serif-md mt-4 text-ink-950">What is your enquiry about?</h2>
                <p class="mt-3 text-sm leading-relaxed text-slate-600">
                    Choose a topic to open a pre-addressed email. Include your district and a phone number where the
                    office can reach you.
                </p>

                <div class="mt-7 space-y-3">
                    @foreach ([
                        ['title' => 'Membership & registration', 'copy' => 'Categories, eligibility and fee confirmation.', 'subject' => 'Membership enquiry'],
                        ['title' => 'District participation', 'copy' => 'Local bodies, district activity and contacts.', 'subject' => 'District enquiry'],
                        ['title' => 'Competitions & officials', 'copy' => 'Event information and technical officiating.', 'subject' => 'Competition enquiry'],
                        ['title' => 'Portal content correction', 'copy' => 'Report a detail that needs the association’s review.', 'subject' => 'Portal correction'],
                    ] as $topic)
                        <a
                            href="{{ $email ? 'mailto:'.$email.'?subject='.rawurlencode($topic['subject']) : route('contact') }}"
                            class="group flex items-center justify-between gap-4 rounded-2xl border border-line px-4 py-4 transition hover:border-gold/40 hover:bg-cream/40 sm:px-5"
                        >
                            <span>
                                <span class="block text-sm font-semibold text-ink-950">{{ $topic['title'] }}</span>
                                <span class="mt-1 block text-xs leading-relaxed text-slate-500">{{ $topic['copy'] }}</span>
                            </span>
                            <svg class="h-4 w-4 shrink-0 text-slate-400 transition group-hover:translate-x-0.5 group-hover:text-gold" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8"/></svg>
                        </a>
                    @endforeach
                </div>

                @unless ($email)
                    <p class="mt-5 rounded-xl border border-dashed border-line bg-cream/60 px-4 py-3 text-xs leading-relaxed text-slate-500">
                        Email is being configured by the association. Please check back shortly.
                    </p>
                @endunless

                <div class="mt-8 rounded-2xl bg-cream/70 p-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Office response</p>
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                        Enquiries are handled by the association office during its working days. This portal does not run an automated support desk.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="District network"
        title="Looking for your district first?"
        copy="The directory lists every district in the state master and links to each public district page."
    >
        <a href="{{ route('districts.index') }}" class="btn btn-gold btn-lg btn-pill">Open the district directory</a>
        <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg btn-pill">About the association</a>
    </x-public.cta-band>
@endsection
