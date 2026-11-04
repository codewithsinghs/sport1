<div>
    <x-page-header
        title="Membership categories"
        subtitle="Association-specific membership types, fees and approval settings."
    >
        @can('create', App\Modules\Membership\Models\MembershipCategory::class)
            <button type="button" wire:click="create" class="btn btn-brand btn-sm">Add membership category</button>
        @endcan
    </x-page-header>

    <div class="card overflow-hidden">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-sm font-semibold text-slate-900">{{ $categories->total() }} membership categories</p>
                <p class="text-xs text-slate-500">Fees and approval requirements apply only to this association.</p>
            </div>
            <div class="w-full sm:w-72">
                <input type="search" wire:model.live.debounce.300ms="search" class="input" placeholder="Search categories" aria-label="Search membership categories">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="table-head">
                    <tr>
                        <th class="px-5 py-3 sm:px-6">Category</th>
                        <th class="px-5 py-3">Fee</th>
                        <th class="px-5 py-3">Billing</th>
                        <th class="px-5 py-3">Approval</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right sm:px-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($categories as $category)
                        <tr wire:key="membership-category-{{ $category->id }}">
                            <td class="px-5 py-3.5 sm:px-6">
                                <p class="font-medium text-slate-900">{{ $category->name }}</p>
                                <p class="max-w-md truncate text-xs text-slate-500">{{ $category->description ?: $category->slug }}</p>
                            </td>
                            <td class="px-5 py-3.5 font-medium text-slate-900">{{ $category->feeLabel() }}</td>
                            <td class="px-5 py-3.5 text-slate-600">{{ $category->billingPeriodLabel() }}</td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $category->requires_approval ? 'badge-amber' : 'badge-slate' }}">
                                    {{ $category->requires_approval ? 'Required' : 'Automatic' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge {{ $category->is_active ? 'badge-green' : 'badge-slate' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right sm:px-6">
                                <div class="flex items-center justify-end gap-2">
                                    @can('update', $category)
                                        <button type="button" wire:click="edit({{ $category->id }})" class="btn btn-secondary btn-sm">Edit</button>
                                    @endcan
                                    @can('delete', $category)
                                        <button
                                            type="button"
                                            wire:click="delete({{ $category->id }})"
                                            wire:confirm="Remove the {{ $category->name }} membership category?"
                                            class="btn btn-danger btn-sm"
                                        >Delete</button>
                                    @endcan
                                    @cannot('update', $category)
                                        <span class="text-xs text-slate-400">Read only</span>
                                    @endcannot
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500 sm:px-6">
                                No membership categories configured yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="border-t border-slate-200 px-5 py-4 sm:px-6">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <x-form-modal :open="$showForm" :title="$editingId ? 'Edit membership category' : 'Add membership category'">
        <form wire:submit="save" class="space-y-5">
            <div>
                <label for="membership-name" class="label">Category name</label>
                <input id="membership-name" type="text" class="input" wire:model="name" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="membership-fee" class="label">Fee (₹)</label>
                    <input id="membership-fee" type="number" min="0" step="0.01" class="input" wire:model="fee" required>
                    @error('fee')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="membership-billing" class="label">Billing period</label>
                    <select id="membership-billing" class="input" wire:model="billingPeriod">
                        @foreach (App\Modules\Membership\Models\MembershipCategory::BILLING_PERIODS as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('billingPeriod')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="membership-description" class="label">Description <span class="font-normal text-slate-400">(optional)</span></label>
                <textarea id="membership-description" rows="3" class="input" wire:model="description"></textarea>
                @error('description')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-3">
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" wire:model="requiresApproval" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                    Membership requires approval
                </label>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" wire:model="isActive" class="rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                    Active category
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-200 pt-4">
                <button type="button" wire:click="closeForm" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-brand" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editingId ? 'Save changes' : 'Create category' }}</span>
                    <span wire:loading>Saving&hellip;</span>
                </button>
            </div>
        </form>
    </x-form-modal>
</div>
