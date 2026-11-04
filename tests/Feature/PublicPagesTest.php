<?php

namespace Tests\Feature;

use App\Modules\Association\Models\Association;
use App\Modules\Association\Models\AssociationDomain;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use App\Modules\Sport\Models\Sport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private Association $association;

    private State $state;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'association.allow_config_fallback' => false,
            'association.default_slug' => null,
        ]);

        $this->state = State::query()->create([
            'name' => 'Test State',
            'code' => 'TS',
            'country_code' => 'IN',
            'is_active' => true,
        ]);

        $this->association = $this->makeAssociation([
            'name' => 'Pages Association',
            'short_name' => 'PA',
            'state_id' => $this->state->getKey(),
        ]);

        AssociationDomain::query()->create([
            'association_id' => $this->association->getKey(),
            'domain' => 'pages.test',
        ]);
    }

    public function test_public_information_pages_render(): void
    {
        foreach ([
            '/',
            '/about',
            '/governance',
            '/events',
            '/results',
            '/news',
            '/gallery',
            '/downloads',
            '/register',
            '/privacy',
            '/terms',
            '/membership',
            '/contact',
            '/districts',
            '/sports',
        ] as $path) {
            $this->get('http://pages.test'.$path)
                ->assertOk();
        }
    }

    public function test_gallery_exposes_championship_photography(): void
    {
        $this->get('http://pages.test/gallery')
            ->assertOk()
            ->assertSee('gallery-01-sm.webp')
            ->assertSee('Opening march past, Tatya Tope Stadium');
    }

    public function test_district_directory_is_scoped_to_the_association_state(): void
    {
        District::query()->create([
            'state_id' => $this->state->getKey(),
            'name' => 'Home District',
            'slug' => 'home-district',
            'code' => 'HD',
            'is_active' => true,
        ]);

        $otherState = State::query()->create([
            'name' => 'Other State',
            'code' => 'OS',
            'country_code' => 'IN',
            'is_active' => true,
        ]);

        District::query()->create([
            'state_id' => $otherState->getKey(),
            'name' => 'Foreign District',
            'slug' => 'foreign-district',
            'code' => 'FD',
            'is_active' => true,
        ]);

        $this->get('http://pages.test/districts')
            ->assertOk()
            ->assertSee('Home District')
            ->assertDontSee('Foreign District');

        $this->get('http://pages.test/districts/home-district')->assertOk();
        $this->get('http://pages.test/districts/foreign-district')->assertNotFound();
    }

    public function test_sport_pages_are_limited_to_sports_enabled_for_the_association(): void
    {
        $sport = Sport::query()->create([
            'name' => 'Targetball',
            'slug' => 'targetball',
            'description' => 'The association sport.',
            'is_active' => true,
        ]);

        $this->association->sports()->attach($sport->getKey(), ['is_active' => true]);

        $this->get('http://pages.test/sports/targetball')
            ->assertOk()
            ->assertSee('Targetball');

        $otherSport = Sport::query()->create([
            'name' => 'Other Sport',
            'slug' => 'other-sport',
            'is_active' => true,
        ]);

        $otherAssociation = $this->makeAssociation([
            'name' => 'Other Association',
            'short_name' => 'OA',
        ]);

        $otherAssociation->sports()->attach($otherSport->getKey(), ['is_active' => true]);

        $this->get('http://pages.test/sports/other-sport')->assertNotFound();
    }

    public function test_public_pages_require_a_trusted_association_context(): void
    {
        $this->get('http://unknown.test/events')->assertNotFound();
        $this->get('http://unknown.test/privacy')->assertNotFound();
        $this->get('http://unknown.test/gallery')->assertNotFound();
    }
}
