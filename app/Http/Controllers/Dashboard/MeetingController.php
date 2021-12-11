<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\SaveMeetingAction;
use App\Http\Requests\CreateOrUpdateMeetingRequest;
use App\Models\Meeting;
use App\Http\Controllers\Controller;
use App\Transformers\MeetingTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MeetingController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $meetings = Meeting::filter($filters)->orderBy('date', 'desc')->paginate(30);
        $meetings = fractal($meetings, new MeetingTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Meetings/Index',
            [
                'meetings' => $meetings,
                'filters' => $filters,
            ]);
    }

    public function create(): Response
    {
        return Inertia::render(
            'Dashboard/Meetings/Create',
            [
                'meeting' => new Meeting()
            ]);
    }

    public function store(CreateOrUpdateMeetingRequest $request, SaveMeetingAction $saveMeetingAction): RedirectResponse
    {
        $meeting = new Meeting();
        $meeting = $saveMeetingAction->execute($meeting, $request->validated());

        return redirect()->route('dashboard.meetings.show', ['meeting' => $meeting])->with("success",
            ' Meeting  has been create!');
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
        return Inertia::render(
            'Dashboard/Meetings/Edit',
            [
                'meeting' => $meeting
            ]);
    }

    public function update(
        CreateOrUpdateMeetingRequest $request,
        Meeting $meeting,
        SaveMeetingAction $saveMeetingAction
    ): RedirectResponse {
        $meeting = $saveMeetingAction->execute($meeting, $request->validated());

        return redirect()->route("dashboard.meetings.show", ['meeting' => $meeting])->with("success",
            "Meeting has been update!");
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
