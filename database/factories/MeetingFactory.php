<?php

namespace Database\Factories;

use App\Models\Meeting;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => School::inRandomOrder()->first()->id ?? School::factory(),
            'title' => $this->faker->text(20),
            'date' => $this->faker->dateTime(),
            'status' => $this->faker->numberBetween(1, 2),
        ];
    }
}
