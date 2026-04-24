<?php

namespace Modules\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TableModelingCommand extends Command
{

    protected $signature = 'make:table {module_name} {[field]?} {--model}';
    protected $description = 'Create a folder and files in the app directory';


    protected $moduleName;
    protected $viewModuleName;
    protected $fields = [];
    protected $baseDirectory;
    protected $modelDirectory;
    protected $databaseDirectory;
    protected $finalModule;
    protected $moduleDir;
    protected $modulePath;
    protected $migrationFile;
    protected $modelFile;
    protected $tableName;



    public function handle()
    {
        $this->initializeArguments();
        $this->parseFields();
        $this->prepareDirectories();
        $this->generateFiles();
        $this->runMigration();
    }

    protected function initializeArguments()
    {
        $this->moduleName = $this->argument('module_name');
        $this->viewModuleName = $this->moduleName;
    }

    protected function parseFields()
    {
        if ($this->hasArgument('[field]') && $this->argument('[field]')) {
            $fieldRaw = str_replace(['[', ']'], '', $this->argument('[field]'));
            $this->fields = collect(explode(',', $fieldRaw))->map(fn($f) => explode(':', $f))->toArray();
        }
    }

    protected function prepareDirectories()
    {
        $this->baseDirectory = base_path("Modules/Management/");
        $parts = explode('/', $this->moduleName);
        $this->finalModule = end($parts);

        if (count($parts) > 1) {
            array_pop($parts);
            $this->moduleDir = implode('/', $parts);
            $fullPath = $this->baseDirectory . $this->moduleDir;

            if (!File::isDirectory($fullPath)) {
                File::makeDirectory($fullPath, 0777, true);
            }

            $this->baseDirectory = $fullPath . '/';
        }

        $this->tableName = Str::plural(Str::snake($this->finalModule));
        $this->migrationFile = "create_{$this->tableName}_table.php";
        $this->modelFile = "{$this->finalModule}Model.php";
        $this->modulePath = $this->moduleDir ? "{$this->moduleDir}/{$this->finalModule}" : $this->finalModule;

        $this->modelDirectory = $this->baseDirectory . 'Database/Models';
        $this->databaseDirectory = $this->baseDirectory . 'Database/Migrations';

        if (!File::isDirectory($this->modelDirectory)) {
            File::makeDirectory($this->modelDirectory, 0777, true);
        }

        if (!File::isDirectory($this->databaseDirectory)) {
            File::makeDirectory($this->databaseDirectory, 0777, true);
        }
    }

    protected function generateFiles()
    {
        File::put(
            "{$this->modelDirectory}/{$this->modelFile}",
            TableModel($this->modulePath, $this->finalModule)
        );

        File::put(
            "{$this->databaseDirectory}/{$this->migrationFile}",
            TableMigration($this->modulePath, $this->fields)
        );
    }

    protected function runMigration()
    {
         // Split the full module path
         $parts = explode('/', $this->modulePath);

         // Get the last part as the model/table base name
         $lastPart = array_pop($parts); // CourseCourseCategory
         $tableName = Str::plural(Str::snake($lastPart)); // course_course_categories
 
         // Remaining parts as the module directory
         $moduleDirectory = implode('/', $parts); // CourseManagement/CourseCategory
       
 
         // Construct the path for migration
         // $path = "/app/Modules/Management/{$moduleDirectory}/{$lastPart}/Database/create_{$tableName}_table.php";
        $path = "/Modules/Management/{$moduleDirectory}/Database/Migrations/create_{$tableName}_table.php";

        Artisan::call('migrate', ['--path' => $path]);
    }
}
