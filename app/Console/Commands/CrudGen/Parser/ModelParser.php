<?php

namespace App\Console\Commands\CrudGen\Parser;

use Illuminate\Support\Str;

trait ModelParser
{

    function getFillableFields($fields)
    {

        $fillable = "";
        foreach ($fields as $field) {

            $fillable = $fillable."'".$field['name']."'".",";
        }
        return $fillable;
    }

    function getSearchQuery($fields)
    {

        $searchQuery = "";
        foreach ($fields as $key => $field) {
            if ($key == 0) {
                $searchQuery = $searchQuery."".'$qr->where("'.$field['name'].'", "like", "%$filters[search]%")';
            } else {
                $searchQuery = $searchQuery."".'->orWhere("'.$field['name'].'", "like", "%$filters[search]%")';
            }
        }
        return $searchQuery;
    }

    function namespace()
    {
        return ucfirst((string)Str::of($this->namespace));
    }

    function upperModel()
    {
        return ucfirst((string)Str::of($this->model));
    }

    function upperModels()
    {
        return ucfirst((string)Str::of($this->model)->pluralStudly());
    }

    function lowerModel()
    {
        return (string)Str::lower($this->model);
    }

    function lowerModels()
    {
        return (string)Str::lower(Str::of($this->model)->pluralStudly());
    }

    function modelController()
    {
        return $this->upperModel()."Controller";
    }

    function modelFile()
    {
        return app_path('Models/'.$this->upperModel().'.php');
    }

    function modelNamespaceCalcuator()
    {
        if ($this->namespace == "") {
            return $this->upperModels();
        } else {
            return $this->namespace."/".$this->upperModels();
        }
    }


}
