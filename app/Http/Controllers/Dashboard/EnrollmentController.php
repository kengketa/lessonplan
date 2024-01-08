<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveEnrollmentAction;
use App\Http\Requests\CreateOrUpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use App\Transformers\EnrollmentTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnrollmentController extends Controller
{
    public function store(
        CreateOrUpdateEnrollmentRequest $request,
        SaveEnrollmentAction $saveEnrollmentAction
    ): RedirectResponse {
        $enrollment = new Enrollment();
        $enrollment = $saveEnrollmentAction->execute($enrollment, $request->validated());

        return redirect()->route('dashboard.enrollments.show', ['enrollment' => $enrollment])->with(
            "success",
            ' Enrollment  has been create!'
        );
    }
}
