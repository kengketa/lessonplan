<?php

namespace App\Actions;

use App\Models\Subject;
use Illuminate\Support\Str;

class SaveSubjectAction
{
    protected Subject $subject;

    public function execute(Subject $subject, array $data): Subject
    {
        $this->subject = $subject;

        if (! empty($this->subject->id)) {
            $this->subject->update($data);
            return $this->subject;
        }

        //create case
        $this->subject = $this->subject->create($data);
        return $this->subject;
    }

}
