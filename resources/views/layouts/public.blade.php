@php
    $association = $currentAssociation ?? null;
    $associationName = $association?->name ?? config('app.name');
    $shortName = $association?->short_name ?? $associationName;
    $stateName = $association?->state?->name ?? 'Madhya Pradesh';
    $description = trim($__env->yieldContent('description')) ?: ($association?->setting('about') ?: $associationName.' official portal');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050b14">
    <title>@yield('title', $associationName)</title>
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $associationName }}">
    <meta property="og:title" content="@yield('title', $associationName)">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($association?->logoUrl())
        <meta property="og:image" content="{{ url($association->logoUrl()) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --brand: {{ $association?->primaryColor() ?? '#0f766e' }};
            --brand-accent: {{ $association?->secondaryColor() ?? '#f59e0b' }};
            --brand-on: {{ $association?->primaryForeground() ?? '#ffffff' }};
        }
    </style>

    @if ($association)
        <script type="application/ld+json">
            {!! json_encode(array_filter([
                '@context' => 'https://schema.org',
                '@type' => 'SportsOrganization',
                'name' => $association->name,
                'url' => url('/'),
                'logo' => $association->logoUrl() ? url($association->logoUrl()) : null,
                'email' => $association->email,
                'telephone' => $association->phone,
                'address' => $association->address ? [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $association->address,
                    'addressRegion' => $stateName,
                    'addressCountry' => 'IN',
                ] : null,
            ]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
</head>
<body class="min-h-screen bg-paper text-slate-800">
    <a href="#main" class="skip-link">Skip to content</a>

    <div class="bg-ink-950 text-white/65">
        <div class="mx-auto flex h-9 max-w-7xl items-center justify-between gap-4 px-4 text-[11px] font-medium tracking-wide sm:px-6 lg:px-8">
            <p class="flex min-w-0 items-center gap-2">
                <span class="h-1.5 w-1.5 shrink-0 rounded-full" style="background-color: var(--brand-accent)"></span>
                <span class="truncate">{{ $shortName }} &middot; {{ $stateName }}</span>
            </p>
            <a href="{{ route('login') }}" class="hidden items-center gap-1.5 transition hover:text-white sm:inline-flex">
                Administrative login
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8" />
                </svg>
            </a>
        </div>
    </div>

    <header class="sticky top-0 z-40 border-b border-line/80 bg-paper/90 backdrop-blur-xl">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="min-w-0 rounded-xl" aria-label="{{ $associationName }} home">
                <x-brand-lockup :association="$association" :subtitle="$stateName" />
            </a>

            <nav class="hidden items-center gap-8 lg:flex" aria-label="Public">
                <a href="{{ route('home') }}#association" class="public-nav-link">The association</a>
                <a href="{{ route('home') }}#sport" class="public-nav-link">Sport</a>
                <a href="{{ route('home') }}#membership" class="public-nav-link">Membership</a>
                <a href="{{ route('home') }}#contact" class="public-nav-link">Contact</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="btn btn-brand btn-sm btn-pill hidden sm:inline-flex">
                    Member login
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </a>

                <button
                    type="button"
                    id="menu-toggle"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-white text-slate-700 transition hover:border-slate-300 hover:text-ink-900 lg:hidden"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                    aria-label="Open navigation"
                >
                    <svg id="menu-icon-open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                    <svg id="menu-icon-close" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6 6 18" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden border-t border-line bg-paper px-4 pb-5 pt-3 lg:hidden">
            <nav class="grid gap-1" aria-label="Public mobile">
                <a href="{{ route('home') }}#association" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">The association</a>
                <a href="{{ route('home') }}#sport" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">Sport</a>
                <a href="{{ route('home') }}#membership" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">Membership</a>
                <a href="{{ route('home') }}#contact" class="rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-white">Contact</a>
                <a href="{{ route('login') }}" class="mt-2 inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-semibold text-white" style="background-color: var(--brand); color: var(--brand-on)">
                    Member login
                </a>
            </nav>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="relative overflow-hidden bg-ink-950 text-white">
        <x-target-motif class="pointer-events-none absolute -bottom-40 -right-24 w-[460px] text-white/80 opacity-20" />
        <div class="relative mx-auto max-w-7xl px-4 pb-8 pt-16 sm:px-6 lg:px-8 lg:pt-20">
            <div class="grid gap-10 lg:grid-cols-[1.4fr_0.8fr_1fr_0.8fr]">
                <div>
                    <a href="{{ route('home') }}" class="inline-block rounded-xl">
                        <x-brand-lockup :association="$association" variant="dark" :subtitle="$stateName" />
                    </a>
                    <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/55">
                        Official portal of {{ $associationName }}. Association information, membership and competition updates published by the state body.
                    </p>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-white/35">
                        {{ $stateName }} &middot; {{ $association?->short_name ?? 'Association' }}
                    </p>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/35">Explore</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/65">
                        <li><a href="{{ route('home') }}#association" class="transition hover:text-white">The association</a></li>
                        <li><a href="{{ route('home') }}#sport" class="transition hover:text-white">Sports programme</a></li>
                        <li><a href="{{ route('home') }}#membership" class="transition hover:text-white">Membership</a></li>
                        <li><a href="{{ route('home') }}#contact" class="transition hover:text-white">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/35">Contact</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/65">
                        <li>{{ $association?->address ?: 'Address not configured' }}</li>
                        @if ($association?->phone)
                            <li><a href="tel:{{ $association->phone }}" class="transition hover:text-white">{{ $association->phone }}</a></li>
                        @endif
                        @if ($association?->email)
                            <li><a href="mailto:{{ $association->email }}" class="transition hover:text-white">{{ $association->email }}</a></li>
                        @endif
                        @if ($association?->website)
                            <li><a href="{{ $association->website }}" target="_blank" rel="noopener noreferrer" class="transition hover:text-white">{{ $association->website }}</a></li>
                        @endif
                    </ul>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/35">Access</p>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/65">
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Administrative login</a></li>
                        <li><a href="{{ route('login') }}" class="transition hover:text-white">Member login</a></li>
                    </ul>
                    <p class="mt-5 text-xs leading-relaxed text-white/35">
                        Access is limited to active accounts belonging to this association.
                    </p>
                </div>
            </div>

            <div class="mt-14 flex flex-col gap-3 border-t border-white/10 pt-6 text-xs text-white/35 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ now()->year }} {{ $associationName }}. All rights reserved.</p>
                <p>Official association portal &middot; {{ $stateName }}</p>
            </div>
        </div>
    </footer>

    <script>
        (() => {
            const toggle = document.getElementById('menu-toggle');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            if (!toggle || !menu) return;

            const setOpen = (open) => {
                menu.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                toggle.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
                iconOpen.classList.toggle('hidden', open);
                iconClose.classList.toggle('hidden', !open);
            };

            toggle.addEventListener('click', () => setOpen(menu.classList.contains('hidden')));
            menu.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setOpen(false)));
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setOpen(false);
            });
        })();
    </script>
</body>
</html>
