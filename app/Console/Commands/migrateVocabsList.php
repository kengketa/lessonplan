<?php

namespace App\Console\Commands;

use App\Models\School;
use App\Models\Vocab;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use App\Models\Report;

class migrateVocabsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabs:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate vocabs to vocabs table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $school = School::find(3);
        $reports = Report::where('academic_year', getCurrentAcademicYear())
            ->where('semester', getCurrentSemester())->whereHas('grade.school', function ($q) use ($school) {
                $q->where('id', $school->id);
            })
            ->orderBy('grade_id', 'asc')
            ->get();

        foreach ($reports as $report) {
            foreach ($report->plans as $plan) {
                if (count($plan['vocabs']) == 0) {
                    continue;
                }
                foreach ($plan['vocabs'] as $vocab) {
                    if ($vocab == null || $vocab == "" || !$report->getSubjectId()) {
                        continue;
                    }
                    $duplicatedVocab = Vocab::where('school_id', $school->id)
                        ->where('academic_year', getCurrentAcademicYear())
                        ->where('semester', getCurrentSemester())
                        ->where('grade_id', $report->grade_id)
                        ->where('subject_id', $report->getSubjectId())
                        ->where('vocab_en', $vocab)->first();
                    if ($duplicatedVocab) {
                        continue;
                    }
                    Vocab::create([
                        'academic_year' => getCurrentAcademicYear(),
                        'semester' => getCurrentSemester(),
                        'school_id' => $school->id,
                        'grade_id' => $report->grade_id,
                        'subject_id' => $report->getSubjectId(),
                        'subject_name' => $report->getSubjectName(),
                        'vocab_en' => $vocab
                    ]);
                    $this->info('added:'.$vocab);
                }
            }
        }
    }
}
