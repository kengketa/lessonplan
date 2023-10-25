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
        $weeks = [21,22, 23, 24, 25, 26, 27,28, 29, 30, 31, 32, 33, 34, 35, 26, 27, 28, 39,40];

        $topics[21][1] = "Conversation บทสนาทนา";
        $topics[21][2] = "Conversation บทสนาทนา";
        $topics[22][1] = "Conversation บทสนาทนา";
        $topics[22][2] = "Conversation บทสนาทนา";
        $topics[23][1] = "การใช้ Too และ Enough";
        $topics[23][2] = "การใช้ Too และ Enough";
        $topics[24][1] = "Connectives คำเชื่อม";
        $topics[24][2] = "Connectives คำเชื่อม";
        $topics[25][1] = "Connectives คำเชื่อม";
        $topics[25][2] = "Connectives คำเชื่อม";
        $topics[26][1] = "Comparison การเปรียบเทียบ";
        $topics[26][2] = "Comparison การเปรียบเทียบ";
        $topics[27][1] = "Comparison การเปรียบเทียบ";
        $topics[27][2] = "Comparison การเปรียบเทียบ";
        $topics[28][1] = "Past simple Tense";
        $topics[28][2] = "Past simple Tense";
        $topics[29][1] = "Past simple Tense";
        $topics[29][2] = "Past simple Tense";
        $topics[30][1] = "Review";
        $topics[30][2] = "Review";
        $topics[31][1] = "Future simple Tense";
        $topics[31][2] = "Future simple Tense";
        $topics[32][1] = "Future simple Tense";
        $topics[32][2] = "Future simple Tense";
        $topics[33][1] = "Adverb of Frequency กริยาวิเศษณ์บอกความถี่";
        $topics[33][2] = "Adverb of Frequency กริยาวิเศษณ์บอกความถี่";
        $topics[34][1] = "Questions and Answers";
        $topics[34][2] = "Questions and Answers";
        $topics[35][1] = "Present Progressive Tense / Continuous Tense";
        $topics[35][2] = "Present Progressive Tense / Continuous Tense";
        $topics[36][1] = "Present Progressive Tense / Continuous Tense";
        $topics[36][2] = "Present Progressive Tense / Continuous Tense";
        $topics[37][1] = "Reading and Vocabulary";
        $topics[37][2] = "Reading and Vocabulary";
        $topics[38][1] = "Reading and Vocabulary";
        $topics[38][2] = "Reading and Vocabulary";
        $topics[39][1] = "Reading and Vocabulary";
        $topics[39][2] = "Reading and Vocabulary";
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
