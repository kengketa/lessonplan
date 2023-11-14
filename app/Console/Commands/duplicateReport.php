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

        $topics[21][1] = "Consonant blends: S Sc / St / Sw";
        $topics[21][2] = "Consonant blends: S Sc / St / Sw";
        $topics[22][1] = "Consonant blends: S Scr / Squ / Spl / Spr / Str";
        $topics[22][2] = "Consonant blends: S Scr / Squ / Spl / Spr / Str";
        $topics[23][1] = "Reading Consonant blends: S";
        $topics[23][2] = "Reading Consonant blends: S";
        $topics[24][1] = "Consonant blends: T Th / Tr / Tw / Thr";
        $topics[24][2] = "Consonant blends: T Th / Tr / Tw / Thr";
        $topics[25][1] = "Reading Consonant blends: T";
        $topics[25][2] = "Reading Consonant blends: T";
        $topics[26][1] = "Consonant blends: L Bl / Cl / Fl / Gl";
        $topics[26][2] = "Consonant blends: L Bl / Cl / Fl / Gl";
        $topics[27][1] = "Consonant blends: L Pl / Sl / Spl";
        $topics[27][2] = "Consonant blends: L Pl / Sl / Spl";
        $topics[28][1] = "Reading Consonant blends: L";
        $topics[28][2] = "Reading Consonant blends: L";
        $topics[29][1] = "Review หน่วย 1-8";
        $topics[29][2] = "Review หน่วย 1-8";
        $topics[30][1] = "Quiz";
        $topics[30][2] = "Quiz";
        $topics[31][1] = "Consonant blends: R Br / Cr / Dr / Fr / Gr";
        $topics[31][2] = "Consonant blends: R Br / Cr / Dr / Fr / Gr";
        $topics[32][1] = "Consonant blends: RPr / Tr / Scr / Spr";
        $topics[32][2] = "Consonant blends: RPr / Tr / Scr / Spr";
        $topics[33][1] = "Consonant Digraphs: Ch / Sh";
        $topics[33][2] = "Consonant Digraphs: Ch / Sh";
        $topics[34][1] = "Consonant Digraphs: Th/ Thr";
        $topics[34][2] = "Consonant Digraphs: Th/ Thr";
        $topics[35][1] = "Consonant Digraphs: Kn / Ng";
        $topics[35][2] = "Consonant Digraphs: Kn / Ng";
        $topics[36][1] = "Consonant Digraphs: Wh / Wr";
        $topics[36][2] = "Consonant Digraphs: Wh / Wr";
        $topics[37][1] = "Consonant Digraphs: Ph";
        $topics[37][2] = "Consonant Digraphs: Ph";
        $topics[38][1] = "Reading Consonant Digraphs";
        $topics[38][2] = "Reading Consonant Digraphs";
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
