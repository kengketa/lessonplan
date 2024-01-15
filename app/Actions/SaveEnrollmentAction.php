<?php

namespace App\Actions;

use App\Imports\EnrollmentImport;
use App\Models\Enrollment;
use App\Models\Grade;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class SaveEnrollmentAction
{
    protected Grade $grade;

    public function execute(Grade $grade, $file): void
    {
        $this->grade = $grade;
        $path = $file->storeAs('enrolment-imports', $file->getClientOriginalName());
        try {
            DB::beginTransaction();
            Excel::import(new EnrollmentImport($grade), $path);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return;
    }
}
