<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $grade = Grade::find(1);
        return [
            'grade_id' => $grade->id,
            'student_id' => Student::inRandomOrder()->first()->id,
        ];
    }
}
