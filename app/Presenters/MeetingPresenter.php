<?php

namespace App\Presenters;

use App\Models\Meeting;
use Carbon\Carbon;

class MeetingPresenter extends BasePresenter
{
    protected $model;

    public function status()
    {
//        return $this->model->status == Meeting::UP_COMMING ? 'up comming' : 'achieved';
        return $this->model->status;
    }

    public function date()
    {
        $date = null;
        if ($this->model->date != null) {
            $date = Carbon::parse($this->model->date)->format('d M Y');
        }
        return $date;
    }

    public function time()
    {
        $time = null;
        if ($this->model->date != null) {
            $time = Carbon::parse($this->model->date)->format('H.i');
        }
        return $time;
    }

}
