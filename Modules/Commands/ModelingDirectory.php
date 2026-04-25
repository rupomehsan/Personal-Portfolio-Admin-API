<?php

namespace Modules\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModelingDirectory extends Command
{
    protected $signature = 'make:module {module_name} {[field]?} {--vue}';
    protected $description = 'Create a folder and files in the app directory';

    protected $moduleName;
    protected $ViewModuleName;
    protected $fields = [];
    protected $fileFields = [];
    protected $jsonFields = [];
    protected $hasFileUploads = false;
    protected $hasJsonUploads = false;
    protected $fieldsWithBraces = [];
    protected $baseDirectory;
    protected $withVue;

    public function handle()
    {
        try {
            $this->initializeProperties();
            $this->parseFields();
            $this->createBaseDirectories();
            $this->createSubDirectories();
            $this->generateFiles();
            $this->runMigration();
            $this->runSeeder();
            $this->appendRouteToApiRoutes();

            if ($this->withVue) {
                $this->generateVueFiles();
            }
            
            $this->info("Module {$this->moduleName} created successfully!");
        } catch (\Exception $e) {
            $this->error("Error creating module: " . $e->getMessage());
            \Log::error("Module creation error: " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString());
            return 1;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | API Management Module
    |--------------------------------------------------------------------------
    */

    protected function initializeProperties()
    {
        $this->moduleName = $this->argument('module_name');
        $this->ViewModuleName = $this->moduleName;
        $this->withVue = $this->option('vue');
        $this->baseDirectory = base_path("Modules/Management/");
    }

    protected function parseFields()
    {
        $arg = $this->argument('[field]');
        if (!$arg) return;

        $arg = str_replace(['[', ']'], '', $arg);
        foreach (explode(',', $arg) as $item) {
            $this->fields[] = explode(':', $item);
        }

        // Identify file fields (file / image / images types all trigger upload handling)
        $this->fileFields = [];
        $this->hasFileUploads = false;
        foreach ($this->fields as $key => $field) {
            $baseType = isset($field[1]) ? strtolower(explode('-', $field[1])[0]) : '';
            if (in_array($baseType, ['file', 'image', 'images'])) {
                $this->fileFields[] = $field[0];
                $this->hasFileUploads = true;
            }
        }
        // Identify Json fields
        $this->jsonFields = [];
        $this->hasJsonUploads = false;
        foreach ($this->fields as $key => $field) {
            if (isset($field[1]) && $field[1] === 'json') {
                $this->jsonFields[] = $field[0];
                $this->hasJsonUploads = true;
            }
        }

        // Identify fields with curly braces, e.g., bigint{test}
        $this->fieldsWithBraces = [];
        foreach ($this->fields as $key => $field) {
            if (isset($field[1]) && preg_match('/\{(.*)\}/', $field[1], $matches)) {
                $this->fieldsWithBraces[] = [
                    'field' => $field[0],
                    'brace_content' => $matches[1]
                ];
            }
        }
    }

    protected function createBaseDirectories()
    {
        $formatDir = explode('/', $this->moduleName);
        if (count($formatDir) > 1) {
            $this->moduleName = array_pop($formatDir);
            $subPath = implode('/', $formatDir);
            $this->baseDirectory .= $subPath . '/';
            File::ensureDirectoryExists($this->baseDirectory);
        }

        File::ensureDirectoryExists($this->baseDirectory . $this->moduleName);
    }

    protected function createSubDirectories()
    {
        $subDirs = ['Actions', 'Validations', 'Controller', 'Others', 'Routes'];
        foreach ($subDirs as $dir) {
            File::ensureDirectoryExists($this->baseDirectory . $this->moduleName . "/{$dir}");
        }
        
        // Create Database subfolders for organized structure
        $databaseSubDirs = ['Migrations', 'Models', 'Seeders'];
        foreach ($databaseSubDirs as $dir) {
            File::ensureDirectoryExists($this->baseDirectory . $this->moduleName . "/Database/{$dir}");
        }
    }

    protected function generateFiles()
    {
        $module_path = $this->getModulePath();
        $fields = $this->fields;


        $files = [
            'Actions/GetAllData.php' => GetAllData($module_path, $fields, $this->fieldsWithBraces),
            'Actions/StoreData.php' => StoreData($module_path, $this->fileFields, $this->hasFileUploads),
            'Actions/UpdateData.php' => UpdateData($module_path, $this->fileFields, $this->hasFileUploads),
            'Actions/GetSingleData.php' => GetSingleData($module_path, $this->fieldsWithBraces),
            'Actions/UpdateStatus.php' => UpdateStatus($module_path),
            'Actions/SoftDelete.php' => SoftDelete($module_path),
            'Actions/DestroyData.php' => DestroyData($module_path),
            'Actions/RestoreData.php' => RestoreData($module_path),
            'Actions/ImportData.php' => ImportData($module_path, $fields),
            'Actions/BulkActions.php' => BulkActions($module_path),
            'Validations/DataStoreValidation.php' => DataStoreValidation($module_path, $fields),
            'Validations/BulkActionsValidation.php' => BulkActionsValidation($module_path, $fields),
            'Controller/Controller.php' => Controller($module_path),
            'Database/Models/Model.php' => Model($module_path, $this->moduleName, $this->jsonFields, $this->hasJsonUploads, $this->fieldsWithBraces),
            "Database/Migrations/create_" . Str::plural(Str::snake($this->moduleName)) . "_table.php" => Migration($module_path, $fields),
            'Database/Seeders/Seeder.php' => Seeder($module_path, $this->moduleName, $fields),
            'Routes/Route.php' => RouteContent($module_path, $this->moduleName),
            'Others/Api.http' => ApiDocumentation($this->moduleName),
            'Others/Doc.txt' => Documentation(),
            'Others/ImportJob.php' => ImportJob($module_path),
            'Others/COMMAND_GUIDE.md' => CommandGuide(),
        ];

        foreach ($files as $relativePath => $content) {
            File::put($this->baseDirectory . $this->moduleName . '/' . $relativePath, $content);
        }
    }

    protected function runMigration()
    {
        $table = Str::plural(Str::snake($this->moduleName));
        $migrationPath = "/Modules/Management/{$this->ViewModuleName}/Database/Migrations/create_{$table}_table.php";
        Artisan::call('migrate', ['--path' => $migrationPath]);
    }

    protected function runSeeder()
    {
        $path = str_replace('/', '\\', $this->ViewModuleName);
        $seederClass = "\\Modules\\Management\\{$path}\\Database\\Seeders\\Seeder";
        Artisan::call('db:seed', ['--class' => $seederClass]);
    }

    protected function appendRouteToApiRoutes()
    {
        $filePath = base_path("Modules/Routes/Backend/ApiRoutes.php");
        $routeInclude = "include_once base_path(\"Modules/Management/{$this->ViewModuleName}/Routes/Route.php\");\n";

        if (!str_contains(file_get_contents($filePath), $routeInclude)) {
            file_put_contents($filePath, $routeInclude, FILE_APPEND);
        }
    }

    protected function getModulePath()
    {
        $parts = explode('/', $this->ViewModuleName);
        if (count($parts) > 1) {
            $module = array_pop($parts);
            return implode('/', $parts) . '/' . $module;
        }
        return $this->moduleName;
    }

    /*
    |--------------------------------------------------------------------------
    | Vue js Management Module
    | Vue js Management Module
    |--------------------------------------------------------------------------
    */

    protected function generateVueFiles()
    {
  
        $fields = $this->fields;
        $vue_format_dir = explode('/', $this->ViewModuleName);
        $ViewModuleName = end($vue_format_dir);
        $vue_module_path_dir = $this->ViewModuleName;

        // Create the Vue directory structure for the global management

        // Create the Vue directory structure for the role
        $mainDirectory = resource_path("js/backend/Views/Management/");
        $mainDirectory = $this->createRoleBaseVueDirectories($mainDirectory, $vue_format_dir);

        //Global Vue Directory
        $this->copyVueSourceFiles($mainDirectory, $ViewModuleName);
        $this->generateVueSetupFiles($mainDirectory, $ViewModuleName, $vue_module_path_dir, $fields);
        $this->generateVueStoreFiles($mainDirectory, $ViewModuleName, $vue_module_path_dir);
        $this->appendToVueRoutesFile($ViewModuleName, $vue_module_path_dir);
        $this->appendToVueSidebar($this->ViewModuleName);

    }

    /*
    |--------------------------------------------------------------------------
    | CreateVueDirectories
    |--------------------------------------------------------------------------
    */
    protected function createGlobalVueDirectories($vueDirectory, $vue_format_dir)
    {
        if (count($vue_format_dir) > 1) {
            array_pop($vue_format_dir);
            $vue_module_dir = implode('/', $vue_format_dir);

            if (!File::isDirectory($vueDirectory . $vue_module_dir)) {
                File::makeDirectory($vueDirectory . $vue_module_dir, 0777, true);
            }

            $vueDirectory .= $vue_module_dir . '/';
        }

        return $vueDirectory;
    }
    protected function createRoleBaseVueDirectories($vueDirectory, $vue_format_dir)
    {
        if (count($vue_format_dir) > 1) {
            array_pop($vue_format_dir);
            $vue_module_dir = implode('/', $vue_format_dir);

            if (!File::isDirectory($vueDirectory . $vue_module_dir)) {
                File::makeDirectory($vueDirectory . $vue_module_dir, 0777, true);
            }

            $vueDirectory .= $vue_module_dir . '/';
        }

        return $vueDirectory;
    }

    /*
    |--------------------------------------------------------------------------
    | CopyVueSourceFiles
    |--------------------------------------------------------------------------
    */

    protected function copyVueSourceFiles($vueDirectory, $ViewModuleName)
    {
        $targetDirectory = $vueDirectory . $ViewModuleName . '/pages';
        $sourceDirectory = base_path('Modules/Helpers/CommandFiles/FrontendModule/pages');

        File::ensureDirectoryExists($targetDirectory);
        if (File::isDirectory(directory: $sourceDirectory)) {
            File::copyDirectory($sourceDirectory, $targetDirectory);
        } else {
            echo "Source directory does not exist.";
        }
    }
  

    protected function generateVueSetupFiles($vueDirectory, $ViewModuleName, $vue_module_path_dir, $fields)
    {
        $SetupDirectory = "{$vueDirectory}{$ViewModuleName}/setup";
        File::ensureDirectoryExists($SetupDirectory);

        File::put("{$SetupDirectory}/form_fields.js", FormField($fields));
        File::put("{$SetupDirectory}/index.ts", SetupIndex($vue_module_path_dir, $fields));
        File::put("{$SetupDirectory}/routes.js", SetupRoutes());
    }

    protected function generateVueStoreFiles($vueDirectory, $ViewModuleName, $vue_module_path_dir)
    {
        $StoreDirectory = "{$vueDirectory}{$ViewModuleName}/store";
        File::ensureDirectoryExists($StoreDirectory);

        File::put("{$StoreDirectory}/index.ts", StoreIndex($vue_module_path_dir));
    }


     protected function appendToVueRoutesFile( $ViewModuleName, $vue_module_path_dir)
    {
        $filePath = base_path("resources/js/backend/Views/Routes/routes.js");
        $routeImport = "import {$ViewModuleName}Routes from '../Management/{$vue_module_path_dir}/setup/routes.js';\n";
        $newRouteChild = "        {$ViewModuleName}Routes,\n";

        $fileContent = file_get_contents($filePath);

        if (strpos($fileContent, $routeImport) === false) {
            $importPosition = strpos($fileContent, "//routes");
            if ($importPosition !== false) {
                $insertPosition = $importPosition + strlen("//routes") + 1;
                $fileContent = substr_replace($fileContent, $routeImport, $insertPosition, 0);
            }
        }

        if (strpos($fileContent, $newRouteChild) === false) {
            $managementRoutesPosition = strpos($fileContent, "//management routes");
            if ($managementRoutesPosition !== false) {
                $insertPosition = $managementRoutesPosition + strlen("//management routes") + 1;
                $fileContent = substr_replace($fileContent, $newRouteChild, $insertPosition, 0);
            }
        }

        file_put_contents($filePath, $fileContent);
    }

 
  

    protected function appendToVueSidebar($ViewModuleName)
    {
        $filePath = base_path("resources/js/backend/Views/Layouts/Partials/Sidebar/Index.vue");
        $vue_format_dir = explode('/', $ViewModuleName);
        $fileContent = file_get_contents($filePath);

        if (count($vue_format_dir) > 1) {
            $parent = ucwords($vue_format_dir[0]);
            $child = ucwords(end($vue_format_dir));
            $menuRoute = "All{$child}";

            // Check if route already exists in the file to prevent duplicates
            if ($this->routeExistsInSidebar($fileContent, $menuRoute)) {
                return;
            }

            $menuItem = <<<MENU
          {
            route_name: `{$menuRoute}`,
            title: `{$child}`,
            icon: `zmdi zmdi-dot-circle-alt`,
          },
MENU;

            $pattern = "/<side-bar-drop-down-menus[^>]*:menu_title=\"`{$parent}`\"[^>]*:menus=\"\\[\s*(.*?)\s*\\]\"/s";

            if (preg_match($pattern, $fileContent, $matches, PREG_OFFSET_CAPTURE)) {
                $menusBlock = $matches[1][0];
                $menusStart = $matches[1][1];
                $insertPos = $menusStart + strlen($menusBlock);
                
                // Add comma safely if needed
                $prefix = '';
                if (trim($menusBlock) !== '' && !str_ends_with(rtrim($menusBlock), ',')) {
                    $prefix = ",";
                }
                
                $fileContent = substr_replace($fileContent, $prefix . "\n" . $menuItem, $insertPos, 0);
                file_put_contents($filePath, $fileContent);
            } else {
                $sidebarMenuContent = <<<HTML
<side-bar-drop-down-menus
        :icon="`fa fa-plus`"
        :menu_title="`{$parent}`"
        :menus="[
{$menuItem}
        ]"
/>\n
HTML;
                $managementEndPosition = strpos($fileContent, "<!-- Management end -->");
                if ($managementEndPosition !== false) {
                    $fileContent = substr($fileContent, 0, $managementEndPosition) . $sidebarMenuContent . substr($fileContent, $managementEndPosition);
                    file_put_contents($filePath, $fileContent);
                }
            }
        } else {
            $title = ucwords(str_replace('-', ' ', $ViewModuleName));
            $menuRoute = "All{$ViewModuleName}";

            // Check if route already exists in the file to prevent duplicates
            if ($this->routeExistsInSidebar($fileContent, $menuRoute)) {
                return;
            }

            $sidebarMenuContent = <<<HTML
<side-bar-single-menu :icon="`fa fa-plus`" :menu_title="`{$title}`" :route_name="`{$menuRoute}`" />\n
HTML;

            $managementEndPosition = strpos($fileContent, "<!-- Management end -->");
            if ($managementEndPosition !== false) {
                $fileContent = substr($fileContent, 0, $managementEndPosition) . $sidebarMenuContent . substr($fileContent, $managementEndPosition);
                file_put_contents($filePath, $fileContent);
            }
        }
    }

    /**
     * Check if a route already exists in the sidebar to prevent duplicates
     */
    protected function routeExistsInSidebar($fileContent, $routeName)
    {
        // Look for route_name with backticks: `AllModuleName`
        $pattern = '/route_name:\s*`' . preg_quote($routeName, '/') . '`/';
        // Also check in :route_name attribute: :route_name="`AllModuleName`"
        $attributePattern = '/:route_name="`' . preg_quote($routeName, '/') . '`"/';

        return preg_match($pattern, $fileContent) || preg_match($attributePattern, $fileContent);
    }
}
