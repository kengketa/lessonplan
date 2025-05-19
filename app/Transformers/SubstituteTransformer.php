<?php

namespace App\Transformers;

use App\Models\Substitute;
use League\Fractal\TransformerAbstract;

class SubstituteTransformer extends TransformerAbstract
{
    public function transform(Substitute $substitute): array
    {
        return [
            'id' => $substitute->id,
            'school_id' => $substitute->school_id,
            'date' => $substitute->date->format('Y-m-d'),
            'display_date' => $substitute->date->format('j F Y'),
            'start_time' => $substitute->start_time->format('H:i'),
            'end_time' => $substitute->end_time->format('H:i'),
            'grade' => $substitute->grade,
            'subject' => $substitute->subject,
            'teacher' => $substitute->teacher,
            'volunteer' => $substitute->volunteer,
            'created_at' => $substitute->present()->createdAt,
            'updated_at' => $substitute->present()->updatedAt,
        ];
    }
}
