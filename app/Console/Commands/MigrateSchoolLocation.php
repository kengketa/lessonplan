<?php

namespace App\Console\Commands;

use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class MigrateSchoolLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'school-location:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'migrate school lat and lng';

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
        if (Schema::hasColumn('schools', 'lat')) {
            $this->error('column lat has already existed!');
            return;
        }
        Schema::table('schools', function ($table) {
            $table->unsignedDecimal('lat', 11, 8)->nullable()->after('subjects');
            $table->unsignedDecimal('lng', 11, 8)->nullable()->after('lat');
            $table->unsignedInteger('radius')->nullable()->after('lng');
        });
        $this->info('columns lat lng and radius has been added to schools.');

        $schools = School::all();
        foreach ($schools as $school) {
            if ($school->name == 'โรงเรียนเทศบาลตำบลงิม (คือเวียงจำ)') {
                $school->lat = 19.268215614863088;
                $school->lng = 100.36667601784593;
                $school->radius = 100;
                $school->save();
                $this->info($school->name.': added lat lng and radius.');
            }
            if ($school->name == 'โรงเรียนอนุบาล เทศบาลตำบลจุน') {
                $school->lat = 19.322821504714376;
                $school->lng = 100.14763174247288;
                $school->radius = 50;
                $school->save();
                $this->info($school->name.': added lat lng and radius.');
            }
            if ($school->name == 'Tessaban 5') {
                $school->lat = 19.17920454012667;
                $school->lng = 99.89263306186193;
                $school->radius = 50;
                $school->save();
                $this->info($school->name.': added lat lng and radius.');
            }
            if ($school->name == 'Tessaban 4') {
                $school->lat = 19.144656507192565;
                $school->lng = 99.90755073014793;
                $school->radius = 50;
                $school->save();
                $this->info($school->name.': added lat lng and radius.');
            }
            if ($school->name == 'Fakkwan wittayakhom') {
                $school->lat = 19.131614186054755;
                $school->lng = 99.8228504357287;
                $school->radius = 100;
                $school->save();
                $this->info($school->name.': added lat lng and radius.');
            }
        }

    }
}
