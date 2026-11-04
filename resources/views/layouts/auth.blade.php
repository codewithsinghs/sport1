@php
    $association = $currentAssociation ?? null;
    $stateName = $association?->state?->name ?? 'Madhya Pradesh';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050b14">
    <title>Sign in — {{ $association?->short_name ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        :root {
            --brand: {{ $association?->primaryColor() ?? '#0f766e' }};
            --brand-accent: {{ $association?->secondaryColor() ?? '#f59e0b' }};
            --brand-on: {{ $association?->primaryForeground() ?? '#ffffff' }};
        }
    </style>
</head>
<body class="h-full bg-paper">
    <div class="grid min-h-screen lg:grid-cols-[1.05fr_0.95fr]">
        <div class="court-grid relative hidden flex-col justify-between overflow-hidden bg-ink-950 p-12 text-white lg:flex xl:p-16">
            <div class="pointer-events-none absolute -right-40 -top-40 h-[30rem] w-[30rem] rounded-full opacity-30 blur-3xl" style="background: radial-gradient(circle, var(--brand) 0%, transparent 68%)"></div>
            <x-target-motif class="pointer-events-none absolute -bottom-48 -left-32 w-[34rem] text-white opacity-20" />

            <a href="{{ route('home') }}" class="relative w-fit rounded-xl">
                <x-brand-lockup :association="$association" variant="dark" :subtitle="$stateName" />
            </a>

            <div class="relative max-w-xl py-14">
                <p class="eyebrow text-white/45">
                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--brand-accent)"></span>
                    Association administration
                </p>
                <h1 class="display-lg mt-5">One workspace.<br>Every district.</h1>
                <p class="mt-5 max-w-md text-base leading-relaxed text-white/60">
                    Manage districts, sports, disciplines, competition categories and membership configuration from one association-scoped workspace.
                </p>

                <ul class="mt-9 space-y-3.5">
                    @foreach (['Association-scoped records by trusted server context', 'District, sport and membership master data', 'Configurable competition structure'] as $point)
                        <li class="flex items-start gap-3 text-sm text-white/70">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-white/15 bg-white/5">
                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5 9.5 17 19 7" />
                                </svg>
                            </span>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="relative flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-5 text-xs text-white/45">
                <p>{{ $stateName }} &middot; Official association portal</p>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 transition hover:text-white">
                    Public site
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M9 7h8v8" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="relative flex items-center justify-center px-4 py-12 sm:px-8">
            <div class="pointer-events-none absolute inset-0 opacity-40" style="background: radial-gradient(circle at 80% 12%, var(--brand-soft), transparent 55%)"></div>

            <div class="relative w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 inline-block rounded-xl lg:hidden">
                    <x-brand-lockup :association="$association" :subtitle="$stateName" />
                </a>

                <div class="card shadow-lift">
                    <div class="card-body p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-6 flex flex-col items-center gap-2 text-center">
                    <p class="text-xs text-slate-500">{{ config('app.name') }} &middot; Association-scoped access only</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition hover:text-ink-900">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M11 18l-6-6 6-6" />
                        </svg>
                        Back to the public site
                    </a>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
