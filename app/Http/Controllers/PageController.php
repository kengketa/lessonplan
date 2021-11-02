<?php

namespace App\Http\Controllers;

use App\Models\ClockIn;
use App\Models\School;
use App\Transformers\ClockInTransformer;
use App\Transformers\SchoolTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Role;

class PageController extends Controller
{
    public function dashboard()
    {
        $filters = [];
        $schools = School::filter($filters)->get();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        $user = Auth::user();
        $siteCoordinates = null;
        $clockedInData = null;
        if ($user->present()->role === Role::ROLE_TEACHER && $user->school->count() > 0) {
            $siteCoordinates['lat'] = $user->school[0]->lat;
            $siteCoordinates['lng'] = $user->school[0]->lng;
            $siteCoordinates['radius'] = $user->school[0]->radius;
            $siteCoordinates['school_id'] = $user->school[0]->id;
            $siteCoordinates['school_name'] = $user->school[0]->name;

            $now = Carbon::now();
            $clockedIn = ClockIn::where('teacher_id', $user->id)
                ->where('school_id', $user->school[0]->id)
                ->where('date', $now->format('Y-m-d'))
                ->first();
            if ($clockedIn) {
                $clockedInData = fractal($clockedIn, new ClockInTransformer())->toArray();
            }
        }
        return Inertia::render(
            'Dashboard/Index',
            [
                'clockedIn' => $clockedInData,
                'siteCoordinates' => $siteCoordinates,
                'schools' => $schoolData
            ]);
    }
}
