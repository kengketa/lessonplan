<?php

namespace App\Actions;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SaveMeetingAction
{
    protected Meeting $meeting;

    public function execute(Meeting $meeting, array $data): Meeting
    {
        $this->meeting = $meeting;
        $now = Carbon::now();
        $hour = 0;
        $minute = 0;
        if (isset($data['time'])) {
            $arr = explode('.', $data['time']);
            $hour = (int)$arr[0];
            $minute = (int)$arr[1] ?? 0;
        }
        $meetingDateTime = null;
        if (isset($data['date'])) {
            $meetingDateTime = Carbon::parse($data['date'])->addHours($hour)->addMinutes($minute);
        }

        $this->meeting->school_id = $data['school_id'];
        $this->meeting->title = $data['title'];
        $this->meeting->date = $meetingDateTime;
        $this->meeting->location = $data['location'];
        $this->meeting->attendee = count($data['attendee']) > 0 ? $data['attendee'] : null;
        $this->meeting->status = $meetingDateTime->lt($now) ? Meeting::ACHIEVED : Meeting::UP_COMMING;
        $this->meeting->save();
        $this->updateAgenda($data['agendas']);
        return $this->meeting;
    }

    private function updateAgenda(array $agendas)
    {
        return void;
    }

}
