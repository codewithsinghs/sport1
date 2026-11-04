@props(['class' => ''])

<svg
    {{ $attributes->merge(['class' => $class]) }}
    viewBox="0 0 560 560"
    fill="none"
    aria-hidden="true"
    focusable="false"
>
    <circle cx="280" cy="280" r="268" stroke="currentColor" stroke-opacity="0.08" />
    <circle cx="280" cy="280" r="226" stroke="currentColor" stroke-opacity="0.16" stroke-width="1.5" stroke-dasharray="2 10" />
    <circle cx="280" cy="280" r="186" stroke="color-mix(in srgb, var(--brand) 68%, white)" stroke-opacity="0.55" stroke-width="18" />
    <circle cx="280" cy="280" r="186" stroke="white" stroke-opacity="0.14" stroke-width="1" />
    <circle cx="280" cy="280" r="132" stroke="var(--brand-accent)" stroke-opacity="0.9" stroke-width="10" />
    <circle cx="280" cy="280" r="76" fill="color-mix(in srgb, var(--brand) 70%, white)" fill-opacity="0.18" />
    <circle cx="280" cy="280" r="76" stroke="white" stroke-opacity="0.18" />
    <circle cx="280" cy="280" r="26" fill="var(--brand-accent)" />
    <circle cx="280" cy="280" r="9" fill="white" fill-opacity="0.92" />
    <path d="M74 470C168 396 228 322 296 250" stroke="var(--brand-accent)" stroke-opacity="0.45" stroke-width="2" stroke-dasharray="5 9" />
    <circle cx="74" cy="470" r="8" fill="white" fill-opacity="0.65" />
    <circle cx="74" cy="470" r="3" fill="var(--brand-accent)" />
</svg>
