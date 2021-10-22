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
        return Carbon::parse($this->model->date)->format('d M Y');
    }

    public function plans()
    {
//        $plans = $this->model->plans;
//        dd($plans);
//        $plans['type'] = $plans['type'] == Report::TOPIC_PHONIC ? 'Phonics' : 'Learning Area';
        return "sdfd";
    }

    public function subject()
    {
        $subjectId = $this->model->subject;
        $subjects = $this->model->grade->school->subjects;
        $filteredSubject = Arr::where($subjects, function ($subject, $key) use ($subjectId) {
            return $subject['id'] == $subjectId;
        });
        return ucfirst($filteredSubject[0]['name']);
    }

}
