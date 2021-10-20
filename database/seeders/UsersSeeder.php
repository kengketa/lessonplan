<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
        ]);
        $superAdmin->assignRole(Role::where('name', \App\Models\Role::ROLE_SUPER_ADMIN)->first());

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
        ]);
        $admin->assignRole(Role::where('name', \App\Models\Role::ROLE_ADMIN)->first());

        for ($i = 1; $i < 30; $i++) {
            $user = User::factory()->create([
                'name' => 'User ' . $i,
                'email' => 'user' . $i . '@mail.com',
            ]);
            $user->assignRole(Role::where("name", \App\Models\Role::ROLE_USER)->first());
        }
    }
}
