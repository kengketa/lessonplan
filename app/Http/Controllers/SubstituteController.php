<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Substitute;
use App\Transformers\SchoolTransformer;
use App\Transformers\SubstituteTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubstituteController extends Controller
{
    public function index(School $school)
    {
        $substitutes = Substitute::where('school_id', $school->id)
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();
        $substituteData = fractal($substitutes, new SubstituteTransformer())->toArray()['data'];
        return Inertia::render('Dashboard/Substitute/Index')->with([
            'substitutes' => $substituteData,
            'school' => fractal($school, new SchoolTransformer())->toArray(),
        ]);
    }

    public function store(Request $request, School $school)
    {
        $req = $request->validate([
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'grade' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'absent' => ['required', 'string'],
            'substitute' => ['nullable', 'string'],
        ]);
        Substitute::create([
            'school_id' => $school->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'start_time' => $req['start_time'],
            'end_time' => $req['end_time'],
            'grade' => $req['grade'],
            'subject' => $req['subject'],
            'teacher' => $req['absent'],
            'volunteer' => $req['substitute'] ?? null,
        ]);
        return redirect()->back();
    }

    public function update(Substitute $substitute, Request $request)
    {
        $req = $request->validate([
            'volunteer' => ['required', 'string'],
        ]);
        $substitute->volunteer = $req['volunteer'];
        $substitute->save();
        return redirect()->back();
    }

    public function destroy(Substitute $substitute)
    {
        $substitute->delete();
        return redirect()->back();
    }

    public function volunteer()
    {
        $school = School::find(8);
        $substitutes = Substitute::where('school_id', $school->id)
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();
        $substituteData = fractal($substitutes, new SubstituteTransformer())->toArray()['data'];
        return Inertia::render('Frontend/Volunteer')->with([
            'substitutes' => $substituteData,
            'school' => fractal($school, new SchoolTransformer())->toArray(),
        ]);
    }

    public function print(School $school)
    {
        $school = School::find(8);
        $substitutes = Substitute::where('school_id', $school->id)
            ->whereDate('date', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();
        $substituteData = fractal($substitutes, new SubstituteTransformer())->toArray()['data'];
        return Inertia::render('Frontend/PrintVolunteer')->with([
            'substitutes' => $substituteData,
            'school' => fractal($school, new SchoolTransformer())->toArray(),
        ]);
    }
}
