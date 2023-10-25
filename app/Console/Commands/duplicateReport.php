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

        $topics[21][1] = "Pronouns คำสรรพนาม";
        $topics[21][2] = "Pronouns คำสรรพนาม";
        $topics[22][1] = "Pronouns คำสรรพนาม";
        $topics[22][2] = "Pronouns คำสรรพนาม";
        $topics[23][1] = "Present Simple Tense of Verb to be";
        $topics[23][2] = "Present Simple Tense of Verb to be";
        $topics[24][1] = "Past Simple Tense of Verb to be";
        $topics[24][2] = "Past Simple Tense of Verb to be";
        $topics[25][1] = "ประโยคคล้อยตาม";
        $topics[25][2] = "ประโยคคล้อยตาม";
        $topics[26][1] = "ประโยคคล้อยตาม";
        $topics[26][2] = "ประโยคคล้อยตาม";
        $topics[27][1] = "Imperative/Order/ Request";
        $topics[27][2] = "Imperative/Order/ Request";
        $topics[28][1] = "Imperative/Order/ Request";
        $topics[28][2] = "Imperative/Order/ Request";
        $topics[29][1] = "Number";
        $topics[29][2] = "Number";
        $topics[30][1] = "Number";
        $topics[30][2] = "Number";
        $topics[31][1] = "Number";
        $topics[31][2] = "Review";
        $topics[32][1] = "Review";
        $topics[32][2] = "Adjectives คำคุณศัพท์";
        $topics[33][1] = "Adjectives คำคุณศัพท์";
        $topics[33][2] = "Adjectives คำคุณศัพท์";
        $topics[34][1] = "Adverbs คำกริยาวิเศษณ์";
        $topics[34][2] = "Adverbs คำกริยาวิเศษณ์";
        $topics[35][1] = "Greeting and Fare wells";
        $topics[35][2] = "Greeting and Fare wells";
        $topics[36][1] = "Greeting and Fare wells";
        $topics[36][2] = "Greeting and Fare wells";
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
