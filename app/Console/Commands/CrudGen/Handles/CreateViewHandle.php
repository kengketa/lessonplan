<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait CreateViewHandle {
    use CollectingData;
    function createViewHandle()
    {
        $this->info("Generating ".$this->Model.' Create Page');
        // Check Collision
        if ($this->checkForCollision([$this->getCreateFile()])) {
            return 'error';
        }
        //Generate custom Request
        $this->copyStubToApp('/vue/Create', $this->getCreateFile(), [
            'namespace.models' => $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models,
            'Model' => $this->Model,
            'Models' => $this->Models,
            'model' => $this->model,
        ]);
        return 'success';
    }
    private function getCreateFile()
    {
        $folderCalculator = $this->namespace !== ''? $this->namespace.'/'.$this->Models.'/Create.vue' : $this->Models.'/Create.vue';
        return resource_path('js/Pages/'.$folderCalculator);
    }
}
