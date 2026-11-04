<?php

use App\Modules\Admin\Providers\AdminServiceProvider;
use App\Modules\Association\Providers\AssociationServiceProvider;
use App\Modules\Content\Providers\ContentServiceProvider;
use App\Modules\Core\Providers\CoreServiceProvider;
use App\Modules\Engagement\Providers\EngagementServiceProvider;
use App\Modules\Geography\Providers\GeographyServiceProvider;
use App\Modules\Membership\Providers\MembershipServiceProvider;
use App\Modules\Programme\Providers\ProgrammeServiceProvider;
use App\Modules\Roster\Providers\RosterServiceProvider;
use App\Modules\Sport\Providers\SportServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CoreServiceProvider::class,
    AssociationServiceProvider::class,
    GeographyServiceProvider::class,
    SportServiceProvider::class,
    MembershipServiceProvider::class,
    ProgrammeServiceProvider::class,
    EngagementServiceProvider::class,
    ContentServiceProvider::class,
    RosterServiceProvider::class,
    AdminServiceProvider::class,
];
