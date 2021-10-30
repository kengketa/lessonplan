<?php

namespace App\Http\Controllers;

use App\Models\ClockIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClockInController extends Controller
{
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
