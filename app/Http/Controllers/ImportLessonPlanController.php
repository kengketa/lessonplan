<?php

namespace App\Http\Controllers;

use App\Actions\ImportLessonPlanFromExcelAction;
use App\Imports\LessonPlanImport;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportLessonPlanController extends Controller
{
    public function import(Request $request, School $school, ImportLessonPlanFromExcelAction $action)
    {
        $req = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);
        try {
            DB::beginTransaction();
            $import = new LessonPlanImport();
            $rows = Excel::toCollection($import, $req['file']);
            $status = $action->execute($school, $rows);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return response()->json($status, $status['status']);
    }
}
