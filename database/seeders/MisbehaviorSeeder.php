<?php

namespace Database\Seeders;

use App\Models\Misbehavior;

use Illuminate\Database\Seeder;

class MisbehaviorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Misbehavior::factory()->count(50)->create();
    }
}
