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
        $date = "-";
        if ($this->model->date != null) {
            $date = Carbon::parse($this->model->date)->format('d M Y H:i');
        }
        return $date;
    }

}
