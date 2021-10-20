<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait SeederParser {

    function setSeederPath(){

        return  database_path("seeders/".$this->Model."Seeder.php");

    }

}
