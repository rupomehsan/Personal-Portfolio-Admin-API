<?php

use Illuminate\Support\Str;

if (!function_exists('Migration')) {
    function Migration($moduleName, $fields)
    {
        $table_name = '';
        $formated_module = explode('/', $moduleName);

        if (count($formated_module) > 1) {
            $moduleName = implode('/', $formated_module);
            $moduleName = Str::replace("/", "/", $moduleName);
            $table_name = Str::plural((Str::snake($formated_module[count($formated_module) - 1])));
        } else {
            $table_name = Str::plural((Str::snake($moduleName)));
            $moduleName = Str::replace("/", "/", $moduleName);
            // dd($moduleName);
        }




        $content = <<<"EOD"
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             php artisan migrate --path='/app/Modules/Management/{$moduleName}/Database/create_{$table_name}_table.php'
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('{$table_name}', function (Blueprint \$table) {
                    \$table->id();

        EOD;
        if (count($fields)) {
            foreach ($fields as $fieldName) {
                $stringLimit = 100;

                if (count($fieldName) == 1) {
                    $content .= "            \$table->string('{$fieldName[0]}', {$stringLimit})->nullable();\n";
                }
                if (count($fieldName) > 1) {
                    $type = $fieldName[1];
                    //enum value set
                    $enumvalue = [];

                    $position = strpos($type, '-');

                    if ($position == 4) {
                        $enumType = explode('-', $type);
                        $type = $enumType[0];
                        $enumvalue = explode('.', $enumType[1]);
                    }

                    if ($position == 6) {
                        $limit = explode('-', $type);
                        $limit = explode('.', $limit[1]);
                        $stringLimit = $limit[0];
                    }

                    // Handle image-N size pattern (e.g. image-150 → string(150))
                    if ($position === 5) {
                        $parts = explode('-', $type);
                        if ($parts[0] === 'image' && isset($parts[1]) && is_numeric($parts[1])) {
                            $stringLimit = (int) $parts[1];
                            $type = 'image';
                        }
                    }

                    //enum value set end

                    // Handle tinyint and boolean with custom options (e.g., tinyint-yes.no, boolean-active.inactive)
                    if (strpos($type, 'tinyint-') === 0 || strpos($type, 'boolean-') === 0) {
                        $enumType = explode('-', $type);
                        $type = 'enum';
                        $enumvalue = explode('.', $enumType[1]);
                    }

                    if (in_array($type, ['string', 'stringfile', 'file'])) {
                        $type = 'string';
                    } elseif (in_array($type, ['image'])) {
                        $type = 'string';   // stored as path string
                    } elseif (in_array($type, ['images'])) {
                        $type = 'text';     // stored as JSON array of paths
                    } elseif (in_array($type, ['text'])) {
                        $type = 'text';
                    } elseif (in_array($type, ['longtext'])) {
                        $type = 'longtext';
                    } elseif (in_array($type, ['number', 'integer', 'intiger', 'int'])) {
                        $type = 'integer';
                    } elseif (in_array($type, ['bigint', 'biginteger'])) {
                        $type = 'bigInteger';
                    } elseif (in_array($type, ['boolean', 'tinyint'])) {
                        $type = 'tinyInteger';
                    } elseif ($type == 'date') {
                        $type = 'date';
                    } elseif (in_array($type, ['datetime', 'datetime-local'])) {
                        $type = 'datetime';
                    } elseif ($type == 'time') {
                        $type = 'time';
                    } elseif ($type == 'year') {
                        $type = 'year';
                    } elseif ($type == 'month') {
                        $type = 'month';
                    } elseif ($type == 'unsigned_int') {
                        $type = 'unsignedInteger';
                    } elseif ($type == 'unsignedInteger') {
                        $type = 'unsignedInteger';
                    } elseif ($type == 'timestamp') {
                        $type = 'timestamp';
                    } elseif ($type == 'json') {
                        $type = 'json';
                    } elseif ($type == 'enum') {
                        $type = 'enum';
                    } elseif ($type == 'float') {
                        $type = 'float';
                    } elseif ($type == 'double') {
                        $type = 'double';
                    } elseif ($type == 'decimal') {
                        $type = 'decimal';
                    } elseif ($type == 'binary') {
                        $type = 'binary';
                    } elseif ($type == 'uuid') {
                        $type = 'uuid';
                    } elseif ($type == 'password') {
                        $type = 'string';
                    } else {
                        $type = 'string';
                    }

                    if ($type == 'enum') {
                        $enumString = implode("','", $enumvalue);
                        $content .= "            \$table->{$type}('{$fieldName[0]}', ['{$enumString}'])->nullable();\n";
                    } else if ($type == 'string') {
                        $content .= "            \$table->string('{$fieldName[0]}', {$stringLimit})->nullable();\n";
                    } else if ($type == 'tinyInteger') {
                        $content .= "            \$table->tinyInteger('{$fieldName[0]}')->default(0);\n";
                    } else {
                        $content .= "            \$table->{$type}('{$fieldName[0]}')->nullable();\n";
                    }
                }
            }
        }
        $content .= <<<EOD

                    \$table->bigInteger('creator')->unsigned()->nullable();
                    \$table->string('slug', 50)->nullable();
                    \$table->enum('status', ['active', 'inactive'])->default('active');
                    \$table->timestamps();
                    \$table->softDeletes();
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('{$table_name}');
            }
        };
        EOD;

        return $content;
    }
}

