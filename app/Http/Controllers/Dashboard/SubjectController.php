<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveSubjectAction;
use App\Http\Requests\CreateOrUpdateSubjectRequest;
use App\Models\Subject;
use App\Http\Controllers\Controller;
use App\Transformers\SubjectTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SubjectController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $subjects = Subject::filter($filters)->latest()->paginate(30);
        $subjects = fractal($subjects, new SubjectTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Subjects/Index',
            [
                'subjects' => $subjects,
                'filters' => $filters,
            ]);
    }
    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Subjects/Create',
            [
                'subject' => new Subject()
            ]);
    }

    public function store(CreateOrUpdateSubjectRequest $request, SaveSubjectAction $saveSubjectAction): RedirectResponse
    {
        $subject = new Subject();
        $subject = $saveSubjectAction->execute($subject, $request->validated());

        return redirect()->route('dashboard.subjects.show', ['subject' => $subject])->with("success", ' Subject  has been create!');
    }

    public function show(Subject $subject): Response
    {
        $subject = fractal($subject, new SubjectTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Subjects/Show',
            [
                'subject' => $subject,
            ]);
    }

    public function edit(Subject $subject): Response
    {
        return Inertia::render(
            'Dashboard/Subjects/Edit',
            [
                'subject' => $subject
            ]);
    }

    public function update(CreateOrUpdateSubjectRequest $request, Subject $subject, SaveSubjectAction $saveSubjectAction): RedirectResponse
    {
        $subject = $saveSubjectAction->execute($subject, $request->validated());

        return redirect()->route("dashboard.subjects.show", ['subject' => $subject])->with("success", "Subject has been update!");
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        if ($subject->delete()) {
            return redirect()->route("dashboard.subjects.index")->with("success", "Subject has been destroy!");
        } else {
            return redirect()->route("dashboard.subjects.index")->with("error", "Can't delete Subject");
        }
    }

}
