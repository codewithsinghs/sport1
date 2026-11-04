<?php

namespace App\Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Association\AssociationContext;
use Illuminate\Contracts\View\View;

class DistrictDirectoryController extends Controller
{
    public function index(AssociationContext $context): View
    {
        $association = $context->require();

        $districts = $association->districts()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('public.districts.index', [
            'association' => $association,
            'districts' => $districts,
            'districtCount' => $districts->count(),
        ]);
    }

    public function show(AssociationContext $context, string $district): View
    {
        $association = $context->require();

        $districts = $association->districts()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $record = $districts->firstWhere('slug', $district);

        abort_if($record === null, 404);

        $position = $districts->search(fn ($item) => $item->is($record));

        return view('public.districts.show', [
            'association' => $association,
            'district' => $record,
            'districtCount' => $districts->count(),
            'previous' => $position > 0 ? $districts[$position - 1] : null,
            'next' => $position < $districts->count() - 1 ? $districts[$position + 1] : null,
        ]);
    }
}
