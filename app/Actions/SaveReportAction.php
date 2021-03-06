<?php

namespace App\Actions;

use App\Jobs\TranslateVocabAndAddToDatabase;
use App\Models\Report;
use App\Models\School;
use App\Models\Vocab;
use App\Services\TranslationService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaveReportAction
{
    protected Report $report;

    public function execute(Report $report, School $school, array $data): array
    {
        $this->report = $report;
        if ($this->report->id) {
            $updatedReport = $this->updateReport($data);
            $this->addVocabs($updatedReport['id']);
            return $updatedReport;
        }
        $createdReports = $this->createReports($data);
        foreach ($createdReports as $createdReport) {
            $this->addVocabs($createdReport->id);
        }
        return $createdReports;
    }

    private function addVocabs($reportId): void
    {
        $report = Report::find($reportId);
        foreach ($report['plans'] as $plan) {
            foreach ($plan['vocabs'] as $vocab) {
                $duplicatedVocab = Vocab::where('school_id', $report->grade->school->id)
                    ->where('academic_year', getCurrentAcademicYear())
                    ->where('semester', getCurrentSemester())
                    ->where('grade_id', $report->grade_id)
                    ->where('subject_id', $report->getSubjectId())
                    ->where('vocab_en', strtolower($vocab))->first();
                if ($duplicatedVocab) {
                    continue;
                }
                TranslateVocabAndAddToDatabase::dispatch($report, $vocab);
            }
        }
    }

    private function updateReport($data): array
    {
        $plans = [];
        foreach ($data['report']['plans'] as $key => $plan) {
            $plans[$key]['type'] = $plan['type'];
            $plans[$key]['topic'] = $plan['topic'];
            $plans[$key]['vocabs'] = $plan['vocabs'];
            $plans[$key]['details'] = $plan['details'];
        }
        $date = null;
        if (!empty($data['report']['for_grades'][0]['date'])) {
            $date = Carbon::parse($data['report']['for_grades'][0]['date'])->format('Y-m-d');
        }
        $this->report->grade_id = $data['report']['for_grades'][0]['id'];
        $this->report->date = $date;
        $this->report->week_number = $data['week_number'];
        $this->report->lesson_number = $data['lesson_number'];
        $this->report->plans = $plans;
        $this->report->subject = $data['subject'];
        $this->report->updated_at = Carbon::now();
        $this->report->approver_id = null;
        $this->report->teaching_materials = $data['teaching_materials'];
        $this->report->activities = $data['activities'];
        $this->report->outcome = $data['outcome'];
        $this->report->outstanding_students = $data['outstanding_students'];
        $this->report->need_improvement_students = $data['need_improvement_students'];
        $this->report->save();
        $updatedReport = new PrepareReportAction();
        $updatedReportData = $updatedReport->execute($this->report->grade->school, $this->report);
        return $updatedReportData;
    }

    private function createReports($data): array
    {
        $grades = $data['report']['for_grades'];
        $plans = [];
        foreach ($data['report']['plans'] as $key => $plan) {
            $plans[$key]['type'] = $plan['type'];
            $plans[$key]['topic'] = $plan['topic'];
            $plans[$key]['vocabs'] = $plan['vocabs'];
            $plans[$key]['details'] = $this->cleanString($plan['details']);
        }

        $addedReports = [];
        foreach ($grades as $grade) {
            $date = null;
            if ($grade['date']) {
                $date = Carbon::parse($grade['date'])->format('Y-m-d');
            }
            $newReport['grade_id'] = $grade['id'];
            $newReport['date'] = $date;
            $newReport['academic_year'] = getCurrentAcademicYear();
            $newReport['semester'] = getCurrentSemester();
            $newReport['week_number'] = $data['week_number'];
            $newReport['lesson_number'] = $data['lesson_number'];
            $newReport['teaching_materials'] = $data['teaching_materials'];
            $newReport['activities'] = $data['activities'];
            $newReport['plans'] = $plans;
            $newReport['subject'] = $data['subject'];
            $newReport['creator_id'] = Auth::id();
            $newReport['approver_id'] = null;
            $addedReports[] = Report::create($newReport);
        }
        return $addedReports;
    }

    function cleanString($text)
    {
        $utf8 = array(
            '/[????????????]/u' => 'a',
            '/[??????????]/u' => 'A',
            '/[????????]/u' => 'I',
            '/[????????]/u' => 'i',
            '/[????????]/u' => 'e',
            '/[????????]/u' => 'E',
            '/[????????????]/u' => 'o',
            '/[??????????]/u' => 'O',
            '/[????????]/u' => 'u',
            '/[????????]/u' => 'U',
            '/??/' => 'c',
            '/??/' => 'C',
            '/??/' => 'n',
            '/??/' => 'N',
            '/???/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[???????????????]/u' => ' ', // Literally a single quote
            '/[?????????????]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

}
