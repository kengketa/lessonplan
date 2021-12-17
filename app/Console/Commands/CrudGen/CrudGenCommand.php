<?php

namespace App\Console\Commands\CrudGen;

use App\Console\Commands\CrudGen\Handles\Handles;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Console\Commands\CrudGen\Concerns\CanManipulateFiles;
use Illuminate\Support\Facades\Artisan;

class CrudGenCommand extends Command
{
    use CanManipulateFiles;
    use CollectingData;
    use Handles;

    protected $signature = 'crud:gen';

    protected $description = 'Create a new Inertia CRUD skeleton';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $confirm = $this->getModelInformation();
        if (!$confirm) {
            $this->error("Aborted, please fill in the Model information again");
        }
        // Auto generate model
        //Artisan::call('make:model '.$modelClass.' -mfs');

        // Generate Custom migration
        $status['migration'] = $this->migrationHandle();

        //Generate custom factory
        $status['factory'] = $this->factoryHandle();

        //Generate custom seed
        $status['seeder'] = $this->seederHandle();

        //Generate Custom model
        $status['model'] = $this->modelHandle();

        // Generate Custom Controller
        $status['controller'] = $this->controllerHandle();

        // Generate Custom Transformer
        $status['transformer'] = $this->transformerHandle();

        // Generate Custom SaveAction
        $status['action'] = $this->saveActionHandle();

        // Generate Custom CreateOrUpdatePostRequest
        $status['request'] = $this->createOrUpdateRequestHandle();

        // Generate Custom Presnter
        $status['presenter'] = $this->PresenterHandle();

        //View CRUD

        //Index
        //$status['index_view'] = $this->indexViewHandle();

        //Create
        //$status['create_view'] = $this->createViewHandle();

        //Edit
        //$status['edit'] = $this->editViewHandle();

        //Show
        //$status['show'] = $this->showViewHandle();

        //Form
        //$status['form'] = $this->formHandle();

    }
}
