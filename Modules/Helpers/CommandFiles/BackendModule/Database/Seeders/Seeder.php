<?php

use Illuminate\Support\Str;

if (!function_exists('Seeder')) {
    function Seeder($moduleName, $module_Name, $fields)
    {
        $targetClass = null;
        $formated_module = explode('/', $moduleName);
        if (count($formated_module) > 1) {
            $moduleName = implode('\\', $formated_module);
            $targetClass = $moduleName;
            $moduleName = Str::replace("/", "\\", $moduleName);
        } else {
            $moduleName = Str::replace("/", "\\", $moduleName);
            $targetClass = $moduleName;
        }



        // dd($targetClass);


        $content = <<<"EOD"
        <?php
        namespace Modules\\Management\\{$moduleName}\\Database\\Seeders;

        use Illuminate\Database\Seeder as SeederClass;
        use Faker\Factory as Faker;

        class Seeder extends SeederClass
        {
            /**
             * Run the database seeds.
             php artisan db:seed --class="Modules\\Management\\{$targetClass}\\Database\\Seeders\\Seeder"
             */
            static \$model = \Modules\\Management\\{$moduleName}\\Database\\Models\\Model::class;

            public function run(): void
            {
                \$faker = Faker::create();
                self::\$model::truncate();

                for (\$i = 1; \$i <= 100; \$i++) {
                    self::\$model::create([
        EOD;

        if (count($fields)) {
            foreach ($fields as $field) {
                [$fieldName, $fieldType] = $field;

                switch ($fieldType) {
                    case str_starts_with($fieldType, 'string'):
                        if (preg_match('/^string-(\d+)$/', $fieldType, $matches)) {
                            $length = (int) $matches[1];
                            $content .= "                '$fieldName' => \$faker->text($length),\n";
                        } else {
                            $content .= "                '$fieldName' => \$faker->sentence,\n";
                        }
                        break;
                    case 'text':
                    case 'longtext':
                        $content .= "                '$fieldName' => \$faker->paragraph,\n";
                        break;
                    case 'int':
                    case 'integer':
                        $content .= "                '$fieldName' => \$faker->randomNumber,\n";
                        break;
                    case strpos($fieldType, 'enum-') === 0: // Handles cases like 'enum-male.female'
                        $options = explode('.', str_replace('enum-', '', $fieldType));
                        $content .= "                '$fieldName' => \$faker->randomElement(" . var_export($options, true) . "),\n";
                        break;
                    case 'json':
                        $content .= "                '$fieldName' => json_encode([\$faker->word, \$faker->word]),\n";
                        break;
                    case 'float':
                        $content .= "                '$fieldName' => \$faker->randomFloat(2, 0, 1000),\n";
                        break;
                    case 'tinyint':
                    case  'boolean':
                        $content .= "                '$fieldName' => \$faker->boolean,\n";
                        break;

                    case 'datetime':
                        $content .= "                '$fieldName' => \$faker->dateTime,\n";
                        break;

                    case str_starts_with($fieldType, 'bigint'):
                        $content .= "                '$fieldName' => \$faker->randomNumber(8),\n";
                        break;  
                    default:
                        $content .= "                '$fieldName' => \$faker->word,\n";
                        break;
                }
            }
        }

        $content .= <<<"EOD"
                    ]);
                }
            }
        }
        EOD;

        return $content;
    }
}
