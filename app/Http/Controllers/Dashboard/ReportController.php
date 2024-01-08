<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\PrepareReportAction;
use App\Actions\SaveReportAction;
use App\Http\Requests\CreateOrUpdateReportRequest;
use App\Models\GlobalReport;
use App\Models\Grade;
use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\School;
use App\Transformers\GradeTransformer;
use App\Transformers\ReportTransformer;
use App\Transformers\SchoolTransformer;
use App\Transformers\UserTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Browsershot\Browsershot;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function index(Request $request, School $school): Response|RedirectResponse
    {
        $user = Auth::user();
        $thisUserIsInTheSchool = $school->teachers->filter(function ($q) use ($user) {
            return $q->id == $user->id;
        });
        if ($thisUserIsInTheSchool->count() === 0 && $user->roles[0]->name === Role::ROLE_TEACHER) {
            abort(401, 'You are not authorized.');
        }

        $cacheKey = 'cache_school_id_' . $school->id;
        $schoolData = Cache::rememberForever($cacheKey, function () use ($school) {
            $schoolData = fractal($school, new SchoolTransformer())->toArray();
            $grades = Grade::with('school')->where('school_id', $school->id)->orderBy('type')->orderBy('level')->get();
            $schoolData['grades'] = fractal($grades, new GradeTransformer())->toArray()['data'];
            $schoolData['years'] = getYears();;
            $schoolData['semesters'] = getSemesters();
            $schoolData['current_academic_year'] = getCurrentAcademicYear();
            $schoolData['current_semester'] = getCurrentSemester();
            $schoolData['weeks'] = getWeeks();
            return $schoolData;
        });
        $schoolId = $school->id;
        $reports = Report::whereHas('grade.school', function ($q) use ($schoolId) {
            $q->where('id', $schoolId);
        })->filter($request['filters'])
            ->orderBy('week_number', 'desc')
            ->orderBy('lesson_number')
            ->paginate(50);
        $reportData = fractal($reports, new ReportTransformer())->toArray();
        return Inertia::render(
            'Dashboard/Reports/Index',
            [
                'school' => $schoolData,
                'reports' => $reportData,
                'filters' => $request['filters']
            ]
        );
    }

    public function store(CreateOrUpdateReportRequest $request, SaveReportAction $saveReportAction)
    {
        $school = School::find($request['school_id']);
        $report = new Report();
        $addedReports = $saveReportAction->execute($report, $school, $request->validated());
        return redirect()->route('dashboard.schools.show', $school->id)
            ->with("success", 'Lesson plans has been created.');
    }

    public function edit(Report $report): Response
    {
        $reportData = Cache::remember('cached_report_data_' . $report->id, now()->addDay(), function () use ($report) {
            $school = $report->grade->school;
            $transformedReport = new PrepareReportAction();
            $reportData = $transformedReport->execute($school, $report);
            return $reportData;
        });

        return Inertia::render(
            'Dashboard/Reports/Edit',
            [
                'report' => $reportData
            ]
        );
    }

    public function update(
        CreateOrUpdateReportRequest $request,
        Report $report,
        SaveReportAction $saveReportAction
    ): RedirectResponse {
        $reportData = $saveReportAction->execute($report, $report->grade->school, $request->validated());
        return redirect()->route("dashboard.reports.edit", $report->id)
            ->with("success", "Report has been updated.");
    }

    public function destroy(Report $report): RedirectResponse
    {
        $reportId = $report->id;
        $schoolId = $report->grade->school->id;
        if (Auth::user()->roles[0]->name === User::ROLE_TEACHER && $report->creator_id != Auth::id()) {
            return redirect()->route("dashboard.reports.edit", $reportId)
                ->with("error", "You are not authorized to delete this report.");
        }

        if ($report->delete()) {
            return redirect()->route("dashboard.schools.show", $schoolId)->with("success", "Report has been deleted.");
        } else {
            return redirect()->route("dashboard.reports.edit", $reportId)->with("error", "Report can not be deleted.");
        }
    }

    public function approve(Report $report)
    {
        $report->approver_id = Auth::id();
        $report->save();
        return redirect()->route("dashboard.reports.edit", $report->id)->with("success", "Lesson plan approved");
    }

    public function next(Report $report)
    {
        $schoolId = $report->grade->school->id;
        $nextReport = Report::whereHas('grade.school', function ($q) use ($schoolId) {
            $q->where('id', $schoolId);
        })
            ->where('academic_year', $report->academic_year)
            ->where('semester', $report->semester)
            ->where('creator_id', $report->creator_id)
            ->where('id', '>', $report->id)
            ->first();

        if ($nextReport == null) {
            $nextReport = Report::whereHas('grade.school', function ($q) use ($schoolId) {
                $q->where('id', $schoolId);
            })
                ->where('academic_year', $report->academic_year)
                ->where('semester', $report->semester)
                ->where('creator_id', $report->creator_id)
                ->where('id', '!=', $report->id)
                ->first();
        }
        if ($nextReport == null) { // when no more report to next
            return redirect()->route("dashboard.reports.edit", $report->id);
        }
        return redirect()->route("dashboard.reports.edit", $nextReport->id);
    }

    public function previous(Report $report)
    {
        $schoolId = $report->grade->school->id;
        $nextReport = Report::whereHas('grade.school', function ($q) use ($schoolId) {
            $q->where('id', $schoolId);
        })
            ->where('academic_year', $report->academic_year)
            ->where('semester', $report->semester)
            ->where('creator_id', $report->creator_id)
            ->where('id', '<', $report->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($nextReport == null) {
            $nextReport = Report::whereHas('grade.school', function ($q) use ($schoolId) {
                $q->where('id', $schoolId);
            })
                ->where('academic_year', $report->academic_year)
                ->where('semester', $report->semester)
                ->where('creator_id', $report->creator_id)
                ->where('id', '!=', $report->id)
                ->orderBy('id', 'desc')
                ->first();
        }
        if ($nextReport == null) { // when no more report to next
            return redirect()->route("dashboard.reports.edit", $report->id);
        }
        return redirect()->route("dashboard.reports.edit", $nextReport->id);
    }

    public function print(Request $request)
    {
        $printLists = $request['print_list'];
        $toBePrintedReports = Arr::where($printLists, function ($report, $key) {
            return $report['print'] == true;
        });

        if (count($toBePrintedReports) === 0) {
            return redirect()->route('dashboard.schools.show', $request['school_id']);
        }
        $reportIds = [];
        foreach ($toBePrintedReports as $report) {
            $reportIds[] = $report['id'];
        }
        $url = route('dashboard.reports.print_preview', ['reportIds' => $reportIds]);
        //Browsershot::url($url)->save('example.pdf');
        return redirect($url);
    }

    public function printPreview(Request $request)
    {
        $reportIds = $request['reportIds'];
        $reportDataGroupByPage = $this->prepareReportGroupByPage($reportIds);
        return Inertia::render(
            'Dashboard/Reports/Print',
            [
                'reportIds' => $reportIds,
                'pages' => $reportDataGroupByPage
            ]
        );
    }

    private function prepareReportGroupByPage(array $reportIds)
    {
        $reports = Report::find($reportIds)->sortBy([
            ['grade_id', 'asc'],
            ['week_number', 'asc'],
            ['lesson_number', 'asc'],
        ]);
        $reportData = fractal($reports, new ReportTransformer())->toArray()['data'];

        $page = 1;
        $reportDataGroupByPage[$page] = [];
        foreach ($reportData as $report) {
            if (count($reportDataGroupByPage[$page]) < 2) {
                $reportDataGroupByPage[$page][] = $report;
            } else {
                $page++;
                $reportDataGroupByPage[$page][] = $report;;
            }
        }
        return $reportDataGroupByPage;
    }

    public function generateLink(Request $request)
    {
        $link = $request['link'];
        $globalLink = str_replace('dashboard/print-reports-preview', 'global-reports', $link);
        $globalReport = GlobalReport::where('link', $globalLink)->first();
        if (!$globalReport) {
            $globalReport = GlobalReport::create([
                'link' => $globalLink,
                'creator_id' => Auth::id()
            ]);
        }
        $shortenLink = route('reports.global.show', $globalReport->hashid);
        return response()->json($shortenLink);
    }

    public function create(School $school, Request $request)
    {
        $emptyReport = new PrepareReportAction();
        $reportData = $emptyReport->execute($school);
        return Inertia::render(
            'Dashboard/Reports/Create',
            [
                'report' => $reportData
            ]
        );
    }

    public function globalReports(Request $request) // to remove later
    {
        $link = route('reports.global', [
            'reportIds' => $request['reportIds']
        ]);
        $foundGeneratedLink = GlobalReport::where('link', $link)->first();
        if (!$foundGeneratedLink) {
            abort(403, 'Opps! nice tried.');
        }

        $reportIds = $request['reportIds'];
        $reportDataGroupByPage = $this->prepareReportGroupByPage($reportIds);
        return Inertia::render(
            'GlobalReports',
            [
                'pages' => $reportDataGroupByPage
            ]
        );
    }

    public function showGlobalReports(GlobalReport $globalReport)
    {
        $arr = [];
        parse_str($globalReport->link, $arr);
        $reportIds = Arr::flatten($arr);
        $reportDataGroupByPage = $this->prepareReportGroupByPage($reportIds);
        return Inertia::render(
            'GlobalReports',
            [
                'pages' => $reportDataGroupByPage
            ]
        );

        return redirect($globalReport->link);
    }

}
