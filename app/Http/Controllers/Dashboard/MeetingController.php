<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveMeetingAction;
use App\Http\Requests\CreateOrUpdateMeetingRequest;
use App\Models\Meeting;
use App\Http\Controllers\Controller;
use App\Transformers\AgendaTransformer;
use App\Transformers\MeetingTransformer;
use App\Transformers\SchoolTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\School;

class MeetingController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search", "school"]);
        $meetings = Meeting::filter($filters)->orderBy('date', 'desc')->paginate(30);
        $meetingData = fractal($meetings, new MeetingTransformer())->toArray();
        $schools = School::all();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/Meetings/Index',
            [
                'meetings' => $meetingData,
                'schools' => $schoolData,
                'filters' => $filters,
            ]);
    }

    public function create(): Response
    {
        $schools = School::all();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/Meetings/Create',
            [
                'schools' => $schoolData,
                'meeting' => new Meeting()
            ]);
    }

    public function store(CreateOrUpdateMeetingRequest $request, SaveMeetingAction $saveMeetingAction): RedirectResponse
    {
        $meeting = new Meeting();
        $meeting = $saveMeetingAction->execute($meeting, $request->validated());

        return redirect()->route('dashboard.meetings.show', ['meeting' => $meeting])
            ->with("success", 'Meeting  has been created.');
    }

    public function show(Meeting $meeting): Response
    {
        $meeting = fractal($meeting, new MeetingTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Meetings/Show',
            [
                'meeting' => $meeting,
            ]);
    }

    public function edit(Meeting $meeting): Response
    {
        $meetingData = fractal($meeting, new MeetingTransformer())->toArray();
        $meetingData['agendas'] = fractal($meeting->agendas, new AgendaTransformer())->toArray()['data'];
        $schools = School::all();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/Meetings/Edit',
            [
                'schools' => $schoolData,
                'meeting' => $meetingData
            ]);
    }

    public function update(
        CreateOrUpdateMeetingRequest $request,
        Meeting $meeting,
        SaveMeetingAction $saveMeetingAction
    ): RedirectResponse {
        $meeting = $saveMeetingAction->execute($meeting, $request->validated());

        return redirect()->route("dashboard.meetings.edit", ['meeting' => $meeting])
            ->with("success", "Meeting has been update!");
    }

    public function destroy(Meeting $meeting): RedirectResponse
    {
        if ($meeting->delete()) {
            return redirect()->route("dashboard.meetings.index")->with("success", "Meeting has been destroy!");
        } else {
            return redirect()->route("dashboard.meetings.index")->with("error", "Can't delete Meeting");
        }
    }

}
