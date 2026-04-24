<?php

namespace App\Console\Commands;

use Modules\Commands\ModelingDirectory as BaseModelingDirectory;

class MakeModule extends BaseModelingDirectory
{
    protected $signature = 'make:module {module_name} {[field]?} {--vue}';
    protected $description = 'Create a folder and files in the app directory';
}

