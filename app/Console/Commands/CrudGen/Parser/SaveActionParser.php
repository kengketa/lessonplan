<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait SaveActionParser {

    public function getSaveActionName(){
        return 'Save'.$this->Model.'Action';
    }
    public function getSaveActionFile(){
        return app_path("Actions/".$this->getSaveActionName().'.php');
    }


}

