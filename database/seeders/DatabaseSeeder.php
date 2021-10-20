<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') !== 'testing') {
            $this->call(RolePermissionSeeder::class);
            $this->call(UsersSeeder::class);
			$this->call(SchoolSeeder::class);
        }
    }
}
