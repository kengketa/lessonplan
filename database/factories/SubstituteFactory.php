<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Substitute>
 */
class SubstituteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startTime = Carbon::createFromTime(
            $this->faker->numberBetween(7, 16),
            $this->faker->randomElement([0, 30])
        );
        $endTime = (clone $startTime)->addHour();

        return [
            'school_id' => School::inRandomOrder()->first()->id ?? School::factory(),
            'date' => $this->faker->dateTimeBetween('-7 days', '+7 days')->format('Y-m-d'),
            'start_time' => $startTime->format('H:i'),
            'end_time' => $endTime->format('H:i'),
            'grade' => $this->faker->randomElement(['1/1', '1/2', '4/1', '5/1', '6/2']),
            'subject' => $this->faker->word(),
            'teacher' => $this->faker->name(),
            'volunteer' => $this->faker->optional()->name(),
        ];
    }
}
