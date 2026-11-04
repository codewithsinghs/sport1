<?php

namespace App\Modules\Core\Providers;

use App\Modules\Core\Association\AssociationContext;
use App\Modules\Core\Http\Middleware\EnsureAssociationContext;
use App\Modules\Core\Http\Middleware\EnsureUserIsActive;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class CoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AssociationContext::class);
    }

    public function boot(): void
    {
        // Livewire update requests bypass the original route middleware unless
        // the middleware is explicitly marked as persistent.
        Livewire::addPersistentMiddleware([
            EnsureAssociationContext::class,
            EnsureUserIsActive::class,
        ]);
    }
}
