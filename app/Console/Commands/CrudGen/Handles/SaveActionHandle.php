<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait SaveActionHandle {
    use CollectingData;
    function saveActionHandle()
    {
        $this->info("Generating ".$this->getSaveActionName());
        // Check Collision
        if ($this->checkForCollision([$this->getSaveActionFile()])) {
            return 'error';
        }
        //Generate custom Save Action
        $this->copyStubToApp('SaveActionTemplate', $this->getSaveActionFile(), [
            'Model' => $this->Model,
            'model' => $this->model,
            'actionName' => $this->getSaveActionName()
        ]);

    }

}
