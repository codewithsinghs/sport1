<?php

namespace App\Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use Illuminate\Contracts\View\View;

class SportDirectoryController extends Controller
{
    public function index(AssociationContext $context): View
    {
        $association = $context->require();

        $sports = $association->enabledSports()
            ->with([
                'disciplines' => fn ($query) => $query->where('is_active', true),
                'disciplines.categories' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();

        return view('public.sports.index', [
            'association' => $association,
            'sports' => $sports,
            'sportCount' => $sports->count(),
            'districtCount' => $this->districtCount($association),
        ]);
    }

    public function show(AssociationContext $context, string $sport): View
    {
        $association = $context->require();

        $record = $association->enabledSports()
            ->where('slug', $sport)
            ->with([
                'disciplines' => fn ($query) => $query->where('is_active', true)->orderBy('name'),
                'disciplines.categories' => fn ($query) => $query->where('is_active', true)->orderBy('name'),
            ])
            ->first();

        abort_if($record === null, 404);

        return view('public.sports.show', [
            'association' => $association,
            'sport' => $record,
            'categories' => $record->disciplines->flatMap->categories,
            'districtCount' => $this->districtCount($association),
            'membershipCategories' => $association->membershipCategories()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    private function districtCount(Association $association): int
    {
        if ($association->state_id === null) {
            return 0;
        }

        return District::query()
            ->where('state_id', $association->state_id)
            ->where('is_active', true)
            ->count();
    }
}
