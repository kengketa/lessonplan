<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait EditViewHandle {
    use CollectingData;
    function editViewHandle()
    {
        $this->info("Generating ".$this->Model.'Edit Page');
        // Check Collision
        if ($this->checkForCollision([$this->getEditFile()])) {
            return 'error';
        }
        //Generate custom vue edit page
        $this->copyStubToApp('/vue/Edit', $this->getEditFile(), [
            'namespace.models' => $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models,
            'Model' => $this->Model,
            'Models' => $this->Models,
            'model' => $this->model,
        ]);
        return 'success';
    }
    private function getEditFile()
    {
        $folderCalculator = $this->namespace !== ''? $this->namespace.'/'.$this->Models.'/Edit.vue' : $this->Models.'/Edit.vue';
        return resource_path('js/Pages/'.$folderCalculator);
    }
}
