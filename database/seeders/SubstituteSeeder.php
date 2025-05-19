<?php

namespace Database\Seeders;

use App\Models\Substitute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Substitute::factory()->count(10)->create();
    }
}
