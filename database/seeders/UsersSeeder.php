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
        $admin = User::factory()->create([
            'name' => 'Sutjapong',
            'email' => 'kengketa@gmail.com',
        ]);
        $admin->assignRole(Role::where('name', \App\Models\Role::ROLE_ADMIN)->first());

        $admin = User::factory()->create([
            'name' => 'Kamonlaphat',
            'email' => 'robo_hanaba@hotmail.com',
        ]);
        $admin->assignRole(Role::where('name', \App\Models\Role::ROLE_ADMIN)->first());

        if (config('app.env') === 'local') {
            for ($i = 1; $i < 10; $i++) {
                $teacher = User::factory()->create([
                    'name' => 'Teacher '.$i,
                    'email' => 'teacher'.$i.'@mail.com',
                ]);
                $teacher->assignRole(Role::where("name", \App\Models\Role::ROLE_TEACHER)->first());
            }
        }
    }
}
