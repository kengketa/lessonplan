<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait ShowViewHandle {
    use CollectingData;
    function showViewHandle()
    {
        $this->info("Generating ".$this->Model.'show Page');
        // Check Collision
        if ($this->checkForCollision([$this->getShowFile()])) {
            return 'error';
        }
        //Generate custom vue edit page
        $this->copyStubToApp('/vue/Show', $this->getShowFile(), [
            'namespace.models' => $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models,
            'Model' => $this->Model,
            'Models' => $this->Models,
            'model' => $this->model,
            'rowData' => $this->getRowData()
        ]);
        return 'success';
    }
    private function getShowFile()
    {
        $folderCalculator = $this->namespace !== ''? $this->namespace.'/'.$this->Models.'/Show.vue' : $this->Models.'/Show.vue';
        return resource_path('js/Pages/'.$folderCalculator);
    }
    private function getRowData(){
        $fields = $this->fields;
        '<DataDisplayRow>';
        '</DataDisplayRow>';
        $rowData = '';
        foreach ($fields as $field){
            $row = '<DataDisplayRow>'."\n\t";
            $row = $row . '<template #label>'."\n\t\t";
            $row = $row . $field['name']."\n\t";
            $row = $row . '</template>'."\n";
            $row = $row . '{{'.$this->model.'.'.$field['name'].' }}'."\n";
            $row = $row . '</DataDisplayRow>'."\n";
            $rowData = $rowData.$row;
        }
        return $rowData;
    }
}

