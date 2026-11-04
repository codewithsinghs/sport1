<?php

namespace Database\Seeders;

use App\Modules\Association\Models\Association;
use App\Modules\Association\Models\AssociationDomain;
use App\Modules\Association\Services\AssociationSettings;
use App\Modules\Geography\Models\State;
use App\Modules\Sport\Models\Sport;
use Illuminate\Database\Seeder;

class AssociationSeeder extends Seeder
{
    public function run(): void
    {
        $state = State::query()->where('code', 'MP')->firstOrFail();
        $sport = Sport::query()->where('slug', 'targetball')->firstOrFail();

        $association = Association::query()->updateOrCreate(
            ['slug' => 'mpta'],
            [
                'name' => 'Madhya Pradesh Targetball Association',
                'short_name' => 'MPTA',
                'state_id' => $state->getKey(),
                'email' => 'info@mpta.test',
                'phone' => null,
                'website' => null,
                'address' => 'Bhopal, Madhya Pradesh, India',
                'is_active' => true,
            ]
        );

        $association->sports()->syncWithoutDetaching([
            $sport->getKey() => ['is_active' => true],
        ]);

        foreach (['localhost', '127.0.0.1', 'mpta.test'] as $index => $domain) {
            AssociationDomain::query()->firstOrCreate(
                ['domain' => $domain],
                [
                    'association_id' => $association->getKey(),
                    'is_primary' => $index === 2,
                ]
            );
        }

        app(AssociationSettings::class)->setMany($association, [
            AssociationSettings::ABOUT => 'Official state association for the promotion, development and administration of Targetball across all districts of Madhya Pradesh.',
            AssociationSettings::PRIMARY_COLOR => '#0f766e',
            AssociationSettings::SECONDARY_COLOR => '#f59e0b',
            AssociationSettings::LOGO_PATH => null,
            AssociationSettings::INSTAGRAM_URL => 'https://www.instagram.com/madhyapradeshtargetball/',
            AssociationSettings::NATIONAL_BODY_URL => 'https://www.targetballindia.com',
        ]);
    }
}
