<?php

namespace App\Actions;

use App\Models\Enrollment;
use Illuminate\Support\Str;

class SaveEnrollmentAction
{
    protected Enrollment $enrollment;

    public function execute(Enrollment $enrollment, array $data): Enrollment
    {
        $this->enrollment = $enrollment;

        if (! empty($this->enrollment->id)) {
            $this->enrollment->update($data);
            return $this->enrollment;
        }

        //create case
        $this->enrollment = $this->enrollment->create($data);
        return $this->enrollment;
    }

}
