<?php

namespace Database\Factories;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Setting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'settings' => [
                'current_academic_year' => Carbon::today()->format('Y'),
                'current_semester' => 1
            ]
        ];
    }
}
