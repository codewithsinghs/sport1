@props(['title', 'copy' => null, 'eyebrow' => 'Next step'])

<section class="relative overflow-hidden py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="luxe-panel photo-grain px-6 py-12 sm:px-10 lg:px-14 lg:py-16">
            <div class="pointer-events-none absolute -right-24 -top-28 h-80 w-80 rounded-full border border-gold/20"></div>
            <div class="pointer-events-none absolute -right-10 -top-16 h-56 w-56 rounded-full border border-white/10"></div>

            <div class="relative lg:flex lg:items-center lg:justify-between lg:gap-12">
                <div class="max-w-2xl">
                    <p class="eyebrow-luxe text-gold">{{ $eyebrow }}</p>
                    <h2 class="display-serif display-serif-lg mt-4 text-white">{{ $title }}</h2>
                    @if ($copy)
                        <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/60 sm:text-base">{{ $copy }}</p>
                    @endif
                </div>

                <div class="relative mt-8 flex flex-wrap gap-3 lg:mt-0">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</section>
