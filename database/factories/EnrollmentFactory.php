<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $grade = Grade::first();
        return [
            'grade_id' => $grade->id,
            'academic_year' => getCurrentAcademicYear(),
            'student_id' => Student::where('school_id', $grade->school->id)->inRandomOrder()->first()->id,
            'semester' => getCurrentSemester(),
        ];
    }
}
