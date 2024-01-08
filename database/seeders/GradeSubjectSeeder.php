<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Role;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class GradeSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grade = Grade::find(1);
        $subjects = Subject::inRandomOrder()->take(10)->get();
        foreach ($subjects as $subject) {
            $grade->subjects()->attach($subject, [
                'academic_year' => getCurrentAcademicYear(),
                'semester' => getCurrentSemester(),
                'teacher_id' => User::role(Role::ROLE_TEACHER)->inRandomOrder()->first()->id
            ]);
        }
    }
}
