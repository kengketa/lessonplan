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
        if (config('app.env') == 'local') {

            $this->call(RolePermissionSeeder::class);
            $this->call(UsersSeeder::class);
            $this->call(SettingSeeder::class);
            $this->call(SchoolSeeder::class);
            $this->call(ReportSeeder::class);
            $this->call(ClockInSeeder::class);
            $this->call(MeetingSeeder::class);
            $this->call(AgendaSeeder::class);
        }
        if (config('app.env') == 'production') {
            $this->call(RolePermissionSeeder::class);
            $this->call(UsersSeeder::class);
            $this->call(SettingSeeder::class);
            $this->call(SchoolSeeder::class);
        }
        if (config('app.env') == 'ngim-trial') {
            $this->call(RolePermissionSeeder::class);
            $this->call(UsersSeeder::class);
            $this->call(SettingSeeder::class);
            $this->call(NgimSeeder::class);
        }
    }
}
