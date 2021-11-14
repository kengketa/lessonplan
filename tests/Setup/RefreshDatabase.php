<?php

namespace Tests\Setup;

use Illuminate\Foundation\Testing\RefreshDatabase as BaseRefreshDatabase;

/** extend RefreshDatabase trait to run seed after migration */
trait RefreshDatabase
{
    use BaseRefreshDatabase {
        refreshDatabase as baseRefreshDatabase;
    }

    public function refreshDatabase()
    {
        $this->baseRefreshDatabase();
        $this->artisan('db:seed --class=RolePermissionSeeder');
    }
}
