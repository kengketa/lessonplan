<?php
namespace App\Console\Commands\CrudGen\Handles;

use App\Console\Commands\CrudGen\Parser\MigrationParser;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait MigrationHandle {

    use MigrationParser;
    function migrationHandle()
    {
        $migrationFile = $this->getNewMigrationFileName($this->models);
        $migrationFields = $this->getNewMigrationFields($this->fields);
        $this->info("Generating migration");
        $this->copyStubToApp('MigrationTemplate', $migrationFile, [
            'Models' => $this->Models,
            'models' => $this->models,
            'migrationFields' => $migrationFields
        ]);
        return 'success';
    }

}
