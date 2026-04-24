<?php

use Illuminate\Support\Str;

if (!function_exists('RestoreData')) {
    function RestoreData($moduleName)
    {
        $formated_module = explode('/', $moduleName);

        if (count($formated_module) > 1) {

            $moduleName = implode('/', $formated_module);
            $moduleName = Str::replace("/", "\\", $moduleName);
        } else {
            $moduleName = Str::replace("/", "\\", $moduleName);
        }

        $content = <<<"EOD"
            <?php

            namespace Modules\\Management\\{$moduleName}\\Actions;

            class RestoreData
            {
                static \$model = \Modules\\Management\\{$moduleName}\\Database\\Models\\Model::class;

                public static function execute()
                {
                     try {
                        if (!\$data = self::\$model::onlyTrashed()->where('slug', request()->slug)->first()) {
                            return messageResponse('Data not found...', \$data, 404, 'error');
                        }
                        \$data->restore();
                        return messageResponse('Item Successfully  Restored', \$data, 200, 'success');
                    } catch (\Exception \$e) {
                        return messageResponse(\$e->getMessage(),[], 500, 'server_error');
                    }
                }
            }
            EOD;
        return $content;
    }
}
