<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Grade::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => School::inRandomOrder()->first()->id ?? School::factory(),
            'type' => $this->faker->numberBetween(1, 100),
            'level' => $this->faker->numberBetween(1, 100),
            'room_number' => $this->faker->numberBetween(1, 100),
        ];
    }
}
