<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => $this->faker->numerify('#####'),
            'name' => $this->faker->text(20),
            'unit' => $this->faker->randomElement([0.5, 1.0, 1.5, 2.0, 2.5, 3]),

        ];
    }
}
