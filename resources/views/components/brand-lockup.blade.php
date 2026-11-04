@props(['association' => null, 'variant' => 'light', 'size' => 'md', 'subtitle' => null])

@php
    $tile = $size === 'lg'
        ? 'h-14 w-14 rounded-2xl text-base'
        : 'h-11 w-11 rounded-xl text-sm';
    $title = $variant === 'dark' ? 'text-white' : 'text-ink-900';
    $muted = $variant === 'dark' ? 'text-slate-400' : 'text-slate-500';
@endphp

<div {{ $attributes->merge(['class' => 'flex min-w-0 items-center gap-3']) }}>
    @if ($association?->logoUrl())
        <img
            src="{{ $association->logoUrl() }}"
            alt=""
            class="{{ $tile }} shrink-0 bg-white object-contain p-1.5 shadow-soft"
        >
    @else
        <span
            class="{{ $tile }} flex shrink-0 items-center justify-center font-display font-bold"
            style="background-color: var(--brand); color: var(--brand-on)"
        >
            {{ $association?->initials() ?? 'SA' }}
        </span>
    @endif

    <div class="min-w-0">
        <p class="truncate font-display text-[0.95rem] font-semibold leading-tight {{ $title }}">
            {{ $association?->short_name ?? $association?->name ?? config('app.name') }}
        </p>
        <p class="truncate text-[11px] leading-tight {{ $muted }}">
            {{ $subtitle ?? $association?->state?->name ?? 'Official association portal' }}
        </p>
    </div>
</div>
