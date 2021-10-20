<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait IndexViewHandle {
    use CollectingData;
    function indexViewHandle()
    {
        $this->info("Generating ".$this->Model.' Index Page');
        // Check Collision
        if ($this->checkForCollision([$this->getIndexFile()])) {
            return 'error';
        }
        //Generate custom Request
        $this->copyStubToApp('/vue/Index', $this->getIndexFile(), [
            'namespace.models' => $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models,
            'Model' => $this->Model,
            'Models' => $this->Models,
            'models' => $this->models,
            'model' => $this->model,
            'tdFields' => $this->getTdFields(),
            'columns' => $this->getColumns(),
        ]);
        return 'success';
    }
    private function getTdFields(){
        $modelNameSpace = $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models;
        $strTdFields = '';
        $tabsMinus1="\t\t\t\t\t\t\t\t\t\t\t\t\t";
        $tabs= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t";
        $tabsPlus1= "\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t";
        foreach($this->fields as $field){
            $strTdFields = $strTdFields.
                '<TableTd>'."\n".
                $tabs.'<inertia-link'."\n".
                    $tabsPlus1.':href='.'"route('."'".$modelNameSpace.'.show'."'".','.'item.id)'.'"'."\n".
                    $tabsPlus1.'class='.'"'.'hover:underline'.'"'."\n".
                $tabs.'>'."\n".
                    $tabsPlus1.'{{ '.'item'.'.'.$field['name'].' }}'."\n".
                $tabs.'</inertia-link>'."\n".
                $tabsMinus1.'</TableTd>'."\n".$tabsMinus1
            ;
        }
        return $strTdFields;
    }

    private function getColumns(){
        $strColumns = '';
        foreach($this->fields as $field){
            $strColumns = $strColumns.
                "'".
                $field['name'].
                "'".', '
                ;
        }
        return $strColumns;
    }
}
