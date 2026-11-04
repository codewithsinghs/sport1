@props([
    'items' => [],
    'columns' => 3,
    'label' => 'Photograph gallery',
])

@php
    $gridClass = match ((int) $columns) {
        2 => 'sm:grid-cols-2',
        4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => 'sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

@if (count($items) > 0)
    <div data-lightbox>
        <div class="grid gap-4 {{ $gridClass }}">
            @foreach ($items as $item)
                <button
                    type="button"
                    data-lightbox-trigger
                    data-full="{{ $item['full'] }}"
                    data-alt="{{ $item['alt'] ?? $item['caption'] ?? '' }}"
                    data-caption="{{ $item['caption'] ?? '' }}"
                    data-credit="{{ $item['credit'] ?? '' }}"
                    data-width="{{ $item['width'] ?? '' }}"
                    data-height="{{ $item['height'] ?? '' }}"
                    aria-label="Open photograph {{ $loop->iteration }} of {{ count($items) }}"
                    class="gallery-tile group relative block overflow-hidden rounded-[1.5rem] border border-line bg-ink-900 text-left focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold"
                >
                    <img
                        src="{{ $item['thumb'] ?? $item['full'] }}"
                        alt="{{ $item['alt'] ?? '' }}"
                        width="{{ $item['width'] ?? 800 }}"
                        height="{{ $item['height'] ?? 600 }}"
                        loading="lazy"
                        decoding="async"
                        class="h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-[1.04] group-hover:opacity-100"
                    >
                    <span class="photo-veil absolute inset-0"></span>
                    <span class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 p-4">
                        <span class="text-xs font-semibold text-white/85">{{ $item['caption'] ?? '' }}</span>
                        <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-white/25 bg-white/10 text-white opacity-0 transition group-hover:opacity-100" aria-hidden="true">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                        </span>
                    </span>
                </button>
            @endforeach
        </div>

        <div
            data-lightbox-dialog
            class="lb"
            role="dialog"
            aria-modal="true"
            aria-label="{{ $label }}"
        >
            <div data-lightbox-backdrop class="lb__backdrop"></div>

            <div class="lb__shell">
                <header class="lb__bar">
                    <p data-lightbox-counter class="lb__counter"></p>
                    <p data-lightbox-caption class="lb__caption" hidden></p>
                    <div class="lb__tools">
                        <button type="button" class="lb__btn" data-lightbox-prev aria-label="Previous photograph">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 6l-6 6 6 6"/></svg>
                        </button>
                        <button type="button" class="lb__btn" data-lightbox-next aria-label="Next photograph">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6l6 6-6 6"/></svg>
                        </button>
                        <button type="button" class="lb__btn" data-lightbox-close aria-label="Close viewer">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                    </div>
                </header>

                <div data-lightbox-stage class="lb__stage">
                    <img data-lightbox-image class="lb__image" alt="">
                </div>

                <footer class="lb__bar lb__bar--foot">
                    <p data-lightbox-credit class="lb__credit" hidden></p>
                    <p class="lb__hint">← → navigate · click to zoom · Esc to close</p>
                </footer>

                @if (count($items) > 1)
                    <div data-lightbox-strip class="lb__strip">
                        @foreach ($items as $item)
                            <button type="button" class="lb__thumb" tabindex="-1" data-thumb-index="{{ $loop->index }}">
                                <img src="{{ $item['thumb'] ?? $item['full'] }}" alt="" loading="lazy" width="120" height="80">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
