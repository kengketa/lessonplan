<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait TransformerParser {

    public function getTransformerName(){
        return $this->Model."Transformer";
    }
    public function getTransformerFile(){
        return app_path("Transformers/".$this->getTransformerName().'.php');
    }
    public function getTransformerFields(){
        $strFields = "";
        $strFields = $strFields."'".'id'."'"."\t=>\t".'$'.$this->model.'->'.'id'.','."\n\t\t\t";
        foreach ($this->fields as $key => $field) {
            $strFields = $strFields."'".$field['name']."'"."\t=>\t".'$'.$this->model.'->'.$field['name'].','."\n\t\t\t";
        }
        $strFields = $strFields."'".'created_at'."'"."\t=>\t".'$'.$this->model.'->'.'present()->createdAt,'."\n\t\t\t";
        $strFields = $strFields."'".'updated_at'."'"."\t=>\t".'$'.$this->model.'->'.'present()->updatedAt,';
        return $strFields;
    }

}

