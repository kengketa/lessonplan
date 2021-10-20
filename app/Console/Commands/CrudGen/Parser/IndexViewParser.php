<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait IndexViewParser {

    public function getIndexFile()
    {
        $folderCalculator = $this->namespace !== ''? $this->namespace.'/'.$this->Models.'/Index.vue' : $this->Models.'/Index.vue';
        return resource_path('js/Pages/'.$folderCalculator);
    }

}

