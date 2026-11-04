<?php

namespace App\Modules\Programme\Providers;

use App\Modules\Programme\Livewire\DistrictAssociationIndex;
use App\Modules\Programme\Livewire\EventEntryIndex;
use App\Modules\Programme\Livewire\EventForm;
use App\Modules\Programme\Livewire\EventIndex;
use App\Modules\Programme\Livewire\EventMediaIndex;
use App\Modules\Programme\Models\DistrictAssociation;
use App\Modules\Programme\Models\Event;
use App\Modules\Programme\Models\EventEntry;
use App\Modules\Programme\Models\EventMedia;
use App\Modules\Programme\Policies\DistrictAssociationPolicy;
use App\Modules\Programme\Policies\EventEntryPolicy;
use App\Modules\Programme\Policies\EventMediaPolicy;
use App\Modules\Programme\Policies\EventPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ProgrammeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'programme');

        Livewire::component('admin.district-bodies.index', DistrictAssociationIndex::class);
        Livewire::component('admin.events.index', EventIndex::class);
        Livewire::component('admin.events.form', EventForm::class);
        Livewire::component('admin.events.entries', EventEntryIndex::class);
        Livewire::component('admin.events.media', EventMediaIndex::class);

        Gate::policy(Event::class, EventPolicy::class);
        Gate::policy(DistrictAssociation::class, DistrictAssociationPolicy::class);
        Gate::policy(EventEntry::class, EventEntryPolicy::class);
        Gate::policy(EventMedia::class, EventMediaPolicy::class);
    }
}
