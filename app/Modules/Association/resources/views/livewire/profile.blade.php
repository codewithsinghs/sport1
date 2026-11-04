<div>
    <x-page-header
        title="Profile & branding"
        subtitle="Public identity, contact details and branding for this association."
    >
        @if ($canEdit)
            <button type="submit" form="association-profile-form" class="btn btn-brand btn-sm" wire:loading.attr="disabled">
                <span wire:loading.remove>Save changes</span>
                <span wire:loading>Saving&hellip;</span>
            </button>
        @endif
    </x-page-header>

    @unless ($canEdit)
        <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Your role has read-only access to the association profile.
        </div>
    @endunless

    <form id="association-profile-form" wire:submit="save" class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="card">
                <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                    <h2 class="section-title">Association details</h2>
                    <p class="muted mt-1">Displayed on the admin workspace and public website.</p>
                </div>
                <div class="card-body space-y-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="profile-name" class="label">Association name</label>
                            <input id="profile-name" type="text" class="input" wire:model="name" @disabled(! $canEdit) required>
                            @error('name')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-short-name" class="label">Short name</label>
                            <input id="profile-short-name" type="text" class="input" wire:model="shortName" @disabled(! $canEdit)>
                            @error('shortName')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-email" class="label">Contact email</label>
                            <input id="profile-email" type="email" class="input" wire:model="email" @disabled(! $canEdit)>
                            @error('email')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-phone" class="label">Contact phone</label>
                            <input id="profile-phone" type="text" class="input" wire:model="phone" @disabled(! $canEdit)>
                            @error('phone')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-website" class="label">Website</label>
                            <input id="profile-website" type="url" class="input" wire:model="website" placeholder="https://" @disabled(! $canEdit)>
                            @error('website')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="profile-address" class="label">Address</label>
                            <textarea id="profile-address" rows="2" class="input" wire:model="address" @disabled(! $canEdit)></textarea>
                            @error('address')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label for="profile-about" class="label">About the association</label>
                            <textarea id="profile-about" rows="5" class="input" wire:model="about" @disabled(! $canEdit) placeholder="A short introduction shown on the public website."></textarea>
                            @error('about')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                    <h2 class="section-title">Branding</h2>
                    <p class="muted mt-1">Logo and colours are applied to the admin shell and public site.</p>
                </div>
                <div class="card-body space-y-6">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="h-full w-full object-contain p-2">
                            @elseif ($existingLogoUrl)
                                <img src="{{ $existingLogoUrl }}" alt="{{ $association->name }} logo" class="h-full w-full object-contain p-2">
                            @else
                                <span class="text-2xl font-bold text-white" style="background-color: var(--brand); padding: 1.75rem 1.5rem;">
                                    {{ $association->initials() }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <label for="profile-logo" class="label">Logo</label>
                                <input id="profile-logo" type="file" class="input file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700" wire:model="logo" accept="image/*" @disabled(! $canEdit)>
                                <p class="mt-1 text-xs text-slate-500">PNG, JPG or WebP up to 2 MB.</p>
                                @error('logo')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                                <p wire:loading wire:target="logo" class="mt-1 text-xs text-slate-500">Uploading&hellip;</p>
                            </div>
                            @if ($existingLogoUrl || $logo)
                                <label class="flex items-center gap-2 text-sm text-slate-700">
                                    <input type="checkbox" wire:model="removeLogo" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400" @disabled(! $canEdit)>
                                    Remove the current logo
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="profile-primary-color" class="label">Primary colour</label>
                            <div class="flex items-center gap-3">
                                <input id="profile-primary-color" type="color" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300 bg-white p-1" wire:model="primaryColor" @disabled(! $canEdit)>
                                <span class="text-sm font-medium text-slate-600">{{ $primaryColor }}</span>
                            </div>
                            @error('primaryColor')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-secondary-color" class="label">Accent colour</label>
                            <div class="flex items-center gap-3">
                                <input id="profile-secondary-color" type="color" class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300 bg-white p-1" wire:model="secondaryColor" @disabled(! $canEdit)>
                                <span class="text-sm font-medium text-slate-600">{{ $secondaryColor }}</span>
                            </div>
                            @error('secondaryColor')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                    <h2 class="section-title">Public site</h2>
                    <p class="muted mt-1">The public website resolves the association from these trusted domains.</p>
                </div>
                <div class="card-body">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="profile-instagram" class="label">Instagram</label>
                            <input id="profile-instagram" type="url" class="input" wire:model="instagramUrl" placeholder="https://www.instagram.com/" @disabled(! $canEdit)>
                            @error('instagramUrl')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile-national-body" class="label">National body website</label>
                            <input id="profile-national-body" type="url" class="input" wire:model="nationalBodyUrl" placeholder="https://" @disabled(! $canEdit)>
                            @error('nationalBodyUrl')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Registered domains</p>
                        @if ($domains->isEmpty())
                            <p class="mt-2 text-sm text-slate-500">No public domains are registered. The configured default association will be used when enabled.</p>
                        @else
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach ($domains as $domain)
                                    <span class="badge badge-slate">{{ $domain }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <p class="mt-4 text-xs text-slate-500">
                        Domain mappings are server-managed to prevent users from selecting another association through request input.
                    </p>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card overflow-hidden">
                <div class="px-5 py-5 text-white" style="background: linear-gradient(135deg, var(--brand) 0%, #0f172a 90%);">
                    <div class="flex items-center gap-3">
                        @if ($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="" class="h-12 w-12 rounded-lg bg-white object-contain p-1">
                        @elseif ($existingLogoUrl)
                            <img src="{{ $existingLogoUrl }}" alt="" class="h-12 w-12 rounded-lg bg-white object-contain p-1">
                        @else
                            <span class="flex h-12 w-12 items-center justify-center rounded-lg bg-white/10 text-sm font-bold">{{ $association->initials() }}</span>
                        @endif
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold">{{ $shortName ?: $association->short_name ?: $association->name }}</p>
                            <p class="truncate text-xs text-white/60">{{ $association->state?->name ?? 'State not configured' }}</p>
                        </div>
                    </div>
                    <p class="mt-5 text-lg font-semibold leading-snug">{{ $name }}</p>
                    <p class="mt-2 text-xs leading-relaxed text-white/70">{{ \Illuminate\Support\Str::limit($about ?: 'No public introduction configured yet.', 120) }}</p>
                </div>
                <div class="space-y-3 px-5 py-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="h-4 w-4 rounded-full border border-white/40" style="background-color: {{ $primaryColor }}"></span>
                        <span class="text-slate-600">Primary {{ $primaryColor }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-4 w-4 rounded-full border border-white/40" style="background-color: {{ $secondaryColor }}"></span>
                        <span class="text-slate-600">Accent {{ $secondaryColor }}</span>
                    </div>
                    <div class="border-t border-slate-100 pt-3 text-slate-500">
                        <p>{{ $email ?: 'No email configured' }}</p>
                        <p class="mt-1">{{ $phone ?: 'No phone configured' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 text-sm text-slate-600">
                <p class="font-semibold text-slate-900">Isolation reminder</p>
                <p class="mt-2 leading-relaxed">
                    This profile belongs only to {{ $association->name }}. Association context is resolved from the authenticated user or a trusted public domain, never from request parameters.
                </p>
            </div>
        </aside>
    </form>
</div>
