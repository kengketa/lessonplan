<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveGradeAction;
use App\Http\Requests\CreateOrUpdateGradeRequest;
use App\Models\Grade;
use App\Http\Controllers\Controller;
use App\Transformers\GradeTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GradeController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $grades = Grade::filter($filters)->latest()->paginate(30);
        $grades = fractal($grades, new GradeTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Grades/Index',
            [
                'grades' => $grades,
                'filters' => $filters,
            ]);
    }
    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Grades/Create',
            [
                'grade' => new Grade()
            ]);
    }

    public function store(CreateOrUpdateGradeRequest $request, SaveGradeAction $saveGradeAction): RedirectResponse
    {
        $grade = new Grade();
        $grade = $saveGradeAction->execute($grade, $request->validated());

        return redirect()->route('dashboard.grades.show', ['grade' => $grade])->with("success", ' Grade  has been create!');
    }

    public function show(Grade $grade): Response
    {
        $grade = fractal($grade, new GradeTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Grades/Show',
            [
                'grade' => $grade,
            ]);
    }

    public function edit(Grade $grade): Response
    {
        return Inertia::render(
            'Dashboard/Grades/Edit',
            [
                'grade' => $grade
            ]);
    }

    public function update(CreateOrUpdateGradeRequest $request, Grade $grade, SaveGradeAction $saveGradeAction): RedirectResponse
    {
        $grade = $saveGradeAction->execute($grade, $request->validated());

        return redirect()->route("dashboard.grades.show", ['grade' => $grade])->with("success", "Grade has been update!");
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        if ($grade->delete()) {
            return redirect()->route("dashboard.grades.index")->with("success", "Grade has been destroy!");
        } else {
            return redirect()->route("dashboard.grades.index")->with("error", "Can't delete Grade");
        }
    }

}
