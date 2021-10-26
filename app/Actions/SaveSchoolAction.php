<?php

namespace App\Actions;

use App\Models\School;
use Illuminate\Support\Str;

class SaveSchoolAction
{
    protected School $school;

    public function execute(School $school, array $data): School
    {
        $this->school = $school;

        if (! empty($this->school->id)) {
            $this->school->update($data);
            return $this->school;
        }

        //create case
        $this->school = $this->school->create($data);
        return $this->school;
    }

}
