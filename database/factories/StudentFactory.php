<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => School::first()->id,
            'code' => $this->faker->numerify('#####'),
            'prefix' => $this->faker->randomElement(['นาย', 'นางสาว', 'เด็กชาย', 'เด็กหญิง']),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password,
            'phone' => $this->faker->phoneNumber,

        ];
    }
}
