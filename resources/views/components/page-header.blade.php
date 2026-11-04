@props(['title', 'subtitle' => null, 'eyebrow' => null])

<div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div>
        @if ($eyebrow)
            <p class="eyebrow" style="color: var(--brand-deep)">{{ $eyebrow }}</p>
        @endif
        <h1 class="font-display text-2xl font-semibold tracking-tight text-ink-950 sm:text-[1.75rem]">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>

    @if (! $slot->isEmpty())
        <div class="flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
