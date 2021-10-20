<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait ControllerHandle {

    function controllerHandle()
    {
        $this->info("Generating ".$this->ModelController);
        // Check Collision
        if ($this->checkForCollision([$this->controllerFile])) {
            return 'error';
        }
        //Generate custom controller
        $this->copyStubToApp('ControllerTemplate', $this->controllerFile, [
            'namespace' => 'App\\Http\\Controllers'.($this->namespace !== '' ? "\\{$this->namespace}" : ''),
            'lowerNamespace' => $this->lowerNamespace,
            'rootNamespace' => $this->rootNamespace,
            'modelNamespace' => $this->modelNamespaceCalcuator(),
            'Model' => $this->Model,
            'Models' => $this->Models,
            'controller' => $this->ModelController,
            'model' => $this->model,
            'models' => $this->models
        ]);
        $this->addRoutesToWeb();
        return 'success';
    }

    private function importRouteClass(){
        $importHeader = 'use App\\Http\\Controllers'.($this->namespace !== '' ? "\\{$this->namespace}" : '').'\\'.$this->ModelController.';'."\n";
        $web = base_path('routes/web.php');
        $lines =  file($web);
        $lastImportLine = 0;
        foreach ($lines as $key => $line){
            if(str_contains($line, 'use ')){
                $lastImportLine = $key;
            }
        }
        $lines[$lastImportLine] = $lines[$lastImportLine].$importHeader;
        file_put_contents($web, implode('', $lines));
    }

    private function addRoutesToWeb():void
    {
        $this->importRouteClass();
        $nameSpace = $this->namespace != '' ? strtolower($this->namespace).'.':'';
        $routeRows[0] = "\n\t".'//'.$this->models."\n\t";
        $routeRows[1] = 'Route::get("'.$this->models.'", ['.$this->ModelController.'::class,"index"])->name("'.$nameSpace.$this->models.'.'.'index");'."\n\t";
        $routeRows[2] = 'Route::get("'.$this->models.'/create", ['.$this->ModelController.'::class,"create"])->name("'.$nameSpace.$this->models.'.'.'create");'."\n\t";
        $routeRows[3] = 'Route::get("'.$this->models.'/{'.$this->model.'}", ['.$this->ModelController.'::class,"show"])->name("'.$nameSpace.$this->models.'.'.'show");'."\n\t";
        $routeRows[4] = 'Route::post("'.$this->models.'", ['.$this->ModelController.'::class,"store"])->name("'.$nameSpace.$this->models.'.'.'store");'."\n\t";
        $routeRows[5] = 'Route::get("'.$this->models.'/{'.$this->model.'}/edit", ['.$this->ModelController.'::class,"edit"])->name("'.$nameSpace.$this->models.'.'.'edit");'."\n\t";
        $routeRows[6] = 'Route::put("'.$this->models.'/{'.$this->model.'}", ['.$this->ModelController.'::class,"update"])->name("'.$nameSpace.$this->models.'.'.'update");'."\n\t";
        $routeRows[7] = 'Route::delete("'.$this->models.'/{'.$this->model.'}", ['.$this->ModelController.'::class,"destroy"])->name("'.$nameSpace.$this->models.'.'.'destroy");'."\n\t";
        $strRoutes = "";
        foreach($routeRows as $route){
            $strRoutes = $strRoutes.$route;
        }
        $web = base_path('routes/web.php');
        $lines =  file($web);
        $startRouteLine = 0;
        //find start auto gen line
        foreach ($lines as $key => $line){
            if(str_contains($line, 'auto_generate_route')){
                $startRouteLine = $key;
            }
        }
        $lastRouteLine = $startRouteLine;
        //find last added route in auto gen section
        foreach ($lines as $key => $line){
            if(str_contains($line, 'Route::') && $key>=$startRouteLine){
                $lastRouteLine = $key;
            }
        }
        $lines[$lastRouteLine] = $lines[$lastRouteLine].$strRoutes;
        file_put_contents($web, implode('', $lines));
    }

}
