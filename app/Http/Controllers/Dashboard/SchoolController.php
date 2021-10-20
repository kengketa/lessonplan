<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveSchoolAction;
use App\Http\Requests\CreateOrUpdateSchoolRequest;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Transformers\SchoolTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SchoolController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $schools = School::filter($filters)->latest()->paginate(30);
        $schools = fractal($schools, new SchoolTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Schools/Index',
            [
                'schools' => $schools,
                'filters' => $filters,
            ]);
    }
    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Schools/Create',
            [
                'school' => new School()
            ]);
    }

    public function store(CreateOrUpdateSchoolRequest $request, SaveSchoolAction $saveSchoolAction): RedirectResponse
    {
        $school = new School();
        $school = $saveSchoolAction->execute($school, $request->validated());

        return redirect()->route('dashboard.schools.show', ['school' => $school])->with("success", ' School  has been create!');
    }

    public function show(School $school): Response
    {
        $school = fractal($school, new SchoolTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Schools/Show',
            [
                'school' => $school,
            ]);
    }

    public function edit(School $school): Response
    {
        return Inertia::render(
            'Dashboard/Schools/Edit',
            [
                'school' => $school
            ]);
    }

    public function update(CreateOrUpdateSchoolRequest $request, School $school, SaveSchoolAction $saveSchoolAction): RedirectResponse
    {
        $school = $saveSchoolAction->execute($school, $request->validated());

        return redirect()->route("dashboard.schools.show", ['school' => $school])->with("success", "School has been update!");
    }

    public function destroy(School $school): RedirectResponse
    {
        if ($school->delete()) {
            return redirect()->route("dashboard.schools.index")->with("success", "School has been destroy!");
        } else {
            return redirect()->route("dashboard.schools.index")->with("error", "Can't delete School");
        }
    }

}
