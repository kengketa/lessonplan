<?php

namespace App\Presenters;

use App\Models\Report;
use Carbon\Carbon;

class ReportPresenter extends BasePresenter
{
    protected $model;

    public function date()
    {
        return Carbon::parse($this->model->date)->format('d M Y');
    }

    public function topic()
    {
        $topic = $this->model->topic;
        $topic['type'] = $topic['type'] == Report::TOPIC_PHONIC ? 'Phonics' : 'Learning Area';
        return $topic;
    }

}
