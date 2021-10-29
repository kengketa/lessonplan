<?php

namespace App\Presenters;

use App\Models\School;

class SchoolPresenter extends BasePresenter
{
    protected $model;

    public function unApprovedReportsCount()
    {
        $count = $this->reports->filter(function ($q) {
            return $q->approver_id == null;
        })->count();
        return $count;
    }

}
