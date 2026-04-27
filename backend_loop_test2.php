<?php
$code = file_get_contents('Modules/Helpers/CommandFiles/BackendModule/Database/Seeders/Seeder.php');

$oldSwitch = <<<OLD
                switch (\$fieldType) {
                    case str_starts_with(\$fieldType, 'string'):
                        if (preg_match('/^string-(\d+)\$/', \$fieldType, \$matches)) {
                            \$length = (int) \$matches[1];
                            \$content .= "                '\$fieldName' => \$faker->text(\$length),\n";
                        } else {
                            \$content .= "                '\$fieldName' => \$faker->sentence,\n";
                        }
                        break;
                    case 'text':
                    case 'longtext':
                        \$content .= "                '\$fieldName' => \$faker->paragraph,\n";
                        break;
                    case 'int':
                    case 'integer':
                        \$content .= "                '\$fieldName' => \$faker->randomNumber,\n";
                        break;
                    case strpos(\$fieldType, 'enum-') === 0: // Handles cases like 'enum-male.female'
                        \$options = explode('.', str_replace('enum-', '', \$fieldType));
                        \$content .= "                '\$fieldName' => \$faker->randomElement(" . var_export(\$options, true) . "),\n";
                        break;
                    case 'json':
                        \$content .= "                '\$fieldName' => json_encode([\$faker->word, \$faker->word]),\n";
                        break;
                    case 'float':
                    case 'decimal':
                    case 'double':
                        \$content .= "                '\$fieldName' => \$faker->randomFloat(2, 0, 1000),\n";
                        break;
                    case 'tinyint':
                    case  'boolean':
                        \$content .= "                '\$fieldName' => \$faker->boolean,\n";
                        break;

                    case 'datetime':
                        \$content .= "                '\$fieldName' => \$faker->dateTime,\n";
                        break;

                    case str_starts_with(\$fieldType, 'bigint'):
                        \$content .= "                '\$fieldName' => \$faker->randomNumber(8),\n";
                        break;  
                    default:
                        \$content .= "                '\$fieldName' => \$faker->word,\n";
                        break;
                }
OLD;

$newSwitch = <<<NEW
                switch (true) {
                    case str_starts_with(\$fieldType, 'string'):
                    case str_starts_with(\$fieldType, 'stringfile'):
                    case str_starts_with(\$fieldType, 'file'):
                    case str_starts_with(\$fieldType, 'image'):
                        if (preg_match('/^(?:string|stringfile|file|image)-(\d+)\$/', \$fieldType, \$matches)) {
                            \$length = (int) \$matches[1];
                            \$content .= "                '\$fieldName' => \$faker->text(\$length),\n";
                        } else {
                            \$content .= "                '\$fieldName' => \$faker->word,\n";
                        }
                        break;
                    case in_array(\$fieldType, ['text', 'longtext', 'images']):
                        \$content .= "                '\$fieldName' => \$faker->paragraph,\n";
                        break;
                    case in_array(\$fieldType, ['int', 'integer', 'number', 'intiger']):
                        \$content .= "                '\$fieldName' => \$faker->randomNumber(5),\n";
                        break;
                    case str_starts_with(\$fieldType, 'enum-'):
                    case str_starts_with(\$fieldType, 'tinyint-'):
                    case str_starts_with(\$fieldType, 'boolean-'):
                        \$baseType = explode('-', \$fieldType)[0];
                        \$options = explode('.', str_replace(\$baseType . '-', '', \$fieldType));
                        \$content .= "                '\$fieldName' => \$faker->randomElement(" . var_export(\$options, true) . "),\n";
                        break;
                    case \$fieldType === 'json':
                        \$content .= "                '\$fieldName' => json_encode([\$faker->word, \$faker->word]),\n";
                        break;
                    case in_array(\$fieldType, ['float', 'decimal', 'double']):
                        \$content .= "                '\$fieldName' => \$faker->randomFloat(2, 0, 1000),\n";
                        break;
                    case in_array(\$fieldType, ['tinyint', 'boolean']):
                        \$content .= "                '\$fieldName' => \$faker->boolean,\n";
                        break;
                    case in_array(\$fieldType, ['date', 'datetime', 'timestamp']):
                        \$content .= "                '\$fieldName' => \$faker->dateTime()->format('Y-m-d H:i:s'),\n";
                        break;
                    case str_starts_with(\$fieldType, 'bigint') || str_starts_with(\$fieldType, 'biginteger'):
                        \$content .= "                '\$fieldName' => \$faker->randomNumber(8),\n";
                        break;  
                    case \$fieldType === 'uuid':
                        \$content .= "                '\$fieldName' => \$faker->uuid,\n";
                        break;
                    default:
                        \$content .= "                '\$fieldName' => \$faker->word,\n";
                        break;
                }
NEW;

$code = str_replace($oldSwitch, $newSwitch, $code);
file_put_contents('Modules/Helpers/CommandFiles/BackendModule/Database/Seeders/Seeder.php', $code);
echo "Replaced\n";
