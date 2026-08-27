<?php

namespace App\Transformers\Api;

use App\Models\Substitute;
use League\Fractal\TransformerAbstract;

/**
 * Public API shape for a substitute row.
 *
 * Deliberately separate from App\Transformers\SubstituteTransformer, which the
 * dashboard and volunteer pages consume with the model's own column names.
 * Here the columns are renamed to say what they mean: `teacher` is the person
 * who is away, `volunteer` is the person standing in.
 */
class SubstituteTransformer extends TransformerAbstract
{
    public function transform(Substitute $substitute): array
    {
        return [
            'id' => $substitute->id,
            'date' => $substitute->date->format('Y-m-d'),
            'display_date' => $substitute->date->format('j F Y'),
            'start_time' => $substitute->start_time->format('H:i'),
            'end_time' => $substitute->end_time->format('H:i'),
            'grade' => $substitute->grade,
            'subject' => $substitute->subject,
            'absent_teacher' => $substitute->teacher,
            'cover_teacher' => $substitute->volunteer,
            'created_at' => $substitute->present()->createdAt,
            'updated_at' => $substitute->present()->updatedAt,
        ];
    }
}
