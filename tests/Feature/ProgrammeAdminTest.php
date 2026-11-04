<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Enums\UserRole;
use App\Modules\Geography\Models\District;
use App\Modules\Geography\Models\State;
use App\Modules\Programme\Enums\DistrictAssociationStatus;
use App\Modules\Programme\Enums\EventStatus;
use App\Modules\Programme\Livewire\DistrictAssociationIndex;
use App\Modules\Programme\Livewire\EventForm;
use App\Modules\Programme\Livewire\EventIndex;
use App\Modules\Programme\Models\DistrictAssociation;
use App\Modules\Programme\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProgrammeAdminTest extends TestCase
{
    use RefreshDatabase;

    private Association $association;

    private District $district;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $state = State::query()->create(['name' => 'Madhya Pradesh', 'code' => 'MP', 'country_code' => 'IN']);

        $this->association = $this->makeAssociation(['state_id' => $state->getKey()]);
        $this->district = District::query()->create([
            'state_id' => $state->getKey(),
            'name' => 'Bhopal',
            'slug' => 'bhopal',
            'is_active' => true,
        ]);
        $this->admin = $this->makeUser($this->association, UserRole::AssociationAdmin);

        $this->useAssociation($this->association);
    }

    public function test_district_body_can_be_recorded_and_supervised(): void
    {
        Livewire::actingAs($this->admin)
            ->test(DistrictAssociationIndex::class)
            ->call('create')
            ->set('districtId', $this->district->getKey())
            ->set('name', 'Targetball Association Bhopal')
            ->set('contactName', 'Anil Yadav')
            ->set('email', 'bhopal@example.test')
            ->set('status', DistrictAssociationStatus::Active->value)
            ->set('playersRegistered', '48')
            ->set('supervisionNotes', 'Affiliation fee cleared.')
            ->call('save')
            ->assertHasNoErrors();

        $body = DistrictAssociation::query()->first();

        $this->assertNotNull($body);
        $this->assertSame($this->association->getKey(), $body->association_id);
        $this->assertTrue($body->isPublic());
        $this->assertSame(48, $body->players_registered);
    }

    public function test_a_district_can_only_hold_one_body_per_association(): void
    {
        DistrictAssociation::query()->create([
            'district_id' => $this->district->getKey(),
            'name' => 'Existing body',
            'status' => DistrictAssociationStatus::Pending->value,
        ]);

        Livewire::actingAs($this->admin)
            ->test(DistrictAssociationIndex::class)
            ->call('create')
            ->set('districtId', $this->district->getKey())
            ->set('name', 'Duplicate body')
            ->call('save')
            ->assertHasErrors('districtId');

        $this->assertSame(1, DistrictAssociation::query()->count());
    }

    public function test_staff_cannot_record_district_bodies(): void
    {
        $staff = $this->makeUser($this->association, UserRole::Staff);

        Livewire::actingAs($staff)
            ->test(DistrictAssociationIndex::class)
            ->call('create')
            ->assertForbidden();
    }

    public function test_event_form_creates_a_draft_with_sanitised_description(): void
    {
        Livewire::actingAs($this->admin)
            ->test(EventForm::class)
            ->set('title', 'Bhopal District Championship')
            ->set('slug', 'bhopal-district-championship')
            ->set('startsAt', now()->addDays(30)->format('Y-m-d\TH:i'))
            ->set('summary', 'District selection tournament.')
            ->set('description', '<p>Good</p><script>alert(1)</script><p onclick="evil()">Copy</p>')
            ->set('venueName', 'Tatya Tope Stadium')
            ->call('save')
            ->assertHasNoErrors();

        $event = Event::query()->first();

        $this->assertNotNull($event);
        $this->assertSame(EventStatus::Draft, $event->status);
        $this->assertStringNotContainsString('script', $event->description);
        $this->assertStringNotContainsString('onclick', $event->description);
        $this->assertStringContainsString('<p>Good</p>', $event->description);
    }

    public function test_event_publish_requires_completed_details(): void
    {
        $event = $this->makeEvent();

        Livewire::actingAs($this->admin)
            ->test(EventIndex::class)
            ->call('publish', $event->getKey());

        $this->assertSame('Add a short summary, a venue before publishing.', session('error'));
        $this->assertSame(EventStatus::Draft, $event->refresh()->status);
    }

    public function test_workflow_moves_an_event_from_draft_to_published(): void
    {
        $event = $this->makeEvent();
        $event->update(['summary' => 'District selection meet.', 'venue_name' => 'Tatya Tope Stadium']);

        Livewire::actingAs($this->admin)
            ->test(EventIndex::class)
            ->call('submit', $event->getKey())
            ->call('approve', $event->getKey())
            ->call('publish', $event->getKey());

        $event->refresh();

        $this->assertSame(EventStatus::Published, $event->status);
        $this->assertNotNull($event->published_at);
        $this->assertSame($this->admin->getKey(), $event->approved_by);
    }

    public function test_events_of_another_association_are_not_reachable(): void
    {
        $other = $this->makeAssociation(['slug' => 'other-association']);
        $this->useAssociation($other);
        $otherEvent = Event::query()->create([
            'title' => 'Other State Open',
            'slug' => 'other-state-open',
            'event_type' => 'tournament',
            'level' => 'state',
            'status' => EventStatus::Published->value,
            'starts_at' => now()->addDays(10),
            'published_at' => now(),
        ]);

        $this->useAssociation($this->association);

        Livewire::actingAs($this->admin)
            ->test(EventIndex::class)
            ->assertDontSee('Other State Open');

        $this->assertSame(0, Event::query()->whereKey($otherEvent->getKey())->count());
    }

    private function makeEvent(): Event
    {
        return Event::query()->create([
            'title' => 'District Championship',
            'slug' => 'district-championship',
            'event_type' => 'tournament',
            'level' => 'district',
            'status' => EventStatus::Draft->value,
            'starts_at' => now()->addDays(21),
        ]);
    }
}
