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
            return 'ศูนย์เด็ก';
        }
        if ($this->model->type === Grade::KINDERGATEN_TYPE) {
            return 'อ.';
        }
        if ($this->model->type === Grade::PRIMARY_TYPE) {
            return 'ป.';
        }
        return "";
    }

}
