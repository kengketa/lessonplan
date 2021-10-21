<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $teacherCount = User::role(Role::ROLE_TEACHER)->get()->count();
        $teacher = User::role(Role::ROLE_TEACHER)->get();
        $admin = User::role(Role::ROLE_ADMIN)->get();
        return [
            'grade_id' => Grade::first()->id ?? Grade::factory(),
            'academic_year' => 2021,
            'semester' => 2,
            'week_number' => $this->faker->numberBetween(1, 20),
            'lesson_number' => $this->faker->numberBetween(1, 5),
            'date' => $this->faker->date(),
            'topic' => [
                "type" => $this->faker->randomElement([Report::TOPIC_PHONIC, Report::TOPIC_LEARNING_AREA]),
                'title' => $this->faker->sentence(5),
                'vocabs' => [$this->faker->word, $this->faker->word, $this->faker->word, $this->faker->word],
                'details' => $this->faker->sentence(20),
            ],
            'subject' => $this->faker->text(20),
            'outcome' => $this->faker->sentence(20),
            'outstanding_student' => $this->faker->sentence(20),
            'need_improvement_student' => $this->faker->sentence(20),
            'creator' => $teacher->random()->id,
            'approver' => $admin->random()->id
        ];
    }
}
