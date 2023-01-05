<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Misbehavior>
 */
class MisbehaviorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $teachers = User::role(Role::ROLE_TEACHER)->get();
        return [
            'report_id' => Report::inRandomOrder()->first()->id,
            'name' => $this->faker->name,
            'behavior' => $this->faker->sentence(10),
            'subject' => 'test',
            'grade' => $this->faker->randomElement(['1', '2', '3']),
            'teacher_id' => $teachers->random()->id
        ];
    }
}
