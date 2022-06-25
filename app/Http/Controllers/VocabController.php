<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Models\Vocab;
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

    public function fetchVocabs(Grade $grade)
    {
        $vocabData = $grade->vocabs->groupBy('subject_name')->toArray();
        return response()->json($vocabData);
    }

    public function update(Vocab $vocab, Request $request)
    {
        $vocab->vocab_th = $request->thaiWord;
        $vocab->save();
        return response()->json($vocab);
    }
}
