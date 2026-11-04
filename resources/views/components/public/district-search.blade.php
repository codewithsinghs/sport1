@props(['districts', 'stateName'])

<div id="district-directory" class="mt-10">
    <div class="flex flex-col gap-4 rounded-2xl border border-line bg-white p-4 shadow-soft sm:flex-row sm:items-center sm:justify-between sm:p-5">
        <div class="relative w-full sm:max-w-sm">
            <label for="district-search" class="sr-only">Search districts</label>
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path stroke-linecap="round" d="m20 20-3.5-3.5" />
            </svg>
            <input
                id="district-search"
                type="search"
                data-district-search
                class="input pl-10"
                placeholder="Search by district name or code"
                autocomplete="off"
            >
        </div>

        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500" data-district-count aria-live="polite">
            {{ $districts->count() }} {{ \Illuminate\Support\Str::plural('district', $districts->count()) }}
        </p>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($districts as $district)
            @php($initial = mb_strtoupper(mb_substr($district->name, 0, 1)))
            <article
                data-district
                data-search="{{ \Illuminate\Support\Str::lower($district->name.' '.$district->code) }}"
            >
                <a href="{{ route('districts.show', $district->slug) }}" class="luxe-card group flex h-full flex-col p-5 transition duration-300 ease-swift hover:-translate-y-0.5 hover:shadow-lift">
                    <div class="flex items-start justify-between gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gold/30 bg-cream/70 font-display text-sm font-bold text-gold">
                            {{ $initial }}
                        </span>
                        @if ($district->code)
                            <span class="badge badge-slate">{{ $district->code }}</span>
                        @endif
                    </div>

                    <h3 class="mt-5 font-display text-lg font-semibold text-ink-950">{{ $district->name }}</h3>
                    <p class="mt-1 text-xs font-medium uppercase tracking-[0.12em] text-slate-400">{{ $stateName }}</p>

                    <span class="mt-auto inline-flex items-center gap-1.5 pt-6 text-xs font-bold uppercase tracking-[0.14em] text-ink-950 transition group-hover:gap-2.5 group-hover:text-gold">
                        District page
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </span>
                </a>
            </article>
        @endforeach
    </div>

    <div class="mt-6 hidden rounded-2xl border border-dashed border-line bg-white p-10 text-center" data-district-empty>
        <p class="font-display text-lg font-semibold text-ink-950">No district matches that search.</p>
        <p class="mx-auto mt-2 max-w-sm text-sm leading-relaxed text-slate-500">
            Try a shorter name or clear the search to see all {{ $districts->count() }} districts.
        </p>
    </div>
</div>

@push('scripts')
    <script>
        (() => {
            const root = document.getElementById('district-directory');
            if (!root) return;

            const input = root.querySelector('[data-district-search]');
            const items = Array.from(root.querySelectorAll('[data-district]'));
            const count = root.querySelector('[data-district-count]');
            const empty = root.querySelector('[data-district-empty]');

            const filter = () => {
                const query = input.value.trim().toLowerCase();
                let shown = 0;

                items.forEach((item) => {
                    const matches = item.dataset.search.includes(query);
                    item.classList.toggle('hidden', !matches);
                    if (matches) shown += 1;
                });

                const label = shown === 1 ? 'district' : 'districts';
                count.textContent = query
                    ? `${shown} of ${items.length} ${label}`
                    : `${items.length} ${label}`;
                empty.classList.toggle('hidden', shown > 0);
            };

            input.addEventListener('input', filter);
        })();
    </script>
@endpush
