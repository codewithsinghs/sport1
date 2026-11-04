@php
    $accent = $event->accentColor();
    $days = $event->ends_at?->diffInDays($event->starts_at);
@endphp

<a href="{{ route('events.show', $event->slug) }}" class="luxe-card group grid overflow-hidden sm:grid-cols-[auto_1fr]">
    <div class="relative flex min-w-[104px] flex-col justify-between gap-4 px-5 py-5 text-center" style="{{ $accent ? 'background: color-mix(in srgb, '.$accent.' 12%, #f5f2ea);' : 'background: var(--paper, #f5f2ea);' }}">
        <div>
            <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">{{ $event->starts_at->format('M') }}</p>
            <p class="display-serif mt-1 text-3xl leading-none" style="color: {{ $accent ?: '#06110e' }}">{{ $event->starts_at->format('j') }}</p>
            <p class="mt-1 text-[11px] tabular-nums text-slate-500">
                @if ($event->ends_at && ! $event->ends_at->isSameDay($event->starts_at))
                    → {{ $event->ends_at->format('j M') }}
                @else
                    {{ $event->starts_at->format('Y') }}
                @endif
            </p>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">{{ $event->isUpcoming() ? 'Upcoming' : 'Completed' }}</p>
    </div>

    <div class="relative overflow-hidden">
        <img src="{{ $event->heroImageUrl() }}" alt="" aria-hidden="true" loading="lazy" decoding="async"
            class="absolute inset-0 h-full w-full object-cover opacity-[0.13]">

        <div class="relative flex h-full flex-col p-5 sm:p-6">
            <div class="flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase tracking-[0.14em]">
                <span class="rounded-full border border-line bg-white/80 px-2.5 py-1 text-slate-600">{{ $event->event_type->pluralLabel() }}</span>
                <span class="rounded-full border px-2.5 py-1" style="border-color: {{ $accent ?: 'var(--line, #e3ddd0)' }}; color: {{ $accent ?: '#06110e' }}; background: rgba(255,255,255,0.72)">{{ $event->level->label() }}</span>
                @if ($event->is_featured)
                    <span class="rounded-full bg-gold/15 px-2.5 py-1 text-gold">Featured</span>
                @endif
            </div>

            <h3 class="display-serif display-serif-md mt-4 text-ink-950 transition group-hover:text-slate-800">{{ $event->title }}</h3>

            @if ($event->summary)
                <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-slate-600">{{ $event->summary }}</p>
            @endif

            <div class="mt-auto space-y-1.5 pt-5 text-xs text-slate-500">
                <p class="flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s7-5.5 7-11a7 7 0 1 0-14 0c0 5.5 7 11 7 11z"/></svg>
                    {{ $event->venue_name ?: 'Venue to be announced' }}{{ $event->venue_address ? ', '.$event->venue_address : '' }}
                </p>
                <p class="flex items-center gap-2">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9l8-5 8 5v10M9 19v-6h6v6"/></svg>
                    {{ $event->hostName() ?: 'Hosted by the state association' }}
                </p>
                @if ($event->timeRangeLabel())
                    <p class="flex items-center gap-2 tabular-nums">
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><path stroke-linecap="round" d="M12 8v4l3 2M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z"/></svg>
                        {{ $event->timeRangeLabel() }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</a>
