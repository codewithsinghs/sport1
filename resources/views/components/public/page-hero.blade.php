@props([
    'eyebrow',
    'title',
    'intro' => null,
    'image' => null,
    'imageAlt' => '',
    'imageWidth' => 1600,
    'imageHeight' => 900,
    'imageSmallWidth' => 800,
    'badge' => null,
    'compact' => false,
    'breadcrumb' => [],
])

<section class="relative isolate overflow-hidden bg-ink-950 text-white">
    @if ($image)
        <div class="absolute inset-0">
            <x-public.photo
                :name="$image"
                :alt="$imageAlt"
                :width="$imageWidth"
                :height="$imageHeight"
                :small-width="$imageSmallWidth"
                sizes="100vw"
                class="h-full w-full object-cover"
                eager
            />
            <div class="photo-veil absolute inset-0"></div>
            <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-ink-950 to-transparent"></div>
        </div>
    @else
        <div class="court-grid absolute inset-0 opacity-60"></div>
        <div class="pointer-events-none absolute -right-32 top-[-10rem] h-[34rem] w-[34rem] rounded-full opacity-25 blur-3xl" style="background: radial-gradient(circle, var(--brand) 0%, transparent 68%)"></div>
    @endif

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 {{ $compact ? 'pb-14 pt-20 lg:pb-20 lg:pt-24' : 'pb-16 pt-24 lg:pb-24 lg:pt-32' }}">
        @if ($breadcrumb !== [])
            <nav aria-label="Breadcrumb" class="mb-8">
                <ol class="flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase tracking-[0.18em] text-white/40">
                    @foreach ($breadcrumb as $item)
                        <li class="flex items-center gap-2">
                            @if (! $loop->last && ! empty($item['url']))
                                <a href="{{ $item['url'] }}" class="transition hover:text-white">{{ $item['label'] }}</a>
                            @else
                                <span @if ($loop->last) aria-current="page" @endif class="text-white/70">{{ $item['label'] }}</span>
                            @endif
                            @unless ($loop->last)
                                <span aria-hidden="true">/</span>
                            @endunless
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        <div class="max-w-4xl">
            <p class="reveal eyebrow-luxe text-gold">{{ $eyebrow }}</p>
            <h1 class="reveal reveal-1 display-serif mt-6 {{ $compact ? 'display-serif-lg' : 'display-serif-xl' }}">
                {{ $title }}
            </h1>

            @if ($intro)
                <p class="reveal reveal-2 mt-6 max-w-2xl text-base leading-relaxed text-white/65 sm:text-lg">
                    {{ $intro }}
                </p>
            @endif

            @isset($actions)
                <div class="reveal reveal-3 mt-9 flex flex-wrap items-center gap-3">
                    {{ $actions }}
                </div>
            @endisset

            @isset($stats)
                <dl class="reveal reveal-4 mt-12 grid max-w-2xl grid-cols-2 gap-x-8 gap-y-6 sm:grid-cols-3">
                    {{ $stats }}
                </dl>
            @endisset
        </div>
    </div>

    <div class="rule-gold absolute inset-x-0 bottom-0 opacity-60"></div>
</section>
