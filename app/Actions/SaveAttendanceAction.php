<?php

namespace App\Actions;

use App\Models\Attendance;
use App\Models\Grade;
use Carbon\Carbon;

class SaveAttendanceAction
{
    public function execute(Grade $grade, array $data): bool
    {
        $today = Carbon::now()->toDateString();
        $attendance = Attendance::whereDate('created_at', $today)
            ->where('student_id', $data['student_id'])
            ->first();
        if ($data['present'] === false && $attendance) {
            $attendance->delete();
            return false;
        }
        if ($data['present'] === true && !$attendance) {
            Attendance::create([
                'grade_id' => $grade->id,
                'student_id' => $data['student_id']
            ]);
            return true;
        }
        return $data['present'];
    }

}
