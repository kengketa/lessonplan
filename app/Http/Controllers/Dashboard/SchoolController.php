<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveSchoolAction;
use App\Http\Requests\CreateOrUpdateSchoolRequest;
use App\Models\Grade;
use App\Models\Report;
use App\Models\Role;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Models\SchoolTeacher;
use App\Models\User;
use App\Transformers\GradeTransformer;
use App\Transformers\ReportTransformer;
use App\Transformers\SchoolTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Arr;


class SchoolController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $schools = School::filter($filters)->paginate(30);
        $schoolData = fractal($schools, new SchoolTransformer())->toArray();
        return Inertia::render(
            'Dashboard/Schools/Index',
            [
                'schools' => $schoolData,
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

        return redirect()->route('dashboard.schools.show', ['school' => $school])->with("success",
            ' School  has been create!');
    }

    public function show(School $school, Request $request): Response|RedirectResponse
    {
        $user = Auth::user();
        $thisUserIsInTheSchool = $school->teachers->filter(function ($q) use ($user) {
            return $q->id == $user->id;
        });
        if ($thisUserIsInTheSchool->count() === 0 && $user->roles[0]->name === Role::ROLE_TEACHER) {
            return redirect()->route('dashboard.schools.index')
                ->with("error", 'You are not authorized.');
        }

        $schoolData = fractal($school, new SchoolTransformer())->toArray();
        $grades = Grade::where('school_id', $school->id)->orderBy('type')->orderBy('level')->get();
        $schoolData['grades'] = fractal($grades, new GradeTransformer())->toArray();
        $schoolData['teachers'] = fractal($school->teachers, new UserTransformer())->toArray()['data'];
        $teachers = User::role(Role::ROLE_TEACHER)->get();
        $schoolData['all_teachers'] = fractal($teachers, new UserTransformer())->toArray()['data'];
        $years = getYears();
        $semesters = getSemesters();
        $schoolData['years'] = $years;
        $schoolData['semesters'] = $semesters;
        $schoolData['current_academic_year'] = getCurrentAcademicYear();
        $schoolData['current_semester'] = getCurrentSemester();
        $schoolId = $school->id;
        $reports = Report::whereHas('grade.school', function ($q) use ($schoolId) {
            $q->where('id', $schoolId);
        })->filter($request['filters'])
            ->orderBy('week_number')
            ->orderBy('lesson_number')
            ->paginate(15);
        $reportData = fractal($reports, new ReportTransformer())->toArray();
        if ($request['filters']) {
            foreach ($reportData['meta']['pagination']['links'] as $link) {
                if (isset($link->url)) {
                    $link->url = $link->url.
                        '&filters[academic_year]='.$request['filters']['academic_year'].
                        '&filters[semester]='.$request['filters']['semester'].
                        '&filters[grade]='.$request['filters']['grade'].
                        '&filters[subject]='.$request['filters']['subject'].
                        '&filters[teacher]='.$request['filters']['teacher'];
                }
            }
        }
        return Inertia::render(
            'Dashboard/Schools/Show',
            [
                'school' => $schoolData,
                'reports' => $reportData,
                'filters' => $request['filters']
            ]
        );
    }

    public function edit(School $school): Response
    {
        return Inertia::render(
            'Dashboard/Schools/Edit',
            [
                'school' => $school
            ]);
    }

    public function update(
        CreateOrUpdateSchoolRequest $request,
        School $school,
        SaveSchoolAction $saveSchoolAction
    ): RedirectResponse {
        $school = $saveSchoolAction->execute($school, $request->validated());

        return redirect()->route("dashboard.schools.show", ['school' => $school])->with("success",
            "School has been update!");
    }

    public function destroy(School $school): RedirectResponse
    {
        if ($school->delete()) {
            return redirect()->route("dashboard.schools.index")->with("success", "School has been destroy!");
        } else {
            return redirect()->route("dashboard.schools.index")->with("error", "Can't delete School");
        }
    }

    public function addSubject(School $school, Request $request)
    {
        $request->validate(['subject' => 'required']);
        $subjects = $school->subjects;
        $lastId = 1;
        if (count($subjects) > 0) {
            $lastId = $subjects[count($subjects) - 1]['id'];
        }
        $newSubject = ['id' => $lastId + 1, 'name' => $request['subject']];
        $subjects[] = $newSubject;
        $school->subjects = $subjects;
        $school->save();
        return redirect()->route("dashboard.schools.show", $school->id)
            ->with("success", "Subject has been added.");
    }

    public function deleteSubject(School $school, Request $request)
    {
        $request->validate(['id' => 'required']);
        $foundReport = Report::whereHas('grade.school', function ($q) use ($school) {
            $q->where('id', $school->id);
        })->where('subject', $request['id'])->count();

        if ($foundReport > 0) {
            return redirect()->route("dashboard.schools.show", $school->id)
                ->with("error", "This subject can not be deleted as it is related to reports");
        }
        $subjects = $school->subjects;
        $toRemoveId = $request['id'];
        $filteredSubjects = Arr::where($subjects, function ($subject, $key) use ($toRemoveId) {
            return $subject['id'] != $toRemoveId;
        });
        $school->subjects = $filteredSubjects;
        $school->save();

        return redirect()->route("dashboard.schools.show", $school->id)
            ->with("success", "Subject has been remove.");
    }

    public function addTeacher(School $school, Request $request)
    {
        $request->validate(['teacher_id' => 'required']);
        $foundTeacherInTheSchool = SchoolTeacher::where('school_id', $school->id)
            ->where('teacher_id', $request['teacher_id'])->first();
        if ($foundTeacherInTheSchool) {
            return redirect()->route("dashboard.schools.show", $school->id)
                ->with("error", "The teacher you added is in already in this school.");
        }
        SchoolTeacher::create([
            'school_id' => $school->id,
            'teacher_id' => $request['teacher_id']
        ]);
        return redirect()->route("dashboard.schools.show", $school->id)
            ->with("success", "Teacher has been added.");
    }

    public function removeTeacher(School $school, Request $request)
    {
        $request->validate(['id' => 'required']);
        SchoolTeacher::where('school_id', $school->id)->where('teacher_id', $request['id'])->delete();
        return redirect()->route("dashboard.schools.show", $school->id)
            ->with("success", "Teacher has been removed.");
    }

}
