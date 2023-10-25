<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveGradeAction;
use App\Http\Requests\CreateOrUpdateGradeRequest;
use App\Models\Grade;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\School;
use App\Transformers\GradeTransformer;
use App\Transformers\ReportTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class GradeController extends Controller
{
    public function store(CreateOrUpdateGradeRequest $request, SaveGradeAction $saveGradeAction): RedirectResponse
    {
        $school = School::find($request['school_id']);
        $grade = $saveGradeAction->execute($request->validated());
        if ($grade == null) {
            return redirect()->route('dashboard.schools.show', $school->id)
                ->with("error", 'มี grade ที่กรอกอยู่แล้ว');
        }
        $this->forgetSchoolCache($school->id);
        return redirect()->route('dashboard.schools.show', $school->id)
            ->with("success", 'Grade has been create.');
    }

    private function forgetSchoolCache(int $id): void
    {
        Cache::forget('cache_school_id_' . $id);
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        if ($grade->reports->count() > 0) {
            return redirect()->route("dashboard.schools.show", $grade->school->id)
                ->with("error", "Grade can't be removed as it is related with reports.");
        }

        $school = $grade->school;
        if ($grade->delete()) {
            $this->forgetSchoolCache($school->id);
            return redirect()->route("dashboard.schools.show", $school->id)->with("success", "Grade has been removed!");
        } else {
            return redirect()->route("dashboard.schools.show", $school->id)->with("error", "Grade can't be removed!");
        }
    }

    public function show(Grade $grade)
    {
        $gradeData = fractal($grade, new GradeTransformer())->toArray();
        $reports = Report::where('grade_id', $grade->id)
            ->orderBy('week_number')
            ->orderBy('lesson_number')
            ->paginate(30);
        $reportData = fractal($reports, new ReportTransformer())->toArray();
        return Inertia::render(
            'Dashboard/Grades/Show',
            [
                'grade' => $gradeData,
                'reports' => $reportData
            ]
        );
    }

}
