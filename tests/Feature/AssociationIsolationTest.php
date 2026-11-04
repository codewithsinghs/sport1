<?php

namespace Tests\Feature;

use App\Modules\Association\Models\Association;
use App\Modules\Core\Association\MissingAssociationContext;
use App\Modules\Core\Enums\UserRole;
use App\Modules\Sport\Livewire\CategoryIndex;
use App\Modules\Sport\Models\Category;
use App\Modules\Sport\Models\Discipline;
use App\Modules\Sport\Models\Sport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use Tests\TestCase;

class AssociationIsolationTest extends TestCase
{
    use RefreshDatabase;

    private Association $associationA;

    private Association $associationB;

    private Sport $sport;

    protected function setUp(): void
    {
        parent::setUp();

        $this->associationA = $this->makeAssociation(['name' => 'Association A']);
        $this->associationB = $this->makeAssociation(['name' => 'Association B']);

        $this->sport = Sport::query()->create([
            'name' => 'Targetball',
            'slug' => 'targetball',
            'is_active' => true,
        ]);
    }

    public function test_association_id_is_assigned_from_the_resolved_context(): void
    {
        $this->useAssociation($this->associationA);

        $discipline = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard',
            'slug' => 'standard',
        ]);

        $this->assertSame($this->associationA->getKey(), $discipline->association_id);
    }

    public function test_mass_assignment_cannot_override_the_association(): void
    {
        $this->useAssociation($this->associationA);

        $discipline = Discipline::query()->create([
            'association_id' => $this->associationB->getKey(),
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard',
            'slug' => 'standard',
        ]);

        $this->assertSame($this->associationA->getKey(), $discipline->association_id);
        $this->assertDatabaseHas('disciplines', [
            'id' => $discipline->getKey(),
            'association_id' => $this->associationA->getKey(),
        ]);
    }

    public function test_queries_are_scoped_to_the_current_association(): void
    {
        $this->useAssociation($this->associationA);
        $disciplineA = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard A',
            'slug' => 'standard-a',
        ]);

        $this->useAssociation($this->associationB);
        $disciplineB = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard B',
            'slug' => 'standard-b',
        ]);

        $this->useAssociation($this->associationA);

        $this->assertSame(
            [$disciplineA->getKey()],
            Discipline::query()->pluck('id')->all()
        );
        $this->assertNull(Discipline::query()->find($disciplineB->getKey()));
    }

    public function test_querying_without_context_throws(): void
    {
        $this->clearAssociationContext();

        $this->expectException(MissingAssociationContext::class);

        Discipline::query()->count();
    }

    public function test_policies_deny_cross_association_and_staff_changes(): void
    {
        $this->useAssociation($this->associationA);
        $disciplineA = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard A',
            'slug' => 'standard-a',
        ]);

        $this->useAssociation($this->associationB);
        $disciplineB = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard B',
            'slug' => 'standard-b',
        ]);

        $admin = $this->makeUser($this->associationA);
        $staff = $this->makeUser($this->associationA, UserRole::Staff);

        $this->useAssociation($this->associationA);
        $this->assertTrue(Gate::forUser($admin)->allows('update', $disciplineA));
        $this->assertFalse(Gate::forUser($admin)->allows('update', $disciplineB));
        $this->assertTrue(Gate::forUser($staff)->allows('view', $disciplineA));
        $this->assertFalse(Gate::forUser($staff)->allows('update', $disciplineA));

        $this->useAssociation($this->associationB);
        $this->assertFalse(Gate::forUser($admin)->allows('update', $disciplineA));
    }

    public function test_livewire_cannot_open_a_cross_association_record(): void
    {
        $this->useAssociation($this->associationA);
        $disciplineA = Discipline::query()->create([
            'sport_id' => $this->sport->getKey(),
            'name' => 'Standard A',
            'slug' => 'standard-a',
        ]);
        $categoryA = Category::query()->create([
            'discipline_id' => $disciplineA->getKey(),
            'name' => 'Open A',
            'slug' => 'open-a',
            'gender' => 'any',
        ]);

        $adminB = $this->makeUser($this->associationB);

        $this->useAssociation($this->associationB);
        $this->actingAs($adminB);

        $this->expectException(ModelNotFoundException::class);

        Livewire::test(CategoryIndex::class)->call('edit', $categoryA->getKey());
    }
}
