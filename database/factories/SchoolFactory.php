<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(10),
            'address' => null,
            'subjects' => [
                ['id' => 1, 'name' => 'phonics'],
                ['id' => 2, 'name' => 'english'],
                ['id' => 3, 'name' => 'maths'],
                ['id' => 4, 'name' => 'sci'],
                ['id' => 5, 'name' => 'arts'],
                ['id' => 6, 'name' => 'pe'],
            ],
            'lat' => 19.170516088524884,
            'lng' => 99.90557395546138,
            'radius' => 50
        ];
    }
}
