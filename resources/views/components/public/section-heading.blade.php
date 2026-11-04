@props(['eyebrow', 'title', 'copy' => null, 'align' => 'left'])

<div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
    <div class="max-w-2xl">
        <p class="eyebrow-luxe">{{ $eyebrow }}</p>
        <h2 class="display-serif display-serif-lg mt-4 text-ink-950">{{ $title }}</h2>
        @if ($copy)
            <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600">{{ $copy }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="shrink-0">
            {{ $actions }}
        </div>
    @endisset
</div>
