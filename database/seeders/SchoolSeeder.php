<?php

namespace Database\Seeders;

use App\Models\Grade;
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
            [
                'name' => 'โรงเรียนเทศบาลตำบลงิม (คือเวียงจ่ำ)',
                'subjects' => [
                    ['id' => 1, 'name' => 'phonics'],
                ]
            ],
            [
                'name' => 'โรงเรียนอนุบาล เทศบาลตำบลจุน',
                'subjects' => [
                    ['id' => 1, 'name' => 'phonics'],
                ]
            ],
            [
                'name' => 'Tessaban 5',
                'subjects' => [
                    ['id' => 1, 'name' => 'phonics'],
                    ['id' => 2, 'name' => 'english'],
                    ['id' => 3, 'name' => 'maths'],
                    ['id' => 4, 'name' => 'sci'],
                    ['id' => 5, 'name' => 'arts'],
                    ['id' => 6, 'name' => 'pe'],
                ]
            ],
            [
                'name' => 'Tessaban 4',
                'subjects' => [
                    ['id' => 1, 'name' => 'phonics'],
                ]
            ],
        ];
        foreach ($schools as $school) {
            School::factory()->create([
                'name' => $school['name'],
                'subjects' => $school['subjects']
            ]);
        }
        $schools = School::all();
        $types = [Grade::NURSERY_TYPE, Grade::KINDERGATEN_TYPE, Grade::PRIMARY_TYPE];
        foreach ($schools as $school) {
            foreach ($types as $type) {
                if ($type === Grade::NURSERY_TYPE) {
                    Grade::create([
                        'school_id' => $school->id,
                        'type' => $type,
                        'level' => 1,
                        'room_number' => 1
                    ]);
                    Grade::create([
                        'school_id' => $school->id,
                        'type' => $type,
                        'level' => 1,
                        'room_number' => 2
                    ]);
                }
                if ($type === Grade::KINDERGATEN_TYPE) {
                    Grade::create([
                        'school_id' => $school->id,
                        'type' => $type,
                        'level' => 1,
                        'room_number' => 1
                    ]);
                    Grade::create([
                        'school_id' => $school->id,
                        'type' => $type,
                        'level' => 1,
                        'room_number' => 2
                    ]);
                }
                if ($type === Grade::PRIMARY_TYPE) {
                    $levels = [1, 2, 3, 4, 5, 6];
                    $roomNumbers = [1, 2];
                    foreach ($levels as $level) {
                        foreach ($roomNumbers as $roomNumber) {
                            Grade::create([
                                'school_id' => $school->id,
                                'type' => $type,
                                'level' => $level,
                                'room_number' => $roomNumber
                            ]);
                        }
                    }
                }
            }
        }
    }
}
