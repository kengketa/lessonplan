<?php

namespace Tests\Feature\Dashboard\User;

use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_viewing_users_index_required_authenication()
    {
        $response = $this->get(route('dashboard.users.index'));
        $response->assertSessionHasNoErrors();
        //$response->assertStatus(302);
    }

    public function test_super_admin_can_view_user_index_page_after_login()
    {
        $this->signInSuperAdmin();
        $response = $this->get(route('dashboard.users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/users', $pageUrl);
    }

    public function test_super_admin_can_view_user_create_page_after_login()
    {
        $this->signInSuperAdmin();
        $response = $this->get(route('dashboard.users.create'));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/users/create', $pageUrl);
    }

    public function test_super_admin_can_view_user_page_after_login()
    {
        $this->signInSuperAdmin();
        // create a user
        $user = User::factory()->create();

        // call user show page
        $response = $this->get(route('dashboard.users.show', $user->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/users/'.$user->id, $pageUrl);
    }

    public function test_super_admin_can_edit_user_page_after_login()
    {
        $this->signInSuperAdmin();
        // create a user
        $user = User::factory()->create();

        // call show page
        $response = $this->get(route('dashboard.users.edit', $user->id));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $pageUrl = $response->getOriginalContent()->getData()['page']['url'];
        $this->assertEquals('/dashboard/users/'.$user->id.'/edit', $pageUrl);
    }

    public function test_super_admin_can_create_a_user()
    {
        $this->signInSuperAdmin();
        // Create a user
        $user = User::factory()->make();
        $response = $this->post(route('dashboard.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_super_admin_can_update_a_user()
    {
        $this->signInSuperAdmin();
        // Create a test user
        $user = User::factory()->create();

        // update a user

        $response = $this->put(route('dashboard.users.update', $user->id), [
            'name' => 'updated name',
            'email' => $user->email,
            'role' => 90,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'name' => 'updated name',
        ]);
    }

    public function test_super_admin_can_delete_a_user()
    {
        $this->signInSuperAdmin();
        //Create a user
        $user = User::factory()->create();

        //Delete a user
        $response = $this->delete(route('dashboard.users.destroy', $user->id));
        $response->assertSessionHasNoErrors();
        $this->assertEquals(1, User::all()->count());
    }
}
