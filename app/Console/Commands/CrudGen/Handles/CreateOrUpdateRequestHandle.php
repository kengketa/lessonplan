<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait CreateOrUpdateRequestHandle {
    use CollectingData;
    function createOrUpdateRequestHandle()
    {
        $this->info("Generating ".$this->getRequestName());
        // Check Collision
        if ($this->checkForCollision([$this->getRequestFile()])) {
            return 'error';
        }
        //Generate custom Request
        $this->copyStubToApp('CreateOrUpdateRequestTemplate', $this->getRequestFile(), [
            'Model' => $this->Model,
            'model' => $this->model,
            'requestName' => $this->getRequestName(),
            'rules' => $this->getRules()
        ]);

    }

}
