<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Transformers\GradeTransformer;
use App\Transformers\SchoolTransformer;
use App\Transformers\VocabTransformer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VocabController extends Controller
{
    public function index()
    {
        $school = School::find(3);
        $grades = $school->grades;
        $gradeData = fractal($grades, new GradeTransformer())->toArray()['data'];
        return Inertia::render(
            'Frontend/Index', [
                'school' => fractal($school, new SchoolTransformer())->toArray(),
                'grades' => $gradeData
            ]
        );
    }

    public function show(Grade $grade)
    {
        dd($grade);

    }
}