if (!function_exists('TableMigration')) {
    function TableMigration($fullModulePath, $fields)
    {
        // Split the full module path
        $parts = explode('/', $fullModulePath);

        // Get the last part as the model/table base name
        $lastPart = array_pop($parts); // CourseCategory
        $tableName = Str::plural(Str::snake($lastPart)); // course_categories

        // Remaining parts as the module directory
        $moduleDirectory = implode('/', $parts); // CourseManagement
        
        $content = <<<"EOD"
        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             php artisan migrate --path='/Modules/Management/{$moduleDirectory}/Database/Migrations/create_{$tableName}_table.php' 
             * Run the migrations.
             */
            public function up(): void
            {
                Schema::create('{$tableName}', function (Blueprint \$table) {
                    \$table->id();

        EOD;
        if (count($fields)) {
            foreach ($fields as $fieldName) {
                $stringLimit = 100;

                if (count($fieldName) == 1) {
                    $content .= "            \$table->string('{$fieldName[0]}', {$stringLimit})->nullable();\n";
                }
                if (count($fieldName) > 1) {
                    $type = $fieldName[1];
                    //enum value set
                    $enumvalue = [];

                    $position = strpos($type, '-');

                    if ($position == 4) {
                        $enumType = explode('-', $type);
                        $type = $enumType[0];
                        $enumvalue = explode('.', $enumType[1]);
                    }

                    if ($position == 6) {
                        $limit = explode('-', $type);
                        $limit = explode('.', $limit[1]);
                        $stringLimit = $limit[0];
                    }

                    // Handle image-N size pattern (e.g. image-150 → string(150))
                    if ($position === 5) {
                        $parts = explode('-', $type);
                        if ($parts[0] === 'image' && isset($parts[1]) && is_numeric($parts[1])) {
                            $stringLimit = (int) $parts[1];
                            $type = 'image';
                        }
                    }

                    //enum value set end

                    // Handle tinyint and boolean with custom options (e.g., tinyint-yes.no, boolean-active.inactive)
                    if (strpos($type, 'tinyint-') === 0 || strpos($type, 'boolean-') === 0) {
                        $enumType = explode('-', $type);
                        $type = 'enum';
                        $enumvalue = explode('.', $enumType[1]);
                    }

                    if (in_array($type, ['string', 'stringfile', 'file'])) {
                        $type = 'string';
                    } elseif (in_array($type, ['image'])) {
                        $type = 'string';   // stored as path string
                    } elseif (in_array($type, ['images'])) {
                        $type = 'text';     // stored as JSON array of paths
                    } elseif (in_array($type, ['text'])) {
                        $type = 'text';
                    } elseif (in_array($type, ['longtext'])) {
                        $type = 'longtext';
                    } elseif (in_array($type, ['number', 'integer', 'intiger', 'int'])) {
                        $type = 'integer';
                    } elseif (in_array($type, ['bigint', 'biginteger'])) {
                        $type = 'bigInteger';
                    } elseif (in_array($type, ['boolean', 'tinyint'])) {
                        $type = 'tinyInteger';
                    } elseif ($type == 'date') {
                        $type = 'date';
                    } elseif (in_array($type, ['datetime', 'datetime-local'])) {
                        $type = 'datetime';
                    } elseif ($type == 'time') {
                        $type = 'time';
                    } elseif ($type == 'year') {
                        $type = 'year';
                    } elseif ($type == 'unsigned_int') {
                        $type = 'unsignedInteger';
                    } elseif ($type == 'unsignedInteger') {
                        $type = 'unsignedInteger';
                    } elseif ($type == 'timestamp') {
                        $type = 'timestamp';
                    } elseif ($type == 'json') {
                        $type = 'json';
                    } elseif ($type == 'enum') {
                        $type = 'enum';
                    } elseif ($type == 'float') {
                        $type = 'float';
                    } elseif ($type == 'double') {
                        $type = 'double';
                    } elseif ($type == 'decimal') {
                        $type = 'decimal';
                    } elseif ($type == 'binary') {
                        $type = 'binary';
                    } elseif ($type == 'uuid') {
                        $type = 'uuid';
                    } elseif ($type == 'password') {
                        $type = 'string';
                    } else {
                        $type = 'string';
                    }

                    if ($type == 'enum') {
                        $enumString = implode("','", $enumvalue);
                        $content .= "            \$table->{$type}('{$fieldName[0]}', ['{$enumString}'])->nullable();\n";
                    } else if ($type == 'string') {
                        $content .= "            \$table->string('{$fieldName[0]}', {$stringLimit})->nullable();\n";
                    } else if ($type == 'tinyInteger') {
                        $content .= "            \$table->tinyInteger('{$fieldName[0]}')->default(0);\n";
                    } else {
                        $content .= "            \$table->{$type}('{$fieldName[0]}')->nullable();\n";
                    }
                }
            }
        }
        $content .= <<<EOD

                    \$table->bigInteger('creator')->unsigned()->nullable();
                    \$table->string('slug', 50)->nullable();
                    \$table->enum('status', ['active', 'inactive'])->default('active');
                    \$table->timestamps();
                    \$table->softDeletes();
                });
            }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('{$tableName}');
            }
        };
        EOD;

        return $content;
    }
}
