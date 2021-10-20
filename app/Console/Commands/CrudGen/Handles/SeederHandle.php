<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait SeederHandle {
    function seederHandle()
    {
        $this->info("Generating Seeder ".$this->Model);
        // Check Collision
        if ($this->checkForCollision([$this->seederFile])) {
            return 'error';
        }
        $this->copyStubToApp('SeederTemplate', $this->seederFile, [
            'Model' => $this->Model
        ]);
        $this->addSeederToDatabaseSeeder();
        return 'success';
    }

    private function addSeederToDatabaseSeeder():void
    {
        $seederCall = '$this->call('.$this->Model.'Seeder::class);';
        $databaseSeederFile = database_path('seeders/DatabaseSeeder.php');
        $lines =  file($databaseSeederFile);
        $lastCallLine = 17;
        foreach ($lines as $key => $line){
            if(str_contains($line, '$this->call')){
                $lastCallLine = $key;
            }
        }
        $lines[$lastCallLine] = $lines[$lastCallLine]."\t\t\t".$seederCall."\n";
        file_put_contents($databaseSeederFile, implode('', $lines));
        return;
    }

}
