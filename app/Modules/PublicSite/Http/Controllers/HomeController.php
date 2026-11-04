<?php

namespace App\Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;

class HomeController extends Controller
{
    public function __invoke(AssociationContext $context)
    {
        $association = $context->require();

        return view('public.home', [
            'association' => $association,
            'sports' => $association->enabledSports()->orderBy('name')->get(),
            'districtCount' => $association->state_id !== null
                ? District::query()
                    ->where('state_id', $association->state_id)
                    ->where('is_active', true)
                    ->count()
                : 0,
            'membershipCategories' => $association->membershipCategories()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'disciplineCount' => Discipline::query()->count(),
            'categoryCount' => Category::query()->count(),
        ]);
    }
}
