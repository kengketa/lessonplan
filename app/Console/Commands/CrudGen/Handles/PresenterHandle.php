<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait PresenterHandle {

    function presenterHandle()
    {
        $presenterFile = 'app/Presenters/'.$this->Model.'Presenter.php';
        $this->info("Generating ".$this->Model.'Presenter');
        // Check Collision
        if ($this->checkForCollision([$presenterFile])) {
            return 'error';
        }
        //Generate custom Presenter
        $this->copyStubToApp('PresenterTemplate', $presenterFile, [
            'Model' => $this->Model,
        ]);
        return 'success';
    }

}
