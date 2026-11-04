<?php

namespace App\Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use App\Modules\PublicSite\Support\PublicContent;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.about', [
            'association' => $association,
            'sports' => $association->enabledSports()->orderBy('name')->get(),
            'districtCount' => $this->districtCount($association),
            'membershipCategories' => $association->membershipCategories()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'disciplineCount' => Discipline::query()->where('is_active', true)->count(),
            'categoryCount' => Category::query()->where('is_active', true)->count(),
            'featuredDistricts' => $association->districts()
                ->where('is_active', true)
                ->orderBy('name')
                ->limit(12)
                ->get(),
            'milestones' => PublicContent::milestones(),
            'championship' => PublicContent::championship(),
            'gallery' => array_slice(PublicContent::gallery(), 0, 6),
        ]);
    }

    public function governance(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.governance', [
            'association' => $association,
            'districtCount' => $this->districtCount($association),
            'sportsCount' => $association->enabledSports()->count(),
        ]);
    }

    public function membership(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.membership', [
            'association' => $association,
            'membershipCategories' => $association->membershipCategories()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'districtCount' => $this->districtCount($association),
        ]);
    }

    public function contact(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.contact', [
            'association' => $association,
            'districtCount' => $this->districtCount($association),
        ]);
    }

    public function events(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.events', [
            'association' => $association,
            'championship' => PublicContent::championship(),
            'milestones' => PublicContent::milestones(),
        ]);
    }

    public function results(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.results', [
            'association' => $association,
            'championship' => PublicContent::championship(),
        ]);
    }

    public function news(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.news', [
            'association' => $association,
            'story' => PublicContent::championshipStory(),
            'championship' => PublicContent::championship(),
        ]);
    }

    public function gallery(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.gallery', [
            'association' => $association,
            'photos' => PublicContent::gallery(),
            'championship' => PublicContent::championship(),
        ]);
    }

    public function downloads(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.downloads', [
            'association' => $association,
        ]);
    }

    public function register(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.register', [
            'association' => $association,
            'membershipCategories' => $association->membershipCategories()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'districtCount' => $this->districtCount($association),
        ]);
    }

    public function privacy(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.privacy', [
            'association' => $association,
        ]);
    }

    public function terms(AssociationContext $context): View
    {
        $association = $context->require();

        return view('public.terms', [
            'association' => $association,
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
