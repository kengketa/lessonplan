<?php

namespace App\Http\Controllers;

use App\Actions\SaveStudentAction;
use App\Http\Requests\CreateOrUpdateStudentRequest;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Transformers\StudentTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $students = Student::filter($filters)->latest()->paginate(30);
        $students = fractal($students, new StudentTransformer())->toArray();
        return Inertia::render(
            'Dashboard/Students/Index',
            [
                'students' => $students,
                'filters' => $filters,
            ]
        );
    }

    public function create(): Response
    {
        return Inertia::render(
            'Students/Create',
            [
                'student' => new Student()
            ]
        );
    }

    public function store(CreateOrUpdateStudentRequest $request, SaveStudentAction $saveStudentAction): RedirectResponse
    {
        $student = new Student();
        $student = $saveStudentAction->execute($student, $request->validated());

        return redirect()->route('.students.show', ['student' => $student])->with(
            "success",
            ' Student  has been create!'
        );
    }

    public function show(Student $student): Response
    {
        $student = fractal($student, new StudentTransformer())->toArray();

        return Inertia::render(
            'Students/Show',
            [
                'student' => $student,
            ]
        );
    }

    public function edit(Student $student): Response
    {
        return Inertia::render(
            'Students/Edit',
            [
                'student' => $student
            ]
        );
    }

    public function update(
        CreateOrUpdateStudentRequest $request,
        Student $student,
        SaveStudentAction $saveStudentAction
    ): RedirectResponse {
        $student = $saveStudentAction->execute($student, $request->validated());

        return redirect()->route(".students.show", ['student' => $student])->with(
            "success",
            "Student has been update!"
        );
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->delete()) {
            return redirect()->route(".students.index")->with("success", "Student has been destroy!");
        } else {
            return redirect()->route(".students.index")->with("error", "Can't delete Student");
        }
    }

}