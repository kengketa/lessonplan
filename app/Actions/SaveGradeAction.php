<?php

namespace App\Actions;

use App\Models\Grade;
use Illuminate\Support\Str;

class SaveGradeAction
{
    public function execute(array $data): Grade|null
    {
        $grade = Grade::where('school_id', $data['school_id'])
            ->where('type', $data['type'])
            ->where('level', $data['level'])
            ->where('room_number', $data['room_number'])
            ->first();
        if ($grade) {
            return null;
        }
        $grade = Grade::create([
            'school_id' => $data['school_id'],
            'type' => $data['type'],
            'level' => $data['level'],
            'room_number' => $data['room_number']
        ]);
        return $grade;
    }

}
