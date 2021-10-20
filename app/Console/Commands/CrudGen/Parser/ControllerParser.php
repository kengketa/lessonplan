<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait ControllerParser {

    function setControllerPath(){

        if($this->namespace!=""){
            return  app_path("Http/Controllers/".$this->namespace."/".$this->ModelController.'.php');
        }else{
            return  app_path("Http/Controllers/".$this->ModelController.'.php');
        }
    }

}
