<?php

namespace App\Modules\Content\Providers;

use App\Modules\Content\Models\ContentBlock;
use App\Modules\Content\Policies\ContentBlockPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ContentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'content');

        Gate::policy(ContentBlock::class, ContentBlockPolicy::class);
    }
}
