<?php

namespace Database\Seeders;

use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\AssociationContext;
use App\Modules\Membership\Models\MembershipCategory;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use Illuminate\Database\Seeder;

class AssociationConfigSeeder extends Seeder
{
    public function run(): void
    {
        $association = Association::query()->where('slug', 'mpta')->firstOrFail();

        app(AssociationContext::class)->run($association, function (): void {
            $sport = Sport::query()->where('slug', 'targetball')->firstOrFail();

            $discipline = Discipline::query()->updateOrCreate(
                [
                    'sport_id' => $sport->getKey(),
                    'slug' => 'standard',
                ],
                [
                    'name' => 'Standard',
                    'description' => 'Standard Targetball competition discipline.',
                    'is_active' => true,
                ]
            );

            foreach ($this->categories() as $category) {
                Category::query()->updateOrCreate(
                    [
                        'discipline_id' => $discipline->getKey(),
                        'slug' => $category['slug'],
                    ],
                    [
                        'name' => $category['name'],
                        'gender' => $category['gender'],
                        'min_age' => $category['min_age'],
                        'max_age' => $category['max_age'],
                        'is_active' => true,
                    ]
                );
            }

            foreach ($this->membershipCategories() as $category) {
                MembershipCategory::query()->updateOrCreate(
                    ['slug' => $category['slug']],
                    [
                        'name' => $category['name'],
                        'fee' => $category['fee'],
                        'billing_period' => $category['billing_period'],
                        'requires_approval' => $category['requires_approval'],
                        'description' => $category['description'],
                        'is_active' => true,
                    ]
                );
            }
        });
    }

    /**
     * @return list<array{name: string, slug: string, gender: string, min_age: ?int, max_age: ?int}>
     */
    private function categories(): array
    {
        return [
            ['name' => 'Open', 'slug' => 'open', 'gender' => 'any', 'min_age' => null, 'max_age' => null],
            ['name' => 'Boys Under-14', 'slug' => 'boys-under-14', 'gender' => 'male', 'min_age' => null, 'max_age' => 14],
            ['name' => 'Girls Under-14', 'slug' => 'girls-under-14', 'gender' => 'female', 'min_age' => null, 'max_age' => 14],
            ['name' => 'Boys Under-17', 'slug' => 'boys-under-17', 'gender' => 'male', 'min_age' => null, 'max_age' => 17],
            ['name' => 'Girls Under-17', 'slug' => 'girls-under-17', 'gender' => 'female', 'min_age' => null, 'max_age' => 17],
        ];
    }

    /**
     * @return list<array{name: string, slug: string, fee: string, billing_period: string, requires_approval: bool, description: string}>
     */
    private function membershipCategories(): array
    {
        return [
            [
                'name' => 'Life Member',
                'slug' => 'life-member',
                'fee' => '5000.00',
                'billing_period' => 'lifetime',
                'requires_approval' => true,
                'description' => 'Lifetime association membership.',
            ],
            [
                'name' => 'Annual Member',
                'slug' => 'annual-member',
                'fee' => '1000.00',
                'billing_period' => 'annual',
                'requires_approval' => true,
                'description' => 'Annual association membership.',
            ],
            [
                'name' => 'Player',
                'slug' => 'player',
                'fee' => '300.00',
                'billing_period' => 'annual',
                'requires_approval' => true,
                'description' => 'Player registration category.',
            ],
            [
                'name' => 'Official',
                'slug' => 'official',
                'fee' => '500.00',
                'billing_period' => 'annual',
                'requires_approval' => true,
                'description' => 'Technical official registration category.',
            ],
        ];
    }
}
