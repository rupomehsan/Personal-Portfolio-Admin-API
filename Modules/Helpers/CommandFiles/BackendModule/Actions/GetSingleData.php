<?php

use Illuminate\Support\Str;

if (!function_exists('GetSingleData')) {
    function GetSingleData($moduleName, $fieldsWithBraces = [])
    {
        $formated_module = explode('/', $moduleName);

        if (count($formated_module) > 1) {

            $moduleName = implode('/', $formated_module);
            $moduleName = Str::replace("/", "\\", $moduleName);
        } else {
            $moduleName = Str::replace("/", "\\", $moduleName);
        }

        if ($fieldsWithBraces && !empty($fieldsWithBraces)) {
            $relationName = '';
            foreach ($fieldsWithBraces as $field) {
                $relationName .= "                 \$with = ['{$field['field']}'];\n";
            }
        } else {
            $relationName = "                 \$with = [];\n";
        }

        $content = <<<"EOD"
            <?php

            namespace Modules\\Management\\{$moduleName}\\Actions;



            class GetSingleData
            {
                static \$model = \Modules\\Management\\{$moduleName}\\Database\\Models\\Model::class;

                public static function execute(\$slug)
                {
                    try {
                        {$relationName}
                        \$fields = request()->input('fields') ?? ['*'];
                        if (!\$data = self::\$model::query()->with(\$with)->select(\$fields)->where('slug', \$slug)->first()) {
                            return messageResponse('Data not found...',\$data, 404, 'error');
                        }
                        return entityResponse(\$data);
                    } catch (\Exception \$e) {
                        return messageResponse(\$e->getMessage(),[], 500, 'server_error');
                    }
                }
            }
            EOD;
        // $content = str_replace('{moduleName}', $moduleName, $content);
        return $content;
    }
}
