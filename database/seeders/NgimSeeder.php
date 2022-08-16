<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NgimSeeder extends Seeder
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
                'name' => 'โรงเรียนเทศบาลตำบลงิม (คือเวียงจำ)',
                'subjects' => [
                    ['id' => 1, 'name' => 'Phonics'],
                ],
                'lat' => 19.268215614863088,
                'lng' => 100.36667601784593,
                'radius' => 100
            ],
        ];
        foreach ($schools as $school) {
            School::factory()->create([
                'name' => $school['name'],
                'subjects' => $school['subjects'],
                'lat' => $school['lat'],
                'lng' => $school['lng'],
                'radius' => $school['radius']
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
