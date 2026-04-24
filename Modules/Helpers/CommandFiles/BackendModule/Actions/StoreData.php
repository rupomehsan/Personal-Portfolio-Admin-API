<?php

use Illuminate\Support\Str;

if (!function_exists('StoreData')) {
    function StoreData($moduleName, $fileFields, $hasFileUploads = false)
    {
        $formated_module = explode('/', $moduleName);

        if (count($formated_module) > 1) {

            $moduleName = implode('/', $formated_module);
            $moduleNameForFile = Str::replace("/", "/", $moduleName);
            $moduleName = Str::replace("/", "\\", $moduleName);
        } else {
            $moduleName = Str::replace("/", "\\", $moduleName);
            $moduleNameForFile = Str::replace("/", "/", $moduleName);
        }

        if ($hasFileUploads) {

            // Generate individual if statements for each field
            $fileUploadCode = '';
            foreach ($fileFields as $field) {
                $fileUploadCode .= "                if (\$request->hasFile('{$field}')) {\n";
                $fileUploadCode .= "                    \$file = \$request->file('{$field}');\n";
                $fileUploadCode .= "                    \$requestData['{$field}'] = uploader(\$file, 'uploads/{$moduleNameForFile}');\n";
                $fileUploadCode .= "                }\n";
            }

            $content = <<<"EOD"
            <?php

            namespace Modules\\Management\\{$moduleName}\\Actions;

            class StoreData
            {
                static \$model = \Modules\\Management\\{$moduleName}\\Database\\Models\\Model::class;

                public static function execute(\$request)
                {
                    try {
                        \$requestData = \$request->validated();

                        // Process file uploads for specific fields
                        {$fileUploadCode}          
                        if (\$data = self::\$model::query()->create(\$requestData)) {
                            return messageResponse('Item added successfully', \$data, 201);
                        }
                    } catch (\Exception \$e) {
                        return messageResponse(\$e->getMessage(),[], 500, 'server_error');
                    }
                }
            }
            EOD;
        } else {
            $content = <<<"EOD"
            <?php

            namespace Modules\\Management\\{$moduleName}\\Actions;

            class StoreData
            {
                static \$model = \Modules\\Management\\{$moduleName}\\Database\\Models\\Model::class;

                public static function execute(\$request)
                {
                    try {
                        \$requestData = \$request->validated();

                        if (\$data = self::\$model::query()->create(\$requestData)) {
                            return messageResponse('Item added successfully', \$data, 201);
                        }
                    } catch (\Exception \$e) {
                        return messageResponse(\$e->getMessage(),[], 500, 'server_error');
                    }
                }
            }
            EOD;
        }



        // $content = str_replace('{moduleName}', $moduleName, $content);
        return $content;
    }
}
