<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait FactoryParser {

    function getNewFactoryFields(){
        $strFields="";
        foreach ($this->fields as $key => $field) {
            if($this->typeCalculation($field['type']) == 'text'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$this->faker->text(20),'."\n\t\t\t";
            }elseif($this->typeCalculation($field['type']) == 'integer'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$this->faker->numberBetween(1,100),'."\n\t\t\t";
            }elseif($this->typeCalculation($field['type']) == 'float'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$this->faker->randomFloat($nbMaxDecimals = 2, $min = 1, $max = 9999),'."\n\t\t\t";
            }elseif($this->typeCalculation($field['type']) == 'boolean'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$this->faker->boolean,'."\n\t\t\t";
            }elseif($this->typeCalculation($field['type']) == 'dateTime'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$this->faker->dateTime(),'."\n\t\t\t";
            }elseif($this->typeCalculation($field['type']) == 'json'){
                $strFields = $strFields."'".$field['name']."'"."\t=>\t".'[
                "testField1" => $this->faker->randomElement(["a","b","c","d"])
                ],'."\n\t\t\t";
            }

        }
        return $strFields;
    }

    function typeCalculation($type){
        if($type=='text' || $type=='string'){
            return 'text';
        }elseif($type == 'integer'){
            return 'integer';
        }elseif($type == 'float'){
            return 'float';
        }elseif($type=='boolean'){
            return 'boolean';
        }elseif($type=='dateTime'){
            return 'dateTime';
        }elseif($type=='json'){
            return 'json';
        }

    }

    function setFactoryPath(){

        return  database_path("factories/".$this->Model."Factory.php");

    }


}
