<?php

namespace App\Http\Controllers;

use App\Models\ClockIn;
use App\Models\Role;
use App\Models\School;
use App\Models\User;
use App\Transformers\ClockInTransformer;
use App\Transformers\SchoolTransformer;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ClockInController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request['filters'];
        if (!$filters) {
            $filters['school_id'] = null;
            $filters['teacher_id'] = null;
        }
        dd(allMonths());
        $clokIns = ClockIn::filter($filters)->orderBy('date')->paginate(15);
        $clokInData = fractal($clokIns, new ClockInTransformer())->toArray();
        $allSchools = School::all();
        $allschoolData = fractal($allSchools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/ClockIns/Index',
            [
                'filters' => $filters,
                'allSchools' => $allschoolData,
                'clockIns' => $clokInData,
            ]);
    }

    public function in(Request $request)
    {
        $teacher = Auth::user();
        $now = Carbon::now();
        $clockedIn = ClockIn::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school[0]->id)
            ->where('date', $now->format('Y-m-d'))
            ->first();
        if ($clockedIn) {
            return redirect()->route('dashboard')->with('error', 'You have already clocked in today.');
        }
        if (!$clockedIn) {
            $clockedIn = ClockIn::create([
                'teacher_id' => $teacher->id,
                'school_id' => $teacher->school[0]->id,
                'date' => $now->format('Y-m-d'),
                'clock_in' => $now,
                'clock_out' => null
            ]);
        }
        $message = 'You have clocked in at '.$now->format('h:i:sa');
        return redirect()->route('dashboard')->with('success', $message);
    }

    public function out(Request $request)
    {
        $teacher = Auth::user();
        $now = Carbon::now();
        $clockedIn = ClockIn::where('teacher_id', $teacher->id)
            ->where('school_id', $teacher->school[0]->id)
            ->where('date', $now->format('Y-m-d'))
            ->first();

        $clockedIn->clock_out = $now;
        $clockedIn->save();
        $message = 'You have clocked out at '.$now->format('h:i:sa');
        return redirect()->route('dashboard')->with('success', $message);

    }
}
