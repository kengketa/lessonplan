<?php

namespace App\Console\Commands;

use App\Models\Report;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DuplicateLessonPlansToSpecfiedSemesterCommand extends Command
{
    protected $signature = 'lesson-plans:duplicate';

    protected $description = 'Duplicates lesson plans from a specified origin semester/year to a destination semester/year.';

    public function handle()
    {
        $this->info('Starting Lesson Plan Duplication Assistant...');
        $user = null;

        while (!$user) {
            $nameInput = $this->ask('What is your name?');
            $user = User::where('name', 'like', '%' . $nameInput . '%')->first();

            if (!$user) {
                $this->error("User with name matching '{$nameInput}' not found. Please try a different name.");
            }
        }

        $originSemesterYear = $this->ask('Enter the origin semester and year to duplicate from (e.g., 1/2025):');
        $destinationSemesterYear = $this->ask(
            'Enter the destination semester and year to duplicate to (e.g., 1/2026):'
        );

        $this->newLine();
        $this->info("--- Duplication Summary ---");
        $this->table(
            ['Parameter', 'Value'],
            [
                ['Duplication Name', $user->name],
                ['email', $user->email],
                ['school', $user->school[0]->name ?? 'N/A'],
                ['Source Semester/Year', $originSemesterYear],
                ['Destination Semester/Year', $destinationSemesterYear],
            ],
            'box'
        );
        $this->newLine();

        if (!$this->confirm("Please confirm the details above. Do you wish to proceed with the duplication?")) {
            $this->error("❌ Duplication canceled by user.");
            return 1;
        }

        $origins = explode('/', $originSemesterYear);
        $destinations = explode('/', $destinationSemesterYear);

        if (count($origins) !== 2 || count($destinations) !== 2) {
            $this->error("Invalid semester/year format. Please use 'semester/year' (e.g., 1/2025).");
            return 1;
        }

        $originSemester = $origins[0];
        $originYear = $origins[1];
        $destinationSemester = $destinations[0];
        $destinationYear = $destinations[1];

        $this->comment("Fetching reports for user {$user->name} from S{$originSemester}/{$originYear}...");

        $reportsToDuplicate = Report::where('creator_id', $user->id)
            ->where('academic_year', $originYear)
            ->where('semester', $originSemester)
            ->get();

        $reportsCount = $reportsToDuplicate->count();
        $this->info("Found {$reportsCount} reports to duplicate.");

        if ($reportsCount === 0) {
            $this->warn("No reports found matching the criteria. Nothing was duplicated.");
            return 0;
        }

        $duplicatedCount = 0;
        $bar = $this->output->createProgressBar($reportsCount);
        $bar->start();
        DB::transaction(
            function () use ($reportsToDuplicate, $destinationSemester, $destinationYear, &$duplicatedCount, $bar) {
                foreach ($reportsToDuplicate as $report) {
                    $newReport = $report->replicate();
                    $newReport->academic_year = $destinationYear;
                    $newReport->semester = $destinationSemester;
                    $newReport->approver_id = null;
                    $newReport->date = null;
                    $newReport->outstanding_students = null;
                    $newReport->need_improvement_students = null;
                    $newReport->save();
                    $duplicatedCount++;
                    $bar->advance();
                }
            }
        );

        $bar->finish();
        $this->newLine(2);

        if ($duplicatedCount > 0) {
            $this->info(
                "✅ Successfully duplicated {$duplicatedCount} reports to Semester {$destinationSemester}/{$destinationYear}!"
            );
        } else {
            $this->error("❌ An error occurred during the duplication process, or no reports were duplicated.");
            return 1;
        }

        return 0;
    }

}
