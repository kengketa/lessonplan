<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Transformers\SchoolTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function dashboard()
    {
        $filters = [];
        $schools = School::filter($filters)->get();
        $schoolData = fractal($schools, new SchoolTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/Index',
            [
                'schools' => $schoolData
            ]);
    }
}
