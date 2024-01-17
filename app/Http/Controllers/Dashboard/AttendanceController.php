<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveAttendanceAction;
use App\Http\Requests\CreateOrUpdateAttendanceRequest;
use App\Models\Attendance;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Transformers\AttendanceTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $attendances = Attendance::filter($filters)->latest()->paginate(30);
        $attendances = fractal($attendances, new AttendanceTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Attendances/Index',
            [
                'attendances' => $attendances,
                'filters' => $filters,
            ]
        );
    }

    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Attendances/Create',
            [
                'attendance' => new Attendance()
            ]
        );
    }

    public function store(
        CreateOrUpdateAttendanceRequest $request,
        Grade $grade,
        SaveAttendanceAction $saveAttendanceAction
    ) {
        $attendance = $saveAttendanceAction->execute($grade, $request->validated());
        return response()->json($attendance);
    }

    public function show(Attendance $attendance): Response
    {
        $attendance = fractal($attendance, new AttendanceTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Attendances/Show',
            [
                'attendance' => $attendance,
            ]
        );
    }

    public function edit(Attendance $attendance): Response
    {
        return Inertia::render(
            'Dashboard/Attendances/Edit',
            [
                'attendance' => $attendance
            ]
        );
    }

    public function update(
        CreateOrUpdateAttendanceRequest $request,
        Attendance $attendance,
        SaveAttendanceAction $saveAttendanceAction
    ): RedirectResponse {
        $attendance = $saveAttendanceAction->execute($attendance, $request->validated());

        return redirect()->route("dashboard.attendances.show", ['attendance' => $attendance])->with(
            "success",
            "Attendance has been update!"
        );
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        if ($attendance->delete()) {
            return redirect()->route("dashboard.attendances.index")->with("success", "Attendance has been destroy!");
        } else {
            return redirect()->route("dashboard.attendances.index")->with("error", "Can't delete Attendance");
        }
    }

}
