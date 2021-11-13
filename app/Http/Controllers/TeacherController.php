<?php

namespace App\Http\Controllers;

use App\Actions\GetTimeSheetAction;
use App\Models\ClockIn;
use App\Models\Role;
use App\Transformers\ClockInTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeacherController extends Controller
{
    public function myTimeSheet()
    {
        $user = Auth::user();
        if ($user->roles[0]->name != Role::ROLE_TEACHER) {
            return redirect()->back();
        }
        $thisMonth = (int)Carbon::today()->format('m');
        $thisYear = (int)Carbon::today()->format('Y');
        $timeSheet = new GetTimeSheetAction();
        $clockInData['thisMonth'] = $timeSheet->execute($user, $thisMonth, $thisYear);
        $lastMonth = $thisMonth - 1;
        $lastYear = $thisYear;
        if ($thisMonth == 1) {
            $lastMonth = 12;
            $lastYear = $thisYear - 1;
        }
        $clockInData['lastMonth'] = $timeSheet->execute($user, $lastMonth, $lastYear);
//        dd($clockInData);
        return Inertia::render(
            'Dashboard/Teachers/MyTimeSheet',
            [
                "clockIn" => $clockInData,
            ]
        );


    }
}
