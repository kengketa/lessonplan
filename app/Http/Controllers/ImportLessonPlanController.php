<?php

namespace App\Http\Controllers;

use App\Actions\ImportLessonPlanFromExcelAction;
use App\Imports\LessonPlanImport;
use App\Models\School;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportLessonPlanController extends Controller
{
    public function import(Request $request, School $school, ImportLessonPlanFromExcelAction $action)
    {
        $req = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        $import = new LessonPlanImport();
        $rows = Excel::toCollection($import, $req['file']);
        $action->execute($school, $rows);
    }
}
