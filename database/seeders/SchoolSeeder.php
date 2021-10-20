<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = [
            ['name' => 'โรงเรียนเทศบาลตำบลงิม (คือเวียงจ่ำ)'],
            ['name' => 'โรงเรียนอนุบาล เทศบาลตำบลจุน'],
            ['name' => 'Tessaban 5'],
            ['name' => 'Tessaban 4'],
        ];
        foreach ($schools as $school) {
            School::factory()->create([
                'name' => $school['name']
            ]);
        }
    }
}
