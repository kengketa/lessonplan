<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
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
        $teachers = User::role(Role::ROLE_TEACHER)->get();
        $admins = User::role(Role::ROLE_ADMIN)->get();
        $school = School::find(1);
        return [
            'grade_id' => Grade::where('school_id', $school->id)->inRandomOrder()->first()->id,
            'academic_year' => getCurrentAcademicYear(),
            'semester' => getCurrentSemester(),
            'week_number' => getCurrentSemester() == 1 ?
                $this->faker->numberBetween(1, 20) :
                $this->faker->numberBetween(21, 40),
            'lesson_number' => $this->faker->numberBetween(1, 5),
            'date' => $this->faker->date(),
            'plans' => [
                0 => [
                    "type" => $this->faker->randomElement([Report::TOPIC_PHONIC, Report::TOPIC_LEARNING_AREA]),
                    'topic' => $this->faker->sentence(5),
                    'vocabs' => [$this->faker->word, $this->faker->word, $this->faker->word, $this->faker->word],
                    'details' => $this->faker->sentence(20),
                ],
                1 => [
                    "type" => $this->faker->randomElement([Report::TOPIC_PHONIC, Report::TOPIC_LEARNING_AREA]),
                    'topic' => $this->faker->sentence(5),
                    'vocabs' => [$this->faker->word, $this->faker->word, $this->faker->word, $this->faker->word],
                    'details' => $this->faker->sentence(20),
                ]
            ],
            'subject' => 1,
            'teaching_materials' => $this->faker->sentence(20),
            'activities' => $this->faker->sentence(20),
            'outcome' => $this->faker->sentence(20),
            'outstanding_students' => $this->faker->sentence(20),
            'need_improvement_students' => $this->faker->sentence(20),
            'creator_id' => $teachers->random()->id,
            'approver_id' => $admins->random()->id
        ];
    }
}
