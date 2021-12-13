<?php

namespace App\Transformers;

use App\Models\Meeting;
use League\Fractal\TransformerAbstract;

class MeetingTransformer extends TransformerAbstract
{
    public function transform(Meeting $meeting): array
    {
        $data = [
            'id' => $meeting->id,
            'school' => $meeting->school->name,
            'title' => $meeting->title,
            'date' => $meeting->present()->date,
            'status' => $meeting->present()->status,
            'created_at' => $meeting->present()->createdAt,
            'updated_at' => $meeting->present()->updatedAt,
        ];

        return $data;
    }
}
