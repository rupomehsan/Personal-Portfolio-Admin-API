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

                switch (true) {
                    case str_starts_with($fieldType, 'string'):
                    case str_starts_with($fieldType, 'stringfile'):
                    case str_starts_with($fieldType, 'file'):
                    case str_starts_with($fieldType, 'image'):
                        if (preg_match('/^(?:string|stringfile|file|image)-(\d+)$/', $fieldType, $matches)) {
                            $length = (int) $matches[1];
                            $content .= "                '$fieldName' => \$faker->text($length),\n";
                        } else {
                            $content .= "                '$fieldName' => \$faker->word,\n";
                        }
                        break;
                    case in_array($fieldType, ['text', 'longtext', 'images']):
                        $content .= "                '$fieldName' => \$faker->paragraph,\n";
                        break;
                    case in_array($fieldType, ['int', 'integer', 'number', 'intiger']):
                        $content .= "                '$fieldName' => \$faker->randomNumber(5),\n";
                        break;
                    case str_starts_with($fieldType, 'enum-'):
                    case str_starts_with($fieldType, 'tinyint-'):
                    case str_starts_with($fieldType, 'boolean-'):
                        $baseType = explode('-', $fieldType)[0];
                        $options = explode('.', str_replace($baseType . '-', '', $fieldType));
                        $content .= "                '$fieldName' => \$faker->randomElement(" . var_export($options, true) . "),\n";
                        break;
                    case $fieldType === 'json':
                        $content .= "                '$fieldName' => json_encode([\$faker->word, \$faker->word]),\n";
                        break;
                    case in_array($fieldType, ['float', 'decimal', 'double']):
                        $content .= "                '$fieldName' => \$faker->randomFloat(2, 0, 1000),\n";
                        break;
                    case in_array($fieldType, ['tinyint', 'boolean']):
                        $content .= "                '$fieldName' => \$faker->boolean,\n";
                        break;
                    case in_array($fieldType, ['date', 'datetime', 'timestamp']):
                        $content .= "                '$fieldName' => \$faker->dateTime()->format('Y-m-d H:i:s'),\n";
                        break;
                    case str_starts_with($fieldType, 'bigint') || str_starts_with($fieldType, 'biginteger'):
                        $content .= "                '$fieldName' => \$faker->randomNumber(8),\n";
                        break;  
                    case $fieldType === 'uuid':
                        $content .= "                '$fieldName' => \$faker->uuid,\n";
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
