@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
    $email = $association->email;
@endphp

@section('title', 'Terms of use — '.$shortName)
@section('description', 'Terms governing use of the '.$association->name.' public portal.')
@section('og_image', asset('images/site/officials.webp'))

@php
    $sections = [
        ['id' => 'portal', 'title' => 'Using this portal'],
        ['id' => 'accounts', 'title' => 'Administrative accounts'],
        ['id' => 'records', 'title' => 'Association records'],
        ['id' => 'competition', 'title' => 'Competition information'],
        ['id' => 'content', 'title' => 'Content and photography'],
        ['id' => 'liability', 'title' => 'Availability and liability'],
    ];
@endphp

@section('content')
    <x-public.page-hero
        eyebrow="Portal policy"
        title="Terms of use."
        intro="These terms govern access to the public pages and administrative area of this portal. By using the portal you agree to them."
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Terms']]"
    />

    <section class="py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-[0.32fr_0.68fr] lg:gap-16">
                <aside class="lg:sticky lg:top-28 lg:self-start">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">On this page</p>
                    <nav class="mt-4 space-y-2" aria-label="Terms sections">
                        @foreach ($sections as $section)
                            <a href="#{{ $section['id'] }}" class="block text-sm text-slate-600 transition hover:text-ink-950">{{ $section['title'] }}</a>
                        @endforeach
                    </nav>
                    <div class="mt-8 rounded-2xl border border-line bg-cream/60 p-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Operated by</p>
                        <p class="mt-2 text-sm font-semibold text-ink-950">{{ $association->name }}</p>
                        @if ($email)
                            <a href="mailto:{{ $email }}" class="mt-1 block text-sm text-slate-600 transition hover:text-ink-950">{{ $email }}</a>
                        @endif
                    </div>
                </aside>

                <div class="space-y-12 text-sm leading-relaxed text-slate-600 sm:text-base">
                    <div id="portal" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Using this portal</h2>
                        <p class="mt-4">
                            This portal is provided by {{ $association->name }} for information about the association, its districts, sports programme and membership. You may read and share the public pages for lawful purposes connected with the association and the sport.
                        </p>
                        <p class="mt-4">
                            You must not attempt to access records or administrative functions you are not authorised to use, interfere with the operation of the portal, or submit misleading information to the association.
                        </p>
                    </div>

                    <div id="accounts" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Administrative accounts</h2>
                        <p class="mt-4">
                            Administrative access is limited to accounts issued by the association to authorised office bearers and staff. Accounts are personal, must not be shared, and must be used only for the association's work.
                        </p>
                        <p class="mt-4">
                            The association may suspend or close an account where access is no longer required or where these terms are not followed. Report suspected unauthorised access to the office immediately.
                        </p>
                    </div>

                    <div id="records" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Association records</h2>
                        <p class="mt-4">
                            District, sport, membership and competition records shown in the administrative area belong to this association. They are not a public dataset and must not be copied, published or shared outside the association without authorisation.
                        </p>
                        <p class="mt-4">
                            Where information on a public page differs from a signed association record, the signed record held by the office takes precedence. Contact the office to correct any error you identify.
                        </p>
                    </div>

                    <div id="competition" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Competition information</h2>
                        <p class="mt-4">
                            Event dates, venues and categories are published as association information. Results and participation records appear only after verification by the championship technical committee; provisional scores are not published here.
                        </p>
                        <p class="mt-4">
                            Competition rules and eligibility conditions are set by the relevant competition authority and are not replaced by anything on this portal.
                        </p>
                    </div>

                    <div id="content" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Content and photography</h2>
                        <p class="mt-4">
                            Association names, marks, text and photography on this portal may be used for reporting and promotion of the association and the sport with appropriate credit. Commercial use requires written permission from the association.
                        </p>
                        <p class="mt-4">
                            Championship photography is published for association and media use. Where an image identifies an individual, it is the responsibility of the reuser to ensure their use is appropriate.
                        </p>
                    </div>

                    <div id="liability" class="scroll-mt-28">
                        <h2 class="display-serif display-serif-md text-ink-950">Availability and liability</h2>
                        <p class="mt-4">
                            The portal is maintained on a reasonable-effort basis. The association does not guarantee uninterrupted availability and may change or withdraw pages without notice.
                        </p>
                        <p class="mt-4">
                            Nothing on this portal constitutes legal, financial or selection advice. The association is not responsible for decisions made solely on the basis of general information published here. Nothing in these terms limits any liability that cannot be limited under applicable law.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            @if ($email)
                                <a href="mailto:{{ $email }}?subject={{ rawurlencode('Portal terms enquiry') }}" class="btn btn-brand btn-pill">Email the office</a>
                            @endif
                            <a href="{{ route('privacy') }}" class="btn btn-ghost btn-pill">Privacy policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
