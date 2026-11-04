@extends('layouts.public')

@php
    $shortName = $association->short_name ?: $association->name;
@endphp

@section('title', 'Gallery — '.$shortName)
@section('description', 'Photography from the 2025 National Targetball Championship in Bhopal, published by '.$association->name.'.')
@section('og_image', asset('images/site/gallery-01.webp'))

@section('content')
    <x-public.page-hero
        eyebrow="Championship gallery"
        title="Four days, one stage."
        intro="Contingents, ceremonies and the people behind the 2025 National Targetball Championship at Tatya Tope Stadium, Bhopal."
        image="gallery-01"
        image-alt="State teams marching at the opening of the 2025 National Targetball Championship"
        :image-width="1200"
        :image-height="800"
        :image-small-width="600"
        compact
        :breadcrumb="[['label' => 'Home', 'url' => route('home')], ['label' => 'Gallery']]"
    >
        <x-slot:actions>
            <a href="{{ route('events.index') }}" class="btn btn-outline-light btn-lg btn-pill">Event details</a>
            <a href="{{ route('news.index') }}" class="btn btn-gold btn-lg btn-pill">Read the update</a>
        </x-slot:actions>
    </x-public.page-hero>

    <section class="py-20 lg:py-28">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 border-b border-line pb-8 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="eyebrow-luxe" style="color: var(--brand-deep)">The archive</p>
                    <h2 class="display-serif display-serif-md mt-4 text-ink-950">{{ count($photos) }} frames from Bhopal</h2>
                </div>
                <p class="max-w-sm text-sm leading-relaxed text-slate-500">
                    Official championship photography. Select any frame to view it full size.
                </p>
            </div>

            <div class="mt-10 grid auto-rows-[14rem] grid-cols-2 gap-4 sm:auto-rows-[16rem] lg:grid-cols-4">
                @foreach ($photos as $index => $photo)
                    @php
                        $isWide = in_array($index, [0, 5, 8], true);
                    @endphp
                    <button
                        type="button"
                        class="gallery-tile group {{ $isWide ? 'col-span-2' : '' }}"
                        data-gallery-open
                        data-src="{{ asset('images/site/'.$photo['name'].'.webp') }}"
                        data-caption="{{ $photo['caption'] }}"
                        data-alt="{{ $photo['alt'] }}"
                        aria-label="View photo: {{ $photo['caption'] }}"
                    >
                        <x-public.photo
                            :name="$photo['name']"
                            :alt="$photo['alt']"
                            :width="1200"
                            :height="800"
                            :small-width="600"
                            sizes="(min-width: 1024px) 25vw, 50vw"
                        />
                        <span class="absolute inset-x-0 bottom-0 z-10 p-4 text-left">
                            <span class="block text-[10px] font-bold uppercase tracking-[0.16em] text-gold">Championship 2025</span>
                            <span class="mt-1 block text-sm font-medium text-white">{{ $photo['caption'] }}</span>
                        </span>
                        <span class="absolute right-4 top-4 z-10 inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/30 bg-ink-950/40 text-white opacity-0 backdrop-blur transition group-hover:opacity-100">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                        </span>
                    </button>
                @endforeach
            </div>

            <p class="mt-8 text-xs leading-relaxed text-slate-500">
                Photography from the 2025 National Targetball Championship, Bhopal. Images are published for association and media use; please credit the source.
            </p>
        </div>
    </section>

    <x-public.cta-band
        eyebrow="On the court"
        title="See the championship in context."
        copy="Read the event update, then explore the competition programme and results process."
    >
        <a href="{{ route('news.index') }}" class="btn btn-gold btn-lg btn-pill">Read the update</a>
        <a href="{{ route('events.index') }}" class="btn btn-outline-light btn-lg btn-pill">Events &amp; championships</a>
    </x-public.cta-band>

    <dialog id="gallery-lightbox" class="w-full max-w-5xl rounded-2xl border border-white/15 bg-ink-950 p-0 text-white backdrop:bg-ink-950/90 backdrop:backdrop-blur">
        <div class="relative">
            <button type="button" class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-ink-950/70 text-white transition hover:bg-white/10" data-gallery-close aria-label="Close photo viewer">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
            </button>
            <img id="gallery-lightbox-image" src="" alt="" class="max-h-[75vh] w-full object-contain">
            <div class="flex flex-col gap-1 border-t border-white/10 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="gallery-lightbox-caption" class="font-display text-sm font-semibold text-white"></p>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-gold">National Targetball Championship 2025 &middot; Bhopal</p>
            </div>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script>
        (() => {
            const dialog = document.getElementById('gallery-lightbox');
            if (!dialog || typeof dialog.showModal !== 'function') return;

            const image = document.getElementById('gallery-lightbox-image');
            const caption = document.getElementById('gallery-lightbox-caption');

            document.querySelectorAll('[data-gallery-open]').forEach((tile) => {
                tile.addEventListener('click', () => {
                    image.src = tile.dataset.src;
                    image.alt = tile.dataset.alt || '';
                    caption.textContent = tile.dataset.caption || '';
                    dialog.showModal();
                });
            });

            dialog.querySelectorAll('[data-gallery-close]').forEach((button) => {
                button.addEventListener('click', () => dialog.close());
            });

            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });

            dialog.addEventListener('close', () => {
                image.src = '';
            });
        })();
    </script>
@endpush
