<?php

namespace App\Transformers;

use App\Models\ClockIn;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class ClockInTransformer extends TransformerAbstract
{
    public function transform(ClockIn $clockIn): array
    {
        $data = [
            'id' => $clockIn->id,
            'teacher_id' => $clockIn->teacher_id,
            'school_id' => $clockIn->school_id,
            'date' => Carbon::parse($clockIn->date)->format('d M Y'),
            'clock_in' => Carbon::parse($clockIn->clock_in)->format('h:i:sa'),
            'clock_out' => Carbon::parse($clockIn->clock_out)->format('h:i:sa'),
        ];

        return $data;
    }
}
