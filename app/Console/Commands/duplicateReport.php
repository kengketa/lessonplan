<?php

namespace App\Console\Commands;

use App\Models\Report;
use Illuminate\Console\Command;

class duplicateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'duplicate reports';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dupId = $this->ask('what Id do you want to duplicate?');
        $weeks = [21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 26, 27, 28, 39, 40];

        $topics[21][1] = "Reading Sentences";
        $topics[21][2] = "Reading Sentences";
        $topics[22][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[22][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[23][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[23][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[24][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[24][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[25][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[25][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[26][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[26][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[27][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[27][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[28][1] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[28][2] = "Reading Comprehension 3: ไม่เกิน 100 คำ";
        $topics[29][1] = "Review หน่วย 1-8";
        $topics[29][2] = "Review หน่วย 1-8";
        $topics[30][1] = "Quiz";
        $topics[30][2] = "Quiz";
        $topics[31][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[31][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[32][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[32][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[33][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[33][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[34][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[34][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[35][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[35][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[36][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[36][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[37][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[37][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[38][1] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[38][2] = "Reading Comprehension 4: ไม่เกิน 150 คำ";
        $topics[39][1] = "Review หน่วย 11-18";
        $topics[39][2] = "Review หน่วย 11-18";
        $topics[40][1] = "Final Test";
        $topics[40][2] = "Final Test";
        $lessons = [1, 2];
        $report = Report::find($dupId);
        foreach ($weeks as $week) {
            foreach ($lessons as $lesson) {
                $newReport = new Report();
                $newReport->fill($report->toArray());
                $newReport->week_number = $week;
                $newReport->lesson_number = $lesson;
                $plans = $newReport->plans;
                $plans[0]['topic'] = $topics[$week][$lesson];
                $newReport->plans = $plans;
                $newReport->save();
            }
        }
        $this->info('duplicated!');
    }
}
