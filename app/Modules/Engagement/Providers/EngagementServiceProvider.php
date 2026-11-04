<?php

namespace App\Modules\Engagement\Providers;

use App\Modules\Engagement\Models\ContactLead;
use App\Modules\Engagement\Policies\ContactLeadPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class EngagementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'engagement');

        Gate::policy(ContactLead::class, ContactLeadPolicy::class);
    }
}
