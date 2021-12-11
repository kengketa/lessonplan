<?php

namespace App\Actions;

use App\Models\Meeting;
use Illuminate\Support\Str;

class SaveMeetingAction
{
    protected Meeting $meeting;

    public function execute(Meeting $meeting, array $data): Meeting
    {
        $this->meeting = $meeting;

        if (! empty($this->meeting->id)) {
            $this->meeting->update($data);
            return $this->meeting;
        }

        //create case
        $this->meeting = $this->meeting->create($data);
        return $this->meeting;
    }

}
