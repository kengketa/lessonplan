<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Console\Commands\CrudGen\InputManagement\CollectingData;
trait TransformerHandle {
    use CollectingData;
    function transformerHandle()
    {
        $this->info("Generating ".$this->getTransformerName());
        // Check Collision
        if ($this->checkForCollision([$this->getTransformerFile()])) {
            return 'error';
        }
        //Generate custom Transformer
        $this->copyStubToApp('TransformerTemplate', $this->getTransformerFile(), [
            'Model' => $this->Model,
            'model' => $this->model,
            'transformerFields' => $this->getTransformerFields()
        ]);
    }

}
