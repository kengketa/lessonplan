<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Role;
use App\Models\School;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SubstituteController@volunteer and @print hardcode School::find(8), which is the
 * Brighton school on production. Locally the seeded schools stop at id 6, so those
 * two pages fail. This pins a stand-in school to id 8 so the public volunteer page
 * and the print view are usable in development.
 */
class VolunteerSchoolSeeder extends Seeder
{
    public const SCHOOL_ID = 8;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $school = School::find(self::SCHOOL_ID);

        if (! $school) {
            $school = new School([
                'name' => 'Brighton Volunteer School',
                'subjects' => [
                    ['id' => 1, 'name' => 'English'],
                    ['id' => 2, 'name' => 'Maths'],
                    ['id' => 3, 'name' => 'Sci'],
                ],
                'lat' => 19.170527444964296,
                'lng' => 99.90555761746296,
                'radius' => 50,
            ]);
            $school->id = self::SCHOOL_ID;
            $school->save();
        }

        $this->seedGrades($school);
        $this->seedTeachers($school);
    }

    /**
     * Same shape as SchoolSeeder: two preschool and two kindergarten rooms,
     * plus primary 1-6 with two rooms each.
     */
    private function seedGrades(School $school): void
    {
        if ($school->grades()->exists()) {
            return;
        }

        foreach ([Grade::NURSERY_TYPE, Grade::KINDERGATEN_TYPE] as $type) {
            foreach ([1, 2] as $roomNumber) {
                Grade::create([
                    'school_id' => $school->id,
                    'type' => $type,
                    'level' => 1,
                    'room_number' => $roomNumber,
                ]);
            }
        }

        foreach ([1, 2, 3, 4, 5, 6] as $level) {
            foreach ([1, 2] as $roomNumber) {
                Grade::create([
                    'school_id' => $school->id,
                    'type' => Grade::PRIMARY_TYPE,
                    'level' => $level,
                    'room_number' => $roomNumber,
                ]);
            }
        }
    }

    private function seedTeachers(School $school): void
    {
        $teachers = User::role(Role::ROLE_TEACHER)->get();

        if ($teachers->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 5; $i++) {
            $teacherId = $teachers->random()->id;

            $found = SchoolTeacher::where('school_id', $school->id)
                ->where('teacher_id', $teacherId)
                ->first();

            if ($found) {
                continue;
            }

            SchoolTeacher::factory()->create([
                'school_id' => $school->id,
                'teacher_id' => $teacherId,
            ]);
        }
    }
}
