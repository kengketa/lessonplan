<?php

namespace App\Console\Commands;

use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

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
        //        $schoolData = fractal($school, new SchoolTransformer())->toArray();
//        $reportGroupedByGrade = Report::where('academic_year', getCurrentAcademicYear())
//            ->where('semester', getCurrentSemester())->whereHas('grade.school', function ($q) use ($school) {
//                $q->where('id', $school->id);
//            })
//            ->orderBy('grade_id', 'asc')
//            ->get()->groupBy('grade_id');
//
//        foreach ($reportGroupedByGrade as $reports) {
////            $vocabData[$reports[0]->grade->fullName()] = fractal($reports, new VocabTransformer())->toArray()['data'];
//            $vocabData[] = [
//                'grade' => $reports[0]->grade->toArray(),
//                'gradeFullName' => $reports[0]->grade->fullName()
//            ];
//        }
    }
}
