<?php

namespace App\Http\Controllers;

use App\Models\Misbehavior;
use App\Models\School;
use App\Transformers\MisbehaviorTransformer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Inertia\Inertia;

class MisbehaviorController extends Controller
{
    public function index(School $school)
    {
        $misbehaviorStudents = Misbehavior::whereHas('report', function ($q) use ($school) {
            $q->where('academic_year', getCurrentAcademicYear())->where('semester', getCurrentSemester())
                ->whereHas('grade.school', function ($r) use ($school) {
                    $r->where('id', $school->id);
                });
        })->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('grade')
            ->orderBy('name')
            ->get();
        $misbehaviorStudentData = fractal($misbehaviorStudents, new MisbehaviorTransformer())->toArray()['data'];
        return Inertia::render(
            'Dashboard/Misbehavior/Index',
            [
                'students' => $misbehaviorStudentData,
                'school' => $school
            ]);
    }
}
