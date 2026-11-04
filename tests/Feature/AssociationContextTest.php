<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Association\Models\AssociationDomain;
use App\Modules\Core\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssociationContextTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'association.allow_config_fallback' => false,
            'association.default_slug' => null,
        ]);
    }

    public function test_public_site_resolves_association_from_a_registered_domain(): void
    {
        $association = $this->makeAssociation(['name' => 'Domain Association']);

        AssociationDomain::query()->create([
            'association_id' => $association->getKey(),
            'domain' => 'mpta.test',
        ]);

        $this->get('http://mpta.test/')
            ->assertOk()
            ->assertSee('Domain Association');
    }

    public function test_public_site_can_use_the_trusted_server_side_fallback(): void
    {
        $this->makeAssociation(['name' => 'Fallback Association', 'slug' => 'fallback-association']);

        config([
            'association.allow_config_fallback' => true,
            'association.default_slug' => 'fallback-association',
        ]);

        $this->get('http://unregistered.test/')
            ->assertOk()
            ->assertSee('Fallback Association');
    }

    public function test_public_site_returns_not_found_without_a_trusted_context(): void
    {
        $this->makeAssociation(['name' => 'Unreachable Association']);

        $this->get('http://unknown.test/')->assertNotFound();
    }

    public function test_authenticated_user_context_wins_over_the_request_domain(): void
    {
        $associationA = $this->makeAssociation(['name' => 'Association Alpha']);
        $associationB = $this->makeAssociation(['name' => 'Association Beta']);

        AssociationDomain::query()->create([
            'association_id' => $associationB->getKey(),
            'domain' => 'beta.test',
        ]);

        $user = $this->makeUser($associationA);

        $this->actingAs($user)
            ->get('http://beta.test/admin')
            ->assertOk()
            ->assertSee('Association Alpha')
            ->assertDontSee('Association Beta');
    }

    public function test_authenticated_user_without_an_association_cannot_access_admin(): void
    {
        $user = User::query()->create([
            'name' => 'Global Admin',
            'email' => 'global@example.test',
            'password' => 'password',
            'role' => UserRole::SuperAdmin->value,
            'association_id' => null,
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get('http://unknown.test/admin')
            ->assertNotFound();
    }

    public function test_public_request_parameters_cannot_switch_the_association(): void
    {
        $associationA = $this->makeAssociation(['name' => 'Request Association']);
        $associationB = $this->makeAssociation(['name' => 'Other Association']);

        AssociationDomain::query()->create([
            'association_id' => $associationA->getKey(),
            'domain' => 'request.test',
        ]);

        $this->get("http://request.test/?association_id={$associationB->getKey()}")
            ->assertOk()
            ->assertSee('Request Association')
            ->assertDontSee('Other Association');
    }
}
