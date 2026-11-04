<div>
    <x-page-header
        eyebrow="Workspace overview"
        title="Dashboard"
        subtitle="Association configuration and master data at a glance."
    >
        @if (auth()->user()->canManageAssociation())
            <a href="{{ route('admin.association.profile') }}" class="btn btn-secondary btn-sm">Edit profile</a>
            <a href="{{ route('admin.disciplines.index') }}" class="btn btn-brand btn-sm">Manage disciplines</a>
        @endif
    </x-page-header>

    <section class="surface-dark court-grid p-6 sm:p-8 lg:p-10">
        <x-target-motif class="pointer-events-none absolute -right-20 -top-24 w-[22rem] opacity-30" />

        <div class="relative z-10 grid gap-8 lg:grid-cols-[1.4fr_0.6fr] lg:items-center">
            <div>
                <p class="eyebrow text-white/45">
                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: var(--brand-accent)"></span>
                    Current association
                </p>

                <div class="mt-5 flex items-start gap-4">
                    @if ($association->logoUrl())
                        <img src="{{ $association->logoUrl() }}" alt="" class="h-14 w-14 shrink-0 rounded-2xl bg-white object-contain p-1.5 shadow-soft">
                    @else
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl font-display text-lg font-bold" style="background: var(--brand); color: var(--brand-on)">
                            {{ $association->initials() }}
                        </span>
                    @endif

                    <div class="min-w-0">
                        <h2 class="display-lg text-white">{{ $association->name }}</h2>
                        <p class="mt-2 text-sm text-white/60">
                            {{ $association->state?->name ?? 'State not configured' }}
                            @if ($association->short_name)
                                &middot; {{ $association->short_name }}
                            @endif
                        </p>
                    </div>
                </div>

                @if ($association->setting('about'))
                    <p class="mt-5 max-w-2xl text-sm leading-relaxed text-white/60">{{ $association->setting('about') }}</p>
                @endif

                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($sports as $sport)
                        <span class="rounded-full border border-white/12 bg-white/[0.06] px-3 py-1.5 text-xs font-medium text-white/75">{{ $sport->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/[0.05] p-5 backdrop-blur-sm">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-white/35">Workspace</p>
                <dl class="mt-4 space-y-3.5 text-sm">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-white/55">District coverage</dt>
                        <dd class="font-display font-semibold text-white">{{ number_format($stats[0]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-white/55">Sports enabled</dt>
                        <dd class="font-display font-semibold text-white">{{ number_format($stats[1]['value']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-white/55">Access</dt>
                        <dd class="font-display font-semibold text-white">{{ auth()->user()->role->label() }}</dd>
                    </div>
                </dl>

                @if (auth()->user()->canManageAssociation())
                    <a href="{{ route('admin.association.profile') }}" class="btn btn-light btn-sm btn-pill mt-5 w-full">
                        Manage profile
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-5">
        @php
            $statIcons = [
                'M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11zM12 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z',
                'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zM12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8z',
                'M12 3l9 5-9 5-9-5 9-5zM3 12l9 5 9-5M3 16l9 5 9-5',
                'M20 12l-8 8-8-8V4h8zM8 8h.01',
                'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM22 21v-2a4 4 0 0 0-3-3.87',
            ];
        @endphp

        @foreach ($stats as $stat)
            <a href="{{ $stat['route'] }}" class="card card-body group flex flex-col transition duration-300 ease-swift hover:-translate-y-1 hover:shadow-lift">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">{{ $stat['label'] }}</p>
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg" style="background: var(--brand-soft); color: var(--brand-deep)">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="{{ $statIcons[$loop->index] ?? '' }}" />
                        </svg>
                    </span>
                </div>
                <p class="stat-value mt-5 text-ink-950">{{ number_format($stat['value']) }}</p>
                <span class="mt-auto pt-4 inline-flex items-center gap-1 text-xs font-semibold" style="color: var(--brand-deep)">
                    View configuration
                    <svg class="h-3.5 w-3.5 transition group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                    </svg>
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="card overflow-hidden lg:col-span-2">
            <div class="flex items-center justify-between gap-4 border-b border-line px-5 py-4 sm:px-6">
                <div>
                    <h3 class="section-title">Enabled sports</h3>
                    <p class="mt-1 text-xs text-slate-500">The sports programme available to this association.</p>
                </div>
                <a href="{{ route('admin.sports.index') }}" class="text-sm font-semibold" style="color: var(--brand-deep)">Manage</a>
            </div>
            <div class="divide-y divide-line">
                @forelse ($sports as $sport)
                    <div class="flex items-start justify-between gap-4 px-5 py-4 transition hover:bg-paper sm:px-6">
                        <div class="flex min-w-0 items-start gap-3.5">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl" style="background: var(--brand-soft); color: var(--brand-deep)">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                    <circle cx="12" cy="12" r="8" />
                                    <circle cx="12" cy="12" r="4" />
                                    <circle cx="12" cy="12" r="1" fill="currentColor" stroke="none" />
                                </svg>
                            </span>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-ink-950">{{ $sport->name }}</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $sport->description ?: 'No description configured.' }}</p>
                            </div>
                        </div>
                        <span class="badge badge-green shrink-0">Enabled</span>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center sm:px-6">
                        <p class="font-display text-base font-semibold text-ink-950">No sports enabled yet.</p>
                        <p class="mt-1 text-sm text-slate-500">Enable a sport to publish it on the public site.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="border-b border-line px-5 py-4 sm:px-6">
                <h3 class="section-title">Your access</h3>
            </div>
            <dl class="space-y-5 px-5 py-5 text-sm sm:px-6">
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Account</dt>
                    <dd class="mt-1.5 font-medium text-ink-950">{{ auth()->user()->name }}</dd>
                    <dd class="break-words text-slate-500">{{ auth()->user()->email }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Role</dt>
                    <dd class="mt-1.5"><span class="badge badge-brand">{{ auth()->user()->role->label() }}</span></dd>
                </div>
                <div>
                    <dt class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">Isolation</dt>
                    <dd class="mt-1.5 leading-relaxed text-slate-600">
                        This workspace only exposes records belonging to {{ $association->short_name ?? $association->name }}.
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
