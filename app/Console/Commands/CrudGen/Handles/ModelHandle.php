<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait ModelHandle {

    function modelHandle()
    {
        $this->info("Generating Model ".$this->Model);
        // Check Collision
        if ($this->checkForCollision([$this->modelFile])) {
            return 'error';
        }
        $this->copyStubToApp('ModelTemplate', $this->modelFile, [
            'Model' => $this->Model,
            'Models' => $this->Models,
            'fillable' => $this->fillable,
            'searchQuery' => $this->searchQuery,
        ]);
        return 'success';
    }

}
