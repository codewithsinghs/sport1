<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Association\Models\Association;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use App\Modules\Membership\Models\MembershipCategory;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_builds_the_initial_association(): void
    {
        $this->seed();

        $association = Association::query()->where('slug', 'mpta')->firstOrFail();
        $this->useAssociation($association);

        $this->assertSame('Madhya Pradesh Targetball Association', $association->name);
        $this->assertSame('MPTA', $association->short_name);
        $this->assertSame('Madhya Pradesh', State::query()->find($association->state_id)?->name);

        $this->assertSame(55, District::query()->count());
        $this->assertTrue($association->enabledSports()->where('slug', 'targetball')->exists());
        $this->assertSame(1, Discipline::query()->count());
        $this->assertSame(5, Category::query()->count());
        $this->assertSame(4, MembershipCategory::query()->count());

        $this->assertDatabaseHas('association_domains', [
            'association_id' => $association->getKey(),
            'domain' => 'mpta.test',
        ]);

        $this->assertSame(3, User::query()->count());
        $this->assertTrue(User::query()->where('email', 'super@mpta.test')->firstOrFail()->isSuperAdmin());
        $this->assertTrue(User::query()->where('email', 'admin@mpta.test')->firstOrFail()->isAssociationAdmin());
        $this->assertTrue(User::query()->where('email', 'staff@mpta.test')->firstOrFail()->isStaff());
    }
}
