<?php
namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait MigrationParser {

    function getNewMigrationFileName($models){

        $migrationPath = database_path('migrations');
        $now = Carbon::now();
        $date = $now->format('Y_m_d')."_".strtotime($now);
        $name = $date."_create_".$models."_table.php";
        $migrationFile = $migrationPath.'/'.$name;
        return $migrationFile;


    }

    function getNewMigrationFields($fields){

        $strFields = "";
        foreach ($fields as $key => $field) {
            if($field['nullable'] == 'yes'){
                $strFields = $strFields."".'$table->'.$field['type'].'("' .$field['name'].'")->nullable();'."\n\t\t\t";
            }else{
                $strFields = $strFields."".'$table->'.$field['type'].'("' .$field['name'].'");'."\n\t\t\t";
            }

        }
        return $strFields;
    }


}
