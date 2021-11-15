<?php

namespace Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signInAdmin($user = null)
    {
        $user = $user ?? User::factory()->create(['name' => 'test', 'email' => 'testcase@test.com']);
        $role = Role::where('name', Role::ROLE_ADMIN)->first();
        $user->assignRole($role);

        return $this->signIn($user);
    }

    protected function signInSuperAdmin($user = null)
    {
        $user = $user ?? User::factory()->create(['name' => 'test', 'email' => 'testcase@test.com']);
        $role = Role::where('name', Role::ROLE_SUPER_ADMIN)->first();
        $user->assignRole($role);

        return $this->signIn($user);
    }

    protected function signInUser($user = null)
    {
        $user = $user ?? User::factory()->create(['name' => 'test', 'email' => 'testcase@test.com']);
        $role = Role::where('name', Role::ROLE_USER)->first();
        $user->assignRole($role);

        return $this->signIn($user);
    }

    protected function signInTeacher($user = null)
    {
        $user = $user ?? User::factory()->create(['name' => 'test', 'email' => 'testcase@test.com']);
        $role = Role::where('name', Role::ROLE_TEACHER)->first();
        $user->assignRole($role);

        return $this->signIn($user);
    }

    protected function signIn($user)
    {
        $this->actingAs($user);

        return $user;
    }

    protected function signOut()
    {
        Auth::logout();
    }
}
