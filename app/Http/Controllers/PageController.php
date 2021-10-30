<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Transformers\SchoolTransformer;
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
        if ($user->present()->role === Role::ROLE_TEACHER) {
            $siteCoordinates['lat'] = $user->school[0]->lat;
            $siteCoordinates['lng'] = $user->school[0]->lng;
            $siteCoordinates['radius'] = $user->school[0]->radius;
        }
        return Inertia::render(
            'Dashboard/Index',
            [
                'siteCoordinates' => $siteCoordinates,
                'schools' => $schoolData
            ]);
    }
}
