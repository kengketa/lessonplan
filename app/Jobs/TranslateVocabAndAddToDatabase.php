<?php

namespace App\Jobs;


use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TranslationService;
use App\Models\Vocab;

class TranslateVocabAndAddToDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Report $report;
    protected string $vocab;


    public function __construct(Report $report, string $vocab)
    {
        $this->report = $report;
        $this->vocab = $vocab;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Vocab::create([
            'academic_year' => getCurrentAcademicYear(),
            'semester' => getCurrentSemester(),
            'school_id' => $this->report->grade->school->id,
            'grade_id' => $this->report->grade_id,
            'subject_id' => $this->report->getSubjectId(),
            'subject_name' => $this->report->getSubjectName(),
            'vocab_en' => strtolower($this->vocab),
            'vocab_th' => strtolower(TranslationService::translate($this->vocab))
        ]);
    }
}
