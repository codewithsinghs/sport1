<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Admin\Livewire\Login;
use App\Modules\Association\Models\Association;
use App\Modules\Core\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private Association $association;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->association = $this->makeAssociation();
        $this->user = $this->makeUser($this->association);
    }

    public function test_active_user_can_sign_in(): void
    {
        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->user);
    }

    public function test_inactive_user_cannot_sign_in(): void
    {
        $this->user->update(['is_active' => false]);

        Livewire::test(Login::class)
            ->set('email', $this->user->email)
            ->set('password', 'password')
            ->call('login')
            ->assertHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_sign_out(): void
    {
        $this->actingAs($this->user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_deactivated_session_is_terminated_on_the_next_request(): void
    {
        $this->actingAs($this->user);

        $this->user->update(['is_active' => false]);

        $this->get('http://localhost/admin')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
