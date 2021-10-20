<?php
namespace App\Console\Commands\CrudGen\Handles;

use Illuminate\Support\Str;
use Carbon\Carbon;

trait Handles {
    use FactoryHandle;
    use MigrationHandle;
    use ModelHandle;
    use SeederHandle;
    use ControllerHandle;
    use TransformerHandle;
    use SaveActionHandle;
    use createOrUpdateRequestHandle;
    use PresenterHandle;
    use IndexViewHandle;
    use CreateViewHandle;
    use EditViewHandle;
    use ShowViewHandle;
    use FormHandle;

}
