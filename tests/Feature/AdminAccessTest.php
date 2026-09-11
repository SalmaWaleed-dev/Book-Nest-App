<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_away_from_admin_routes(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_a_normal_user_cannot_access_the_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_a_normal_user_cannot_access_admin_users_list(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }

    public function test_an_admin_can_access_the_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
    }

    public function test_role_tampering_on_profile_update_does_not_grant_admin(): void
    {
        // ProfileRequest doesn't accept a 'role' field at all, so even a
        // tampered request body can't touch it.
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->put('/profile', [
            'interests' => 'Testing',
            'role' => 'admin',
        ]);

        $this->assertSame('user', $user->fresh()->role);
    }
}
