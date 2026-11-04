<?php

namespace App\Modules\Admin\Livewire;

use App\Modules\Core\Association\AssociationContext;
use App\Modules\Geography\Models\District;
use App\Modules\Membership\Models\MembershipCategory;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $association = app(AssociationContext::class)->require();

        $stats = [
            [
                'label' => 'Districts',
                'value' => $association->state_id
                    ? District::query()->where('state_id', $association->state_id)->count()
                    : 0,
                'route' => route('admin.districts.index'),
            ],
            [
                'label' => 'Enabled sports',
                'value' => $association->enabledSports()->count(),
                'route' => route('admin.sports.index'),
            ],
            [
                'label' => 'Disciplines',
                'value' => Discipline::query()->count(),
                'route' => route('admin.disciplines.index'),
            ],
            [
                'label' => 'Categories',
                'value' => Category::query()->count(),
                'route' => route('admin.categories.index'),
            ],
            [
                'label' => 'Membership categories',
                'value' => MembershipCategory::query()->count(),
                'route' => route('admin.membership-categories.index'),
            ],
        ];

        return view('admin::livewire.dashboard', [
            'association' => $association,
            'stats' => $stats,
            'sports' => $association->enabledSports()->orderBy('name')->get(),
        ]);
    }
}
