<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\School;
use App\Models\SchoolTeacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolTeacherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SchoolTeacher::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $teachers = User::role(Role::ROLE_TEACHER)->get();
        return [
            'school_id' => School::inRandomOrder()->first()->id ?? School::factory(),
            'teacher_id' => $teachers->random()->id
        ];
    }
}
