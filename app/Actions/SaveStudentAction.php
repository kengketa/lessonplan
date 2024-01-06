<?php

namespace App\Actions;

use App\Models\Student;
use Illuminate\Support\Str;

class SaveStudentAction
{
    protected Student $student;

    public function execute(Student $student, array $data): Student
    {
        $this->student = $student;

        if (! empty($this->student->id)) {
            $this->student->update($data);
            return $this->student;
        }

        //create case
        $this->student = $this->student->create($data);
        return $this->student;
    }

}
