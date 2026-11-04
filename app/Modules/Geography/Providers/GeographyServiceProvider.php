<?php

namespace App\Modules\Geography\Providers;

use App\Modules\Geography\Livewire\DistrictIndex;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Policies\DistrictPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class GeographyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'geography');

        Livewire::component('admin.districts.index', DistrictIndex::class);

        Gate::policy(District::class, DistrictPolicy::class);
    }
}
