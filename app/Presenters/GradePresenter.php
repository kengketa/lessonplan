<?php

namespace App\Presenters;

use App\Models\Grade;

class GradePresenter extends BasePresenter
{
    protected $model;

    public function name()
    {
        return $this->getType().''.$this->level.'/'.$this->room_number;
    }

    private function getType()
    {
        if ($this->model->type === Grade::NURSERY_TYPE) {
            return 'pre';
        }
        if ($this->model->type === Grade::KINDERGATEN_TYPE) {
            return 'K.';
        }
        if ($this->model->type === Grade::PRIMARY_TYPE) {
            return 'P.';
        }
        if ($this->model->type === Grade::SECONDARY_TYPE) {
            return 'M.';
        }
        return "";
    }

}
