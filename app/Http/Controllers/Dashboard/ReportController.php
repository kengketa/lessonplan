<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\PrepareReportAction;
use App\Actions\SaveReportAction;
use App\Http\Requests\CreateOrUpdateReportRequest;
use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Transformers\ReportTransformer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = $request->only(["search"]);
        $reports = Report::filter($filters)->latest()->paginate(30);
        $reports = fractal($reports, new ReportTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Reports/Index',
            [
                'reports' => $reports,
                'filters' => $filters,
            ]);
    }

    public function create(School $school, Request $request)
    {
        $emptyReport = new PrepareReportAction();
        $reportData = $emptyReport->execute($school);
        return Inertia::render(
            'Dashboard/Reports/Create',
            [
                'report' => $reportData
            ]);
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
        $school = $report->grade->school;
        $transformedReport = new PrepareReportAction();
        $reportData = $transformedReport->execute($school, $report);
        return Inertia::render(
            'Dashboard/Reports/Edit',
            [
                'report' => $reportData
            ]);
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
        if ($report->delete()) {
            return redirect()->route("dashboard.reports.index")->with("success", "Report has been destroy!");
        } else {
            return redirect()->route("dashboard.reports.index")->with("error", "Can't delete Report");
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

    public function print(Request $request)
    {
        $printLists = $request['print_list'];
        $toBePrintedReports = Arr::where($printLists, function ($report, $key) {
            return $report['print'] == true;
        });
        //dd($toBePrintedReports);
        $reportIds = [];
        foreach ($toBePrintedReports as $report) {
            $reportIds[] = $report['id'];
        }
        $reports = Report::find($reportIds);
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
//        dd($reportDataGroupByPage);
        return Inertia::render(
            'Dashboard/Reports/Print',
            [
                'pages' => $reportDataGroupByPage
            ]);
    }

}
