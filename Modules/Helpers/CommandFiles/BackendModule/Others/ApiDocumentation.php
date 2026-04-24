<?php

use Illuminate\Support\Str;

if (!function_exists('ApiDocumentation')) {
    function ApiDocumentation($moduleName)
    {
        $route_name = Str::plural((Str::kebab($moduleName)));

        $content = <<<"EOD"
        @protocol = http://
        # @hostname =
        @hostname = 127.0.0.1:8000
        @endpoint = api/v1/{$route_name}
        @url = {{protocol}}{{hostname}}/{{endpoint}}
        @token = Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YTFjMWRmZC04MWU3LTQ1ODgtYjVhMi0xOTA1ODNiNjZhNmUiLCJqdGkiOiIyYWIxMTMzZjJkMDE3YmE0ZDNmZDk3NzI3NWJlZDY5YjUyMjk1N2M3OGFlYzIxNTA2NTQ5NTUyYTA1M2ZmNzEyMDM4NzU2N2U3ZTRkMzlkNiIsImlhdCI6MTY5NDU3NjcyOC4xOTQ4OTgsIm5iZiI6MTY5NDU3NjcyOC4xOTQ5LCJleHAiOjE3MjYxOTkxMjguMTg2ODc2LCJzdWIiOiIyIiwic2NvcGVzIjpbXX0.gJZW7MbPkwIdJw7kXhPuRK6bMkuiSTPhFXxzoXxcBzX2cT9Nj7ozj7oZXQG_9cDdKe1LIQFxr2DvapUqGMvBfenrhifvWTU0_wrNIhbBQCsS3gaWWfxuvoKkCrQwKqm7wJSB5yN2LszrdqWPgMR9EBpNiUjhXxgrZUdzskjiqG35FZzZkoHOvPxV5LP2QKtekiUv_ekQoMHxeOwOC43XXiLk9cH_XNvyRjSGibh3hZHUvtLEZ0NAfWL1eZlbge8GoQYMJxN-LO72HYCSnrG2bEmWT624iAU13U-t_cEyVDb-HFPaIXOAjOnKwNUJqfwwC1lVBvd6hNyJwODZ6iZevvwUScW3_UuoUnww46Td_A6CI7QfFywQdFolu0vDkHgQMmSmPu-QNDG8HrvSh5W1d4ve-wPaNauISsvVCVXPgdDWW9n4aKI8qAv_RCEZZYa3s9zmRdjweDxG0VeISlaPg6hLzlpO1He83onbjoPSzEWJWC7bb2nUR57zppDHgKKSBP-WW_OHH7fruavkG44_7NuNmvrTFtDclGe4E3XuqZFvno154daJpKKRPzUkbeCp6Cc3X2Qp5k-ORq1cx1NfFYmpM6ZmzFZ3bNV8IV3Cp2HgZMRKHfXWRlxiVAL73sLnSBmvn1iRswMXftl2cm9xiUMQnpJKBq_MDUilRlBTiWE
        @createdAt = {{\$timestamp}}
        # @modifiedBy = {{\$processEnv {$moduleName}NAME}}

        #                                               #
        #------------------ Modules TESTING ----------------#
        #                                               #

        ### get all data without pagination
        ### will return:  {  }
        GET {{url}} HTTP/1.1
        content-type: application/json
        # Authorization: {{token}}

        ### get single data
        # @prompt id enter id
        GET {{url}}/{{id}} HTTP/1.1
        content-type: application/json
        # Authorization: {{token}}

        ### store data
        ### @prompt title enter title
        POST {{url}} HTTP/1.1
        content-type: application/json

        {
            "title": "Title"
        }


        ### store data canvas
        ### @prompt title enter title
        PATCH {{url}}/1 HTTP/1.1
        content-type: application/json
        # Authorization: {{token}}

        {
            "title": "Title update"
        }


        #### soft delete
        # @prompt id enter id
        POST {{url}}/soft-delete HTTP/1.1
        content-type: application/json

        {
            "id": "{{id}}"
        }

        #### permenently delete
        # @prompt id enter id
        DELETE {{url}}/{{id}} HTTP/1.1
        content-type: application/json

        {
            "id": "{{id}}"
        }

        ### restore
        # @prompt id enter id
        POST {{url}}/restore HTTP/1.1
        content-type: application/json
        # Authorization: {{token}}

        {
            "id": "{{id}}"
        }
        EOD;
        // $route = Str::plural((Str::kebab($moduleName)));
        // $content = str_replace('{route_name}', $route, $content);
        return $content;
    }
}


if (!function_exists('Documentation')) {
    function Documentation()
    {

        $content = <<<"EOD"

        if (\$request->hasFile('image')) {
            \$image = \$request->file('image');
            \$requestData['image'] = uploader(\$image, 'uploads/folder_name');
        }
         // search
         if (request()->input('product_category_id')) {
                    \$data->where('product_category_id', request()->input('product_category_id'));
                }
                if (request()->input('product_sub_category_id')) {
                    \$data->where('product_sub_category_id', request()->input('product_sub_category_id'));
                }
        //image removal
               Route::post('image-delete/{slug}', [Controller::class, 'DeletePropertyImage']);


               <?php

            namespace Modules\Management\PropertyManagement\Property\Actions;

            class DeletePropertyImage
            {
                static \$model = \Modules\Management\PropertyManagement\Property\Models\Model::class;

                public static function execute(\$slug)
                {
                    try {

                        // dd(request()->all(), \$slug);

                        if (!\$data = self::\$model::where('slug', \$slug)->first()) {

                            return messageResponse('Data not found...', \$data, 404, 'error');
                        }



                        \$requestData = json_decode(request()->data);

                        \$fields = ['banner_image', 'gallery'];

                        if (
                            isset(\$requestData->field, \$requestData->index) &&
                            in_array(\$requestData->field, \$fields)
                        ) {
                            \$field = \$requestData->field;
                            \$index = \$requestData->index;

                            // Get current array
                            \$images = \$data->\$field ?? [];

                            if (isset(\$images[\$index])) {
                                \$imagePath = \$images[\$index];

                                // Delete file if it exists
                                if (!empty(\$imagePath) && file_exists(public_path(\$imagePath))) {
                                    unlink(public_path(\$imagePath));
                                }

                                // Remove image and re-index
                                unset(\$images[\$index]);
                                \$images = array_values(\$images);

                                // Save back to model
                                \$data->\$field = \$images;
                                \$data->save();
                            }
                        }

                        // Return success message
                        return messageResponse('Item Successfully deleted', [], 200, 'success');
                    } catch (\Exception \$e) {
                        return messageResponse(\$e->getMessage(), [], 500, 'server_error');
                    }
                }
            }

        EOD;

        return $content;
    }
}
