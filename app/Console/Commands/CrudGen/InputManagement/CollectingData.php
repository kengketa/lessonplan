<?php
namespace App\Console\Commands\CrudGen\InputManagement;

use App\Console\Commands\CrudGen\Parser\ControllerParser;
use App\Console\Commands\CrudGen\Parser\CreateOrUpdateRequestParser;
use App\Console\Commands\CrudGen\Parser\FactoryParser;
use App\Console\Commands\CrudGen\Parser\IndexViewParser;
use App\Console\Commands\CrudGen\Parser\MigrationParser;
use App\Console\Commands\CrudGen\Parser\ModelParser;
use App\Console\Commands\CrudGen\Parser\SeederParser;
use App\Console\Commands\CrudGen\Parser\TransformerParser;
use App\Console\Commands\CrudGen\Parser\SaveActionParser;
use Illuminate\Support\Str;
use Carbon\Carbon;

trait CollectingData {
    use FactoryParser;
    use ModelParser;
    use ControllerParser;
    use MigrationParser;
    use SeederParser;
    use TransformerParser;
    use SaveActionParser;
    use CreateOrUpdateRequestParser;
    use IndexViewParser;

    public $rootNamespace = 'App';
    public $rawInput = "";
    public $namespace = ""; // Dashboard
    public $lowerNamespace = ""; // dashboard
    public $ModelController = ""; // PostController
    public $Model = ""; // Post
    public $Models = ""; // Posts
    public $model = ""; // post
    public $models = ""; // posts
    public $modelFile = ""; // app/Models/Post.php
    public $controllerFile = ""; //app/Http/Controllers/Dashboard/PostController.php
    public $seederFile = ""; //database/seeders/PostsSeeder.php
    public $factoryFile="";
    public $fields = [];
    public $fillable = "";
    public $searchQuery = "";

    function getModelInformation()
    {
        $this->namespace = Str::ucfirst($this->ask('Name space?'));
        $this->rawInput = $this->ask('Model Name?');
        $rawFields = [];
        $newField = "";
        while ($newField != 'done') {
            $newField = $this->ask('field_name type(integer/float/string/text/boolean/json/date_time) nullable(yes/no) type done to finish');
            if ($newField != 'done') {
                $rawFields[] = $newField;
            }
        }
        foreach ($rawFields as $key => $field) {
            $arrField = explode(' ', $field);
            $fields[$key]['name'] = Str::lower($arrField[0]);
            $fields[$key]['type'] = isset($arrField[1]) ? Str::camel($arrField[1]) : "";
            $fields[$key]['nullable'] = isset($arrField[2]) ? Str::lower($arrField[2]) : "";
        }
        $this->fields = $fields;
        $this->setConstantValue();

        // Show infor before confirmation
        $this->info("Namespace: ".$this->namespace);
        $this->info("Model: ".$this->Model);
        $this->info("Controller: ".$this->ModelController);
        $this->info("Fields: ".json_encode($this->fields));

        if ($this->confirm('Generate the model CRUD now?')) {
            return true;
        }
        return false;
    }

    function setConstantValue()
    {
        $rawModel = (string)Str::of($this->rawInput)
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');
        $this->namespace = $this->namespace();
        $this->lowerNamespace = Str::lower($this->namespace);
        $this->model = (string)Str::of($rawModel)->afterLast('\\'); // Post
        $this->Model = $this->upperModel(); // Post
        $this->Models = $this->upperModels(); // Posts
        $this->model = $this->lowerModel(); // post
        $this->models = $this->lowerModels(); // posts
        $this->ModelController = $this->modelController(); // PostController
        $this->modelFile = $this->modelFile();
        $this->controllerFile = $this->setControllerPath($this->namespace);
        $this->seederFile = $this->setSeederPath();
        $this->factoryFile = $this->setFactoryPath();
        $this->fillable = $this->getFillableFields($this->fields);
        $this->searchQuery = $this->getSearchQuery($this->fields);

    }

}
