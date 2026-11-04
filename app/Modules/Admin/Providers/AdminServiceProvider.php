<?php

namespace App\Modules\Admin\Providers;

use App\Modules\Admin\Livewire\Dashboard;
use App\Modules\Admin\Livewire\Login;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');

        Livewire::component('admin.login', Login::class);
        Livewire::component('admin.dashboard', Dashboard::class);
    }
}
