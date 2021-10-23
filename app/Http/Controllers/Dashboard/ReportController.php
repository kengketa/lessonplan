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

    public function show(Report $report): Response
    {
        $report = fractal($report, new ReportTransformer())->toArray();

        return Inertia::render(
            'Dashboard/Reports/Show',
            [
                'report' => $report,
            ]);
    }

    public function edit(Report $report): Response
    {
        $school = $report->grade->school;
        $emptyReport = new PrepareReportAction();
        $reportData = $emptyReport->execute($school, $report);
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

}
