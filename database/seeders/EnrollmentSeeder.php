<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grade = Grade::first();
        $students = Student::where('school_id', $grade->school->id)->take(10)->get();
        foreach ($students as $key => $student) {
            Enrollment::factory()->create([
                'grade_id' => $grade->id,
                'number_in_grade' => $key + 1,
                'student_id' => $student->id
            ]);
        }
    }
}
