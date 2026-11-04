<?php

namespace App\Modules\Roster\Providers;

use App\Modules\Roster\Models\DistrictTeamMember;
use App\Modules\Roster\Models\Player;
use App\Modules\Roster\Models\StateTeamMember;
use App\Modules\Roster\Policies\DistrictTeamMemberPolicy;
use App\Modules\Roster\Policies\PlayerPolicy;
use App\Modules\Roster\Policies\StateTeamMemberPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RosterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'roster');

        Gate::policy(Player::class, PlayerPolicy::class);
        Gate::policy(DistrictTeamMember::class, DistrictTeamMemberPolicy::class);
        Gate::policy(StateTeamMember::class, StateTeamMemberPolicy::class);
    }
}
