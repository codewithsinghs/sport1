<?php

namespace App\Modules\Membership\Providers;

use App\Modules\Membership\Livewire\MembershipCategoryIndex;
use App\Modules\Membership\Models\MembershipCategory;
use App\Modules\Membership\Policies\MembershipCategoryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class MembershipServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'membership');

        Livewire::component('admin.membership-categories.index', MembershipCategoryIndex::class);

        Gate::policy(MembershipCategory::class, MembershipCategoryPolicy::class);
    }
}
