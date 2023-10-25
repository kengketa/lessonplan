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

        $topics[21][1] = "Past simple Tense";
        $topics[21][2] = "Past simple Tense";
        $topics[22][1] = "Past simple Tense";
        $topics[22][2] = "Future simple Tense";
        $topics[23][1] = "Future simple Tense";
        $topics[23][2] = "Requests";
        $topics[24][1] = "Somebody/anybody/ Someone/Anyone/Nobody/No one";
        $topics[24][2] = "Somebody/anybody/ Someone/Anyone/Nobody/No one";
        $topics[25][1] = "Must / have to";
        $topics[25][2] = "Can/ be able to";
        $topics[26][1] = "May";
        $topics[26][2] = "Should และ Ought to";
        $topics[27][1] = "Past  Progressive Tense/ Past Continuous Tense";
        $topics[27][2] = "Past  Progressive Tense/ Past Continuous Tense";
        $topics[28][1] = "Past  Progressive Tense/ Past Continuous Tense";
        $topics[28][2] = "Past  Progressive Tense/ Past Continuous Tense";
        $topics[29][1] = "Present Perfect Tense";
        $topics[29][2] = "Present Perfect Tense";
        $topics[30][1] = "Present Perfect Tense";
        $topics[30][2] = "Present Perfect Tense";
        $topics[31][1] = "Condition การใช้ if";
        $topics[31][2] = "Condition การใช้ if";
        $topics[32][1] = "Condition การใช้ if";
        $topics[32][2] = "Condition การใช้ if";
        $topics[33][1] = "Active voice และ Passive Voice";
        $topics[33][2] = "Active voice และ Passive Voice";
        $topics[34][1] = "Active voice และ Passive Voice";
        $topics[34][2] = "Active voice และ Passive Voice";
        $topics[35][1] = "Linking Verb";
        $topics[35][2] = "Speaking";
        $topics[36][1] = "Speaking";
        $topics[36][2] = "Reading";
        $topics[37][1] = "Reading";
        $topics[37][2] = "ฝึกทำข้อสอบ";
        $topics[38][1] = "ฝึกทำข้อสอบ";
        $topics[38][2] = "ฝึกทำข้อสอบ";
        $topics[39][1] = "ฝึกทำข้อสอบ";
        $topics[39][2] = "ฝึกทำข้อสอบ";
        $topics[40][1] = "ฝึกทำข้อสอบ";
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
