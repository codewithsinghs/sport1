@php
    $association = $currentAssociation ?? null;
    $user = auth()->user();
    $navigation = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'route' => route('admin.dashboard'), 'match' => 'admin.dashboard', 'icon' => 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z'],
            ],
        ],
        [
            'label' => 'Association',
            'items' => [
                ['label' => 'Profile & branding', 'route' => route('admin.association.profile'), 'match' => 'admin.association.profile', 'icon' => 'M4 21V7l8-4 8 4v14M9 21v-5h6v5M9 10h.01M15 10h.01'],
            ],
        ],
        [
            'label' => 'Programme',
            'items' => [
                ['label' => 'District associations', 'route' => route('admin.district-bodies.index'), 'match' => 'admin.district-bodies.*', 'icon' => 'M4 20h16M6 20V9l6-4 6 4v11M10 20v-5h4v5'],
                ['label' => 'Events & championships', 'route' => route('admin.events.index'), 'match' => 'admin.events.*', 'icon' => 'M7 3v3M17 3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1z'],
            ],
        ],
        [
            'label' => 'Master data',
            'items' => [
                ['label' => 'Districts', 'route' => route('admin.districts.index'), 'match' => 'admin.districts.*', 'icon' => 'M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11zM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'],
                ['label' => 'Sports', 'route' => route('admin.sports.index'), 'match' => 'admin.sports.*', 'icon' => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zM12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z'],
                ['label' => 'Disciplines', 'route' => route('admin.disciplines.index'), 'match' => 'admin.disciplines.*', 'icon' => 'M12 3l9 5-9 5-9-5 9-5zM3 12l9 5 9-5M3 16l9 5 9-5'],
                ['label' => 'Categories', 'route' => route('admin.categories.index'), 'match' => 'admin.categories.*', 'icon' => 'M20 12l-8 8-8-8V4h8zM8 8h.01'],
                ['label' => 'Membership categories', 'route' => route('admin.membership-categories.index'), 'match' => 'admin.membership-categories.*', 'icon' => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM22 21v-2a4 4 0 0 0-3-3.87'],
            ],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050b14">
    <title>{{ $association?->short_name ?? $association?->name ?? config('app.name') }} — Administration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    @livewireStyles
    @stack('styles')
    <style>
        :root {
            --brand: {{ $association?->primaryColor() ?? '#0f766e' }};
            --brand-accent: {{ $association?->secondaryColor() ?? '#f59e0b' }};
            --brand-on: {{ $association?->primaryForeground() ?? '#ffffff' }};
        }
    </style>
</head>
<body class="h-full bg-paper" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
    <a href="#main" class="skip-link">Skip to content</a>

    <div class="min-h-screen">
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-30 bg-ink-950/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

        <aside
            class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-hidden bg-ink-950 text-white transition-[transform,visibility] duration-300 ease-swift lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0 visible' : '-translate-x-full invisible lg:visible'"
        >
            <div class="h-1 shrink-0" style="background: linear-gradient(90deg, var(--brand), var(--brand-accent))"></div>

            <div class="flex h-[71px] shrink-0 items-center border-b border-white/8 px-5">
                <a href="{{ route('admin.dashboard') }}" class="w-full rounded-xl">
                    <x-brand-lockup :association="$association" variant="dark" :subtitle="$association?->state?->name ?? 'Association workspace'" />
                </a>
            </div>

            <nav class="relative z-10 flex-1 overflow-y-auto px-3 py-5" aria-label="Administration">
                @foreach ($navigation as $section)
                    <p class="px-3 pb-2 @if (! $loop->first) pt-5 @endif text-[10px] font-bold uppercase tracking-[0.18em] text-white/30">{{ $section['label'] }}</p>
                    <div class="space-y-1">
                        @foreach ($section['items'] as $item)
                            @php($active = request()->routeIs($item['match']))
                            <a
                                href="{{ $item['route'] }}"
                                class="nav-link @if ($active) nav-link-active @endif"
                                @if ($active) aria-current="page" @endif
                            >
                                <svg class="h-[18px] w-[18px] shrink-0 opacity-80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="{{ $item['icon'] }}" />
                                </svg>
                                <span class="truncate">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </nav>

            <x-target-motif class="pointer-events-none absolute -bottom-28 -right-24 w-64 opacity-[0.08]" />

            <div class="relative z-10 shrink-0 border-t border-white/8 p-4">
                <div class="flex items-center gap-3 rounded-2xl border border-white/8 bg-white/[0.03] p-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-display text-xs font-bold" style="background: var(--brand); color: var(--brand-on)">
                        {{ $user?->initials() }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ $user?->name }}</p>
                        <p class="truncate text-[11px] text-white/45">{{ $user?->role?->label() }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg p-2 text-white/45 transition hover:bg-white/10 hover:text-white" aria-label="Sign out">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="lg:pl-72">
            <header class="sticky top-0 z-20 flex h-[72px] items-center justify-between border-b border-line/80 bg-paper/90 px-4 backdrop-blur-xl sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" class="rounded-xl border border-line bg-white p-2 text-slate-600 transition hover:border-slate-300 hover:text-ink-900 lg:hidden" @click="sidebarOpen = true" aria-label="Open navigation">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0">
                        <p class="truncate font-display text-sm font-semibold text-ink-950">{{ $association?->name ?? config('app.name') }}</p>
                        <p class="truncate text-[11px] text-slate-500">{{ $association?->state?->name ?? 'Association workspace' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <span class="badge badge-slate hidden sm:inline-flex">{{ $user?->role?->label() }}</span>
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-sm btn-pill" target="_blank" rel="noopener">
                        Public site
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8" />
                        </svg>
                    </a>
                </div>
            </header>

            <main id="main" class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 lg:py-9">
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm font-medium text-emerald-800 shadow-soft">
                        <span class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5 9.5 17 19 7" />
                            </svg>
                            {{ session('status') }}
                        </span>
                        <button type="button" @click="show = false" class="text-emerald-600 hover:text-emerald-800" aria-label="Dismiss">&times;</button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start justify-between gap-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3.5 text-sm font-medium text-amber-900 shadow-soft">
                        <span class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 3h.01M10.3 4.3 2.6 18a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 4.3a2 2 0 0 0-3.4 0z" />
                            </svg>
                            {{ session('error') }}
                        </span>
                        <button type="button" @click="show = false" class="text-amber-700 hover:text-amber-900" aria-label="Dismiss">&times;</button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
