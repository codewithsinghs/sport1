<?php

namespace App\Modules\Association\Providers;

use App\Modules\Association\Livewire\Profile;
use App\Modules\Association\Models\Association;
use App\Modules\Association\Policies\AssociationPolicy;
use App\Modules\Association\Services\AssociationSettings;
use App\Modules\Core\Association\AssociationContext;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AssociationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'association');

        Livewire::component('association.profile', Profile::class);

        Gate::policy(Association::class, AssociationPolicy::class);

        View::composer(['layouts.admin', 'layouts.public', 'layouts.auth'], function ($view): void {
            $association = app(AssociationContext::class)->association();

            $view->with([
                'currentAssociation' => $association,
                'branding' => $association
                    ? app(AssociationSettings::class)->all($association)
                    : [],
            ]);
        });
    }
}
