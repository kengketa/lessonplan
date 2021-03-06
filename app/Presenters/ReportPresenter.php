<?php

namespace App\Presenters;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class ReportPresenter extends BasePresenter
{
    protected $model;

    public function date()
    {
        if ($this->model->date) {
            return Carbon::parse($this->model->date)->format('d M Y');
        }
        return null;
    }

    public function plans()
    {
        $plans = $this->model->plans;
        foreach ($plans as $key => $plan) {
            $plans[$key]['type'] = $plan['type'] == Report::TOPIC_PHONIC ? 'Phonics' : 'Learning Area';
        }
        return $plans;
    }

    public function topics()
    {
        $plans = $this->model->plans;
        $topics = [];
        foreach ($plans as $plan) {
            $type = $plan['type'] == Report::TOPIC_PHONIC ? 'Phonics' : 'Learning Area';
            $topics[] = $type.' : '.$plan['topic'];
        }
        return $topics;
    }

    public function subject()
    {
        $subjectId = $this->model->subject;
        $subjects = $this->model->grade->school->subjects;
        $filteredSubject = Arr::where($subjects, function ($subject, $key) use ($subjectId) {
            return $subject['id'] == $subjectId;
        });
        $subjectName = null;
        foreach ($filteredSubject as $subject) {
            $subjectName = $subject['name'];
        }
        return $subjectName;
    }

}
