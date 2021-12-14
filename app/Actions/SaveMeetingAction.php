<?php

namespace App\Actions;

use App\Models\Agenda;
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
        $this->meeting->location = $data['location'] ?? null;
        $this->meeting->attendee = count($data['attendee']) > 0 ? $data['attendee'] : null;
        $this->meeting->status = $meetingDateTime?->lt($now) ? Meeting::ACHIEVED : Meeting::UP_COMMING;
        $this->meeting->save();
        $this->updateAgenda($data['agendas']);
        return $this->meeting;
    }

    private function updateAgenda(array $agendas): void
    {
        $stayIds = [];
        $oldAgendas = Agenda::where('meeting_id', $this->meeting->id)->pluck('id')->toArray();
        foreach ($agendas as $agenda) {
            if ($agenda['id'] == null) {
                $newAgenda = Agenda::create([
                    'meeting_id' => $this->meeting->id,
                    'topic' => $agenda['topic'],
                    'detail' => $agenda['detail'] ?? null,
                    'decision' => $agenda['decision'] ?? null
                ]);
                $stayIds[] = $newAgenda->id;
            }
            if ($agenda['id'] != null) {
                $toBeUpdatedAgenda = Agenda::findOrFail($agenda['id']);
                $toBeUpdatedAgenda->topic = $agenda['topic'];
                $toBeUpdatedAgenda->detail = $agenda['detail'] ?? null;
                $toBeUpdatedAgenda->decision = $agenda['decision'] ?? null;
                $toBeUpdatedAgenda->save();
                $stayIds[] = $toBeUpdatedAgenda->id;
            }
        }
        $toBeDeletedIds = array_diff($oldAgendas, $stayIds);
        if (count($toBeDeletedIds) > 0) {
            Agenda::whereIn('id', $toBeDeletedIds)->delete();
        }
        return;
    }

}
