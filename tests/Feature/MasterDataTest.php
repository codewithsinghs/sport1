<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Association\Livewire\Profile;
use App\Modules\Association\Models\Association;
use App\Modules\Association\Models\AssociationSport;
use App\Modules\Association\Services\AssociationSettings;
use App\Modules\Core\Enums\UserRole;
use App\Modules\Geography\Livewire\DistrictIndex;
use App\Modules\Geography\Models\State;
use App\Modules\Membership\Livewire\MembershipCategoryIndex;
use App\Modules\Sport\Livewire\CategoryIndex;
use App\Modules\Sport\Livewire\DisciplineIndex;
use App\Modules\Sport\Livewire\SportIndex;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private Association $association;

    private Sport $sport;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $state = State::query()->create([
            'name' => 'Madhya Pradesh',
            'code' => 'MP',
            'country_code' => 'IN',
            'is_active' => true,
        ]);

        $this->association = $this->makeAssociation([
            'name' => 'Master Association',
            'state_id' => $state->getKey(),
        ]);

        $this->sport = Sport::query()->create([
            'name' => 'Targetball',
            'slug' => 'targetball',
            'is_active' => true,
        ]);

        AssociationSport::query()->create([
            'association_id' => $this->association->getKey(),
            'sport_id' => $this->sport->getKey(),
            'is_active' => true,
        ]);

        $this->admin = $this->makeUser($this->association);

        $this->useAssociation($this->association);
        $this->actingAs($this->admin);
    }

    public function test_admin_can_create_a_discipline_for_the_current_association(): void
    {
        Livewire::test(DisciplineIndex::class)
            ->set('sportId', $this->sport->getKey())
            ->set('name', 'Singles')
            ->set('description', 'Singles competition discipline')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('disciplines', [
            'association_id' => $this->association->getKey(),
            'sport_id' => $this->sport->getKey(),
            'name' => 'Singles',
            'slug' => 'singles',
        ]);
    }

    public function test_admin_can_create_a_category_for_an_association_discipline(): void
    {
        $discipline = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard',
            'slug' => 'standard',
        ]);

        Livewire::test(CategoryIndex::class)
            ->set('disciplineId', $discipline->getKey())
            ->set('name', 'Boys Under-14')
            ->set('gender', 'male')
            ->set('minAge', '')
            ->set('maxAge', '14')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'association_id' => $this->association->getKey(),
            'discipline_id' => $discipline->getKey(),
            'name' => 'Boys Under-14',
            'gender' => 'male',
            'max_age' => 14,
        ]);
    }

    public function test_admin_can_create_a_membership_category(): void
    {
        Livewire::test(MembershipCategoryIndex::class)
            ->set('name', 'Annual Member')
            ->set('fee', '1000')
            ->set('billingPeriod', 'annual')
            ->set('requiresApproval', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('membership_categories', [
            'association_id' => $this->association->getKey(),
            'name' => 'Annual Member',
            'fee' => 1000,
            'billing_period' => 'annual',
            'requires_approval' => true,
        ]);
    }

    public function test_association_admin_cannot_manage_global_district_master(): void
    {
        $this->assertFalse($this->admin->can('create', \App\Modules\Geography\Models\District::class));

        Livewire::test(DistrictIndex::class)
            ->call('create')
            ->assertForbidden();
    }

    public function test_super_admin_can_create_a_district(): void
    {
        $superAdmin = $this->makeUser($this->association, UserRole::SuperAdmin);
        $this->actingAs($superAdmin);

        Livewire::test(DistrictIndex::class)
            ->set('name', 'Bhopal')
            ->set('code', 'BPL')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('districts', [
            'state_id' => $this->association->state_id,
            'name' => 'Bhopal',
            'slug' => 'bhopal',
            'code' => 'BPL',
        ]);
    }

    public function test_admin_can_toggle_a_sport_for_the_current_association(): void
    {
        Livewire::test(SportIndex::class)
            ->call('toggle', $this->sport->getKey())
            ->assertHasNoErrors();

        $this->assertDatabaseHas('association_sports', [
            'association_id' => $this->association->getKey(),
            'sport_id' => $this->sport->getKey(),
            'is_active' => false,
        ]);

        $this->assertDatabaseMissing('association_sports', [
            'association_id' => $this->association->getKey(),
            'sport_id' => $this->sport->getKey(),
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_association_branding_and_profile(): void
    {
        Livewire::test(Profile::class)
            ->set('name', 'Updated Association')
            ->set('shortName', 'UPD')
            ->set('primaryColor', '#123456')
            ->set('secondaryColor', '#abcdef')
            ->set('about', 'Updated public introduction.')
            ->call('save')
            ->assertHasNoErrors();

        $this->association->refresh();

        $this->assertSame('Updated Association', $this->association->name);
        $this->assertSame('UPD', $this->association->short_name);
        $this->assertSame('#123456', $this->association->setting(AssociationSettings::PRIMARY_COLOR));
        $this->assertSame('#abcdef', $this->association->setting(AssociationSettings::SECONDARY_COLOR));
        $this->assertSame('Updated public introduction.', $this->association->setting(AssociationSettings::ABOUT));
    }
}
