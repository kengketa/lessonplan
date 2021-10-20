<?php

namespace App\Actions;

use App\Models\Grade;
use Illuminate\Support\Str;

class SaveGradeAction
{
    protected Grade $grade;

    public function execute(Grade $grade, array $data): Grade
    {
        $this->grade = $grade;

        if (! empty($this->grade->id)) {
            $this->grade->update($data);
            return $this->grade;
        }

        //create case
        $this->grade = $this->grade->create($data);
        return $this->grade;
    }

}
