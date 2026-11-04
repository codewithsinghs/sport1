<div>
    <div class="flex items-center gap-3">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl" style="background: var(--brand-soft); color: var(--brand-deep)">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="4" y="10" width="16" height="10" rx="2" />
                <path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v2" />
            </svg>
        </span>
        <p class="eyebrow" style="color: var(--brand-deep)">Secure access</p>
    </div>

    <h2 class="mt-5 font-display text-2xl font-semibold tracking-tight text-ink-950">Sign in to your workspace</h2>
    <p class="mt-2 muted">Use your association administration account.</p>

    <form wire:submit="login" class="mt-7 space-y-5">
        <div>
            <label for="email" class="label">Email address</label>
            <input
                id="email"
                type="email"
                class="input"
                wire:model="email"
                autocomplete="username"
                placeholder="name@association.org"
                autofocus
                required
            >
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="label">Password</label>
            <input
                id="password"
                type="password"
                class="input"
                wire:model="password"
                autocomplete="current-password"
                placeholder="Enter your password"
                required
            >
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2.5 text-sm text-slate-600">
            <input type="checkbox" wire:model="remember" class="h-4 w-4 rounded border-line" style="accent-color: var(--brand)">
            Remember this device
        </label>

        <button type="submit" class="btn btn-brand btn-lg w-full" wire:loading.attr="disabled">
            <span wire:loading.remove class="inline-flex items-center gap-2">
                Sign in
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </span>
            <span wire:loading>Signing in&hellip;</span>
        </button>
    </form>

    <div class="mt-7 flex items-start gap-2.5 border-t border-line pt-5 text-xs leading-relaxed text-slate-500">
        <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M12 3l7 3v6c0 4-3 7-7 8-4-1-7-4-7-8V6z" />
            <path d="M9 12.5 11 14.5 15.5 10" />
        </svg>
        <p>Access is limited to active accounts belonging to the association resolved for this site.</p>
    </div>
</div>
