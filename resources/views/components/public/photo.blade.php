@props([
    'name',
    'alt',
    'width',
    'height',
    'smallWidth' => 800,
    'sizes' => '100vw',
    'eager' => false,
])

<img
    src="{{ asset('images/site/'.$name.'.webp') }}"
    srcset="{{ asset('images/site/'.$name.'-sm.webp') }} {{ $smallWidth }}w, {{ asset('images/site/'.$name.'.webp') }} {{ $width }}w"
    sizes="{{ $sizes }}"
    width="{{ $width }}"
    height="{{ $height }}"
    alt="{{ $alt }}"
    @if ($eager) fetchpriority="high" @else loading="lazy" @endif
    decoding="async"
    {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }}
>
