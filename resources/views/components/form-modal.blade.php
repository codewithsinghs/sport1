@props(['title', 'open' => false])

@if ($open)
    <div
        class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-ink-950/65 p-4 backdrop-blur-sm sm:items-center"
        x-data="{
            lastFocused: null,
            init() {
                this.lastFocused = document.activeElement;
                this.$nextTick(() => {
                    const panel = this.$refs.panel;
                    const field = panel?.querySelector('input:not([type=hidden]):not([disabled]), select:not([disabled]), textarea:not([disabled])');
                    const fallback = panel?.querySelector('button:not([disabled]), a[href]');
                    (field || fallback)?.focus();
                });
            },
            destroy() {
                this.lastFocused?.focus();
            },
            dismiss() {
                $wire.closeForm();
            },
            trap(event) {
                if (event.key !== 'Tab' || !this.$refs.panel) return;
                const items = Array.from(this.$refs.panel.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex=&quot;-1&quot;])'))
                    .filter((el) => el.offsetParent !== null);
                if (items.length === 0) return;
                const first = items[0];
                const last = items[items.length - 1];
                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        }"
        @keydown="trap($event)"
        @keydown.escape.window="dismiss()"
    >
        <div x-ref="panel" class="w-full max-w-2xl overflow-hidden rounded-3xl border border-line bg-white shadow-lift" role="dialog" aria-modal="true" aria-label="{{ $title }}">
            <div class="flex items-center justify-between gap-4 border-b border-line px-5 py-4 sm:px-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Configuration</p>
                    <h2 class="section-title mt-1">{{ $title }}</h2>
                </div>
                <button type="button" @click="dismiss()" class="rounded-xl border border-line p-2 text-slate-500 transition hover:border-slate-300 hover:bg-paper hover:text-slate-700" aria-label="Close">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <div class="p-5 sm:p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
