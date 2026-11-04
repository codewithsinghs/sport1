@php
    use App\Modules\Content\Enums\ContentBlockType;

    $accent = $association?->secondaryColor() ?? '#c1a26a';
@endphp

@if ($block->type === ContentBlockType::RichText)
    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @if ($block->eyebrow)
                <p class="eyebrow-luxe" style="color: {{ $accent }}">{{ $block->eyebrow }}</p>
            @endif
            @if ($block->title)
                <h2 class="display-serif display-serif-lg mt-4 text-ink-950">{{ $block->title }}</h2>
            @endif
            {!! $block->bodyHtml() !!}
            @if ($block->hasLink())
                <p class="mt-7">
                    <a href="{{ $block->linkHref() }}" class="btn btn-brand btn-pill">{{ $block->linkText() }}</a>
                </p>
            @endif
        </div>
    </section>
@elseif ($block->type === ContentBlockType::ImageText)
    <section class="border-y border-line bg-cream/60 py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                <div class="luxe-frame relative aspect-[4/3] overflow-hidden rounded-[2rem]">
                    <x-public.photo
                        :name="$block->imageName()"
                        :alt="$block->title ?: $block->eyebrow ?: 'Association photograph'"
                        :width="1400"
                        :height="1050"
                        :small-width="700"
                        sizes="(min-width: 1024px) 46vw, 100vw"
                    />
                    <div class="photo-veil-soft absolute inset-0"></div>
                </div>
                <div>
                    @if ($block->eyebrow)
                        <p class="eyebrow-luxe" style="color: {{ $accent }}">{{ $block->eyebrow }}</p>
                    @endif
                    @if ($block->title)
                        <h2 class="display-serif display-serif-lg mt-4 text-ink-950">{{ $block->title }}</h2>
                    @endif
                    {!! $block->bodyHtml() !!}
                    @if ($block->hasLink())
                        <p class="mt-7">
                            <a href="{{ $block->linkHref() }}" class="btn btn-brand btn-pill">{{ $block->linkText() }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@elseif ($block->type === ContentBlockType::Stats && $block->stats() !== [])
    <section class="py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($block->title)
                <x-public.section-heading :eyebrow="$block->eyebrow ?: 'By the numbers'" :title="$block->title" />
            @endif
            <div class="luxe-panel photo-grain mt-10 grid gap-px overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($block->stats() as $stat)
                    <div class="bg-ink-950/40 px-7 py-8 text-center">
                        <p class="stat-value display-serif text-5xl text-white">{{ $stat['value'] }}</p>
                        <p class="mt-3 text-[10px] font-bold uppercase tracking-[0.18em] text-white/50">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@elseif ($block->type === ContentBlockType::Cta)
    <x-public.cta-band
        :eyebrow="$block->eyebrow ?: 'Next step'"
        :title="$block->title ?: 'Get involved.'"
        :copy="$block->plainExcerpt()"
    >
        @if ($block->hasLink())
            <a href="{{ $block->linkHref() }}" class="btn btn-gold btn-lg btn-pill">{{ $block->linkText() }}</a>
        @endif
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg btn-pill">Contact the office</a>
    </x-public.cta-band>
@endif
