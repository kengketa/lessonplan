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
                    ['id' => 1, 'name' => 'English'],
                    ['id' => 2, 'name' => 'วิทยาศาสตร์'],
                    ['id' => 3, 'name' => 'คณิตศาสตร์'],
                    ['id' => 4, 'name' => 'ภาษาไทย'],
                    ['id' => 5, 'name' => 'สังคม'],
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
        $path = database_path('seeders/ngim-users.json');
        $data = json_decode(file_get_contents($path), true);
        $users = $data['users'];
        foreach ($users as $user) {
            $newUser = User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email']
            ]);
            if ($user['role'] == 'admin') {
                $newUser->assignRole(\Spatie\Permission\Models\Role::where('name',
                    \App\Models\Role::ROLE_ADMIN)->first());
            }
            if ($user['role'] == 'teacher') {
                $newUser->assignRole(\Spatie\Permission\Models\Role::where('name',
                    \App\Models\Role::ROLE_TEACHER)->first());
            }
        }
    }
}
