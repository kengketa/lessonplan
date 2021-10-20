<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait FormHandle {
    use CollectingData;
    function formHandle()
    {
        $this->info("Generating ".$this->Model.'Form');
        // Check Collision
        if ($this->checkForCollision([$this->getFormFile()])) {
            return 'error';
        }
        //Generate custom vue edit page
        $this->copyStubToApp('/vue/Form', $this->getFormFile(), [
            'namespace.models' => $this->namespace != '' ? strtolower($this->namespace).'.'.$this->models : $this->models,
            'Model' => $this->Model,
            'model' => $this->model,
            'formFields' => $this->getFormFields(),
            'formInput' => $this->getFormInput()
        ]);
        return 'success';
    }
    private function getFormFile()
    {
        return resource_path('js/Components/Forms/'.$this->Model.'Form.vue');
    }
    private function getFormInput(){
        $fields = $this->fields;
        $inputFields = '';
        foreach ($fields as $field){
           $row = '';
           $row = $row.'<TextInput'."\n";
           $row = $row.'v-model='.'"'.'form.'.$field['name'].'"'."\n";
           $row = $row.':errors='.'"'.'form.errors.'.$field['name'].'"'."\n";
           $row = $row.'label='.'"'.ucfirst($field['name']).'"'."\n";
           $row = $row.'placeholder='.'"'.$field['name'].'"'."\n";
           $row = $row.'/>'."\n";
            $inputFields = $inputFields.$row;
        }
        return $inputFields;
    }
    private function getFormFields(){
        $fields = $this->fields;
        $fieldData = '';
        foreach ($fields as $field){
            $row = '';
            $row = $row.$field['name'].': '.'this.'.$this->model.".".$field['name'].','."\n";
            $fieldData = $fieldData.$row;
        }
        return $fieldData;
    }
}

