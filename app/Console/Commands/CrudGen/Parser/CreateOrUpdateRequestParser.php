<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait CreateOrUpdateRequestParser {

    public function getRequestName()
    {
        return 'CreateOrUpdate'.$this->Model.'Request';
    }

    public function getRequestFile()
    {
        return app_path("Http/Requests/".$this->getRequestName().'.php');
    }

    public function getRules()
    {
        $strFields="";
        foreach ($this->fields as $key => $field) {
            $strFields = $strFields."'".$field['name']."'"."\t=>\t".'[';
                if ($field['nullable'] === 'no'){
                    $strFields = $strFields."'required', ";
                }else{
                    $strFields = $strFields."'nullable', ";
                }
                if($field['type'] === 'string' || $field['type'] === 'text'){
                    $strFields = $strFields."'string', ";
                }
                if($field['type'] === 'dateTime'){
                    $strFields = $strFields."'date', ";
                }
                if($field['type'] === 'boolean'){
                    $strFields = $strFields."'boolean', ";
                }
                if($field['type'] === 'json'){
                    $strFields = $strFields."'json', ";
                }
            $strFields = $strFields.'],'."\n\t\t\t";

        }
        return $strFields;
    }

}

