<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveEnrollmentAction;
use App\Http\Requests\CreateOrUpdateEnrollmentRequest;
use App\Imports\EnrollmentImport;
use App\Models\Grade;
use Exception;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class EnrollmentController extends Controller
{
    public function store(
        CreateOrUpdateEnrollmentRequest $request,
        Grade $grade,
        SaveEnrollmentAction $saveEnrollmentAction
    ) {
        $file = $request->file('file');
        $saveEnrollmentAction->execute($grade, $file);
        return redirect()->route('dashboard.grades.show', ['grade' => $grade])
            ->with("success", 'Imported all students to this class.');
    }
}
