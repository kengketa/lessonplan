<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ClockInFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $teacher = User::role(Role::ROLE_TEACHER)->first();
        $date = Carbon::now()->subDays(random_int(10, 30));
        return [
            'teacher_id' => $teacher->id,
            'school_id' => $teacher->school[0]->id,
            'date' => $date,
            'clock_in' => Carbon::parse($date)->subHours(random_int(2, 4))->subMinutes(random_int(10, 50)),
            'clock_out' => Carbon::parse($date)->addHours(random_int(2, 4))->addMinutes(random_int(10, 50)),
        ];
    }
}
