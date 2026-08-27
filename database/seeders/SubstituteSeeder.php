<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Substitute;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SubstituteSeeder extends Seeder
{
    /**
     * Class periods a substitute can be booked into.
     */
    private const PERIODS = [
        ['08:30', '09:20'],
        ['09:20', '10:10'],
        ['10:30', '11:20'],
        ['11:20', '12:10'],
        ['13:00', '13:50'],
        ['13:50', '14:40'],
        ['14:40', '15:30'],
    ];

    private const FALLBACK_TEACHERS = [
        'Somchai Wongsa',
        'Pranee Thongdee',
        'Anong Srisai',
        'Wichai Boonmee',
        'Malee Chaiyo',
    ];

    private const FALLBACK_SUBJECTS = ['English', 'Maths', 'Science', 'Arts', 'PE'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::today();

        foreach (School::with(['grades', 'teachers'])->get() as $school) {
            $grades = $school->grades->map(fn ($grade) => $grade->present()->name)->all();
            $teachers = $school->teachers->pluck('name')->filter()->values()->all();
            $subjects = collect($school->subjects)->pluck('name')->filter()->values()->all();

            $grades = $grades ?: ['P.1/1', 'P.2/1', 'P.3/1'];
            $teachers = count($teachers) > 1 ? $teachers : self::FALLBACK_TEACHERS;
            $subjects = $subjects ?: self::FALLBACK_SUBJECTS;

            // A week back for history, a fortnight forward for advance scheduling.
            for ($offset = -7; $offset <= 14; $offset++) {
                $date = $today->copy()->addDays($offset);

                if ($offset !== 0 && $date->isWeekend()) {
                    continue;
                }

                // Re-runnable: never stack a second board onto a day already seeded.
                $alreadySeeded = Substitute::where('school_id', $school->id)
                    ->whereDate('date', $date)
                    ->exists();

                if ($alreadySeeded) {
                    continue;
                }

                // Today always gets a full board; other days are sparser.
                $count = $offset === 0 ? 4 : random_int(0, 3);

                foreach ($this->pickPeriods($count) as $index => $period) {
                    $absent = Arr::random($teachers);
                    $volunteer = $this->pickVolunteer($teachers, $absent, $offset, $index);

                    Substitute::create([
                        'school_id' => $school->id,
                        'date' => $date->format('Y-m-d'),
                        'start_time' => $period[0],
                        'end_time' => $period[1],
                        'grade' => Arr::random($grades),
                        'subject' => Arr::random($subjects),
                        'teacher' => $absent,
                        'volunteer' => $volunteer,
                    ]);
                }
            }
        }
    }

    /**
     * Distinct periods, in chronological order.
     */
    private function pickPeriods(int $count): array
    {
        if ($count < 1) {
            return [];
        }

        $keys = (array) Arr::random(array_keys(self::PERIODS), $count);
        sort($keys);

        return array_map(fn ($key) => self::PERIODS[$key], $keys);
    }

    /**
     * Past days are fully covered; today keeps open slots so the assign flow is testable.
     */
    private function pickVolunteer(array $teachers, string $absent, int $offset, int $index): ?string
    {
        if ($offset === 0) {
            // Rows 0 and 1 covered, rows 2 and 3 left open.
            $covered = $index < 2;
        } elseif ($offset < 0) {
            $covered = true;
        } else {
            $covered = random_int(1, 100) <= 40;
        }

        if (! $covered) {
            return null;
        }

        $candidates = array_values(array_diff($teachers, [$absent]));

        return $candidates ? Arr::random($candidates) : null;
    }
}
