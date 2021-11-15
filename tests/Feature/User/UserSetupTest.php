<?php

namespace Tests\Feature\Dashboard\User;

use App\Models\Role;
use App\Models\User;
use App\Notifications\WelcomeEmailNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\Assert;
use Tests\Setup\RefreshDatabase;
use Tests\TestCase;

class UserSetupTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_user()
    {
        $this->signInAdmin();
        // Create a user
        $user = User::factory()->make();
        $response = $this->post(route('dashboard.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => User::ROLE_ADMIN,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function test_new_user_token_link_is_valid()
    {
        $this->signInAdmin();
        // generate a dummy user
        $user = User::factory()->make();
        $this->post(route('dashboard.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => User::ROLE_ADMIN,
        ]);

        $token = DB::table('user_setups')->where('user_id', 2)->first();
        //This link will be called by a user to get password setup view
        $link = '/setup-password/'.$token->token.'/'.$token->user_id;
        $response = $this->get($link);
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
    }

    public function test_new_password_can_be_set_by_a_new_user()
    {
        $this->signInAdmin();
        // generate a dummy user
        $user = User::factory()->make();
        $this->post(route('dashboard.users.store'), [
            'name' => $user->name,
            'email' => $user->email,
            'role' => User::ROLE_ADMIN,
        ]);
        //the system generate a link and a token for a new user
        $token = DB::table('user_setups')->where('user_id', 2)->first();
        //This link will be called by a user to get password setup view
        $link = '/setup-password/'.$token->token.'/'.$token->user_id;
        $this->get($link);

        // user set his password
        $response = $this->post(route('user-setup.save'), [
            'password' => 'testpassword',
            'password_confirmation' => 'testpassword',
            'token' => $token->token,
            'user_id' => $token->user_id,
            'id' => $token->id,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('testpassword', User::find($token->user_id)->password));
    }
}
