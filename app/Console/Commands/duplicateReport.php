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

        $topics[21][1] = "Review Blending Short Vowel A E I O U";
        $topics[21][2] = "Review Blending Short Vowel A E I O U";
        $topics[22][1] = "Review Blending Long Vowels A E I O U";
        $topics[22][2] = "Review Blending Long Vowels A E I O U";
        $topics[23][1] = "Review Long Vowels A E I O U";
        $topics[23][2] = "Review Long Vowels A E I O U";
        $topics[24][1] = "Magic “e” /a-e/ / e-e/ /i-e/ /o-e/ /u-e/";
        $topics[24][2] = "Magic “e” /a-e/ / e-e/ /i-e/ /o-e/ /u-e/";
        $topics[25][1] = "Reading Magic “e”";
        $topics[25][2] = "Reading Magic “e”";
        $topics[26][1] = "Other Vowel Sound: ออร์ /au/ /aw/";
        $topics[26][2] = "Other Vowel Sound: ออร์ /au/ /aw/";
        $topics[27][1] = "Other Vowel Sound: ออร์ /or/ /ore/ /oar/";
        $topics[27][2] = "Other Vowel Sound: ออร์ /or/ /ore/ /oar/";
        $topics[28][1] = "Other Vowel Sound: อาร์ / แอร์ /ar/ /air/ /ear/";
        $topics[28][2] = "Other Vowel Sound: อาร์ / แอร์ /ar/ /air/ /ear/";
        $topics[29][1] = "Review หน่วย 1-8";
        $topics[29][2] = "Review หน่วย 1-8";
        $topics[30][1] = "Quiz";
        $topics[30][2] = "Quiz";
        $topics[31][1] = "Other Vowel Sound: อาว /ou/ /ow/";
        $topics[31][2] = "Other Vowel Sound: อาว /ou/ /ow/";
        $topics[32][1] = "Other Vowel Sound: ออย /oi/ /oy/";
        $topics[32][2] = "Other Vowel Sound: ออย /oi/ /oy/";
        $topics[33][1] = "Other Vowel Sound: เออร์ /er/ /ir/ /ur/";
        $topics[33][2] = "Other Vowel Sound: เออร์ /er/ /ir/ /ur/";
        $topics[34][1] = "Other Vowel Sound: เอียร์ /ear/ /eer/";
        $topics[34][2] = "Other Vowel Sound: เอียร์ /ear/ /eer/";
        $topics[35][1] = "Other Vowel Sound: อุ / อู / ยอร์ oo / OO / ure";
        $topics[35][2] = "Other Vowel Sound: อุ / อู / ยอร์ oo / OO / ure";
        $topics[36][1] = "Blending Other Vowel Sounds with Consonants";
        $topics[36][2] = "Blending Other Vowel Sounds with Consonants";
        $topics[37][1] = "Tricky words";
        $topics[37][2] = "Tricky words";
        $topics[38][1] = "Reading Tricky words";
        $topics[38][2] = "Reading Tricky words";
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
