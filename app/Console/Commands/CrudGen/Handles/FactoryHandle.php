<?php
namespace App\Console\Commands\CrudGen\Handles;

use App\Console\Commands\CrudGen\Parser\FactoryParser;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait FactoryHandle {
    use FactoryParser;
    function factoryHandle()
    {
        $factoryFile = $this->setFactoryPath();
        $factoryFields = $this->getNewFactoryFields();
        $this->info("Generating Factory");
        $this->copyStubToApp('FactoryTemplate', $factoryFile, [
            'Model' => $this->Model,
            'factoryFields' => $factoryFields
        ]);
        return 'success';
    }

}
