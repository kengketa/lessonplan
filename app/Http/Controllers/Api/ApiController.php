<?php

namespace App\Http\Controllers\Api;

use App\Actions\SaveReportAction;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use App\Models\Grade;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;


class ApiController extends Controller
{
    public function injectLessonPlans(Request $request)
    {
        $req = $request->all();
        try {
            DB::beginTransaction();
            $teacherName = strtolower($req['plans'][0]['teacher']);
            $teacherEmail = $req['plans'][0]['email'];
            $teacherNameLower = strtolower($teacherName);
            $user = User::where('email', $teacherEmail)->first();
            $school = School::find(8);
            if (!$user) {
                $user = User::factory()->create([
                    'name' => $teacherName,
                    'email' => $teacherEmail,
                ]);
                $user->assignRole(Role::where("name", \App\Models\Role::ROLE_TEACHER)->first());
            }
            $school->teachers()->syncWithoutDetaching([$user->id]);
            $arrOfGrades = explode('/', $req['plans'][0]['grade']);
            $grade = Grade::where('school_id', $school->id)
                ->where('type', 3)
                ->where('level', $arrOfGrades[0])
                ->where('room_number', $arrOfGrades[1])
                ->first();
            $subjectId = getSubjectIdByName(8, $req['plans'][0]['subject']);
            foreach ($req['plans'] as $plan) {
                if (empty($plan['plans'])) {
                    continue;
                }
                Report::updateOrCreate(
                    [
                        'grade_id' => $grade->id,
                        'academic_year' => getCurrentAcademicYear(),
                        'semester' => getCurrentSemester(),
                        'week_number' => $plan['week_number'],
                        'lesson_number' => $plan['lesson_number'],
                        'subject' => $subjectId,
                        'creator_id' => $user->id,
                    ],
                    [
                        'date' => null,
                        'plans' => $plan['plans'],
                        'teaching_materials' => null,
                        'activities' => null,
                        'outcome' => null,
                        'outstanding_students' => null,
                        'need_improvement_students' => null,
                        'approver_id' => null,
                    ]
                );
            }
            DB::commit();
            Artisan::call('cache:clear');
            return response()->json(['message' => 'success'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            return response()->json($exception);
        }
    }
}
