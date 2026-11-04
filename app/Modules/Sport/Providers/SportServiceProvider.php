<?php

namespace App\Modules\Sport\Providers;

use App\Modules\Sport\Livewire\CategoryIndex;
use App\Modules\Sport\Livewire\DisciplineIndex;
use App\Modules\Sport\Livewire\SportIndex;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use App\Modules\Sport\Policies\CategoryPolicy;
use App\Modules\Sport\Policies\DisciplinePolicy;
use App\Modules\Sport\Policies\SportPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class SportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sport');

        Livewire::component('admin.sports.index', SportIndex::class);
        Livewire::component('admin.disciplines.index', DisciplineIndex::class);
        Livewire::component('admin.categories.index', CategoryIndex::class);

        Gate::policy(Sport::class, SportPolicy::class);
        Gate::policy(Discipline::class, DisciplinePolicy::class);
        Gate::policy(Category::class, CategoryPolicy::class);
    }
}
