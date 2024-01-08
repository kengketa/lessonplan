<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveEnrollmentAction;
use App\Http\Requests\CreateOrUpdateEnrollmentRequest;
use App\Models\Enrollment;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;


class EnrollmentController extends Controller
{
    public function store(
        CreateOrUpdateEnrollmentRequest $request,
        SaveEnrollmentAction $saveEnrollmentAction
    ): RedirectResponse {
        $enrollment = new Enrollment();
        $enrollment = $saveEnrollmentAction->execute($enrollment, $request->validated());

        return redirect()->route('dashboard.enrollments.show', ['enrollment' => $enrollment])
            ->with("success", 'Enrollment has been created.');
    }
}
