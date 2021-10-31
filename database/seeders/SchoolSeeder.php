<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolTeacher;
use App\Models\User;
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
                'name' => 'โรงเรียนเทศบาลตำบลงิม (คือเวียงจำ)',
                'subjects' => [
                    ['id' => 1, 'name' => 'Phonics'],
                ],
                'lat' => 19.268215614863088,
                'lng' => 100.36667601784593,
                'radius' => 100
            ],
            [
                'name' => 'โรงเรียนอนุบาล เทศบาลตำบลจุน',
                'subjects' => [
                    ['id' => 1, 'name' => 'Phonics'],
                ],
                'lat' => 19.322821504714376,
                'lng' => 100.14763174247288,
                'radius' => 50
            ],
            [
                'name' => 'Tessaban 5',
                'subjects' => [
                    ['id' => 2, 'name' => 'English'],
                    ['id' => 3, 'name' => 'Maths'],
                    ['id' => 4, 'name' => 'Sci'],
                    ['id' => 5, 'name' => 'Arts'],
                    ['id' => 6, 'name' => 'Pe'],
                ],
                'lat' => 19.17920454012667,
                'lng' => 99.89263306186193,
                'radius' => 50
            ],
            [
                'name' => 'Tessaban 4',
                'subjects' => [
                    ['id' => 1, 'name' => 'Phonics'],
                ],
                'lat' => 19.144656507192565,
                'lng' => 99.90755073014793,
                'radius' => 50
            ],
            [
                'name' => 'Fakkwan wittayakhom',
                'subjects' => [
                    ['id' => 1, 'name' => 'English conversation'],
                ],
                'lat' => 19.131614186054755,
                'lng' => 99.8228504357287,
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
        if (config('app.env') === 'local') {
            $teachers = User::role(Role::ROLE_TEACHER)->get();
            //Seed mock teacher in schools
            foreach ($schools as $school) {
                for ($i = 0; $i < 5; $i++) {
                    $teacherId = $teachers->random()->id;
                    $foundTeacher = SchoolTeacher::where('school_id', $school->id)
                        ->where('teacher_id', $teacherId)->first();
                    if ($foundTeacher) {
                        continue;
                    }
                    SchoolTeacher::factory()->create([
                        'school_id' => $school->id,
                        'teacher_id' => $teacherId
                    ]);
                }
            }
        }
    }
}
