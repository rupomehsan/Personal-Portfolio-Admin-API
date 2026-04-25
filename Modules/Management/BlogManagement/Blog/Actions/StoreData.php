<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class StoreData
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            if ($request->hasFile('thumbnail_image')) {
                $requestData['thumbnail_image'] = uploader(
                    $request->file('thumbnail_image'),
                    'uploads/BlogManagement/Blog'
                );
            }

            if ($request->hasFile('images')) {
                $paths = [];
                foreach ($request->file('images') as $file) {
                    $paths[] = uploader($file, 'uploads/BlogManagement/Blog');
                }
                // Pass a PHP array — Eloquent's 'array' cast will json_encode it once
                $requestData['images'] = $paths;
            }

            unset($requestData['images_present'], $requestData['images_kept'], $requestData['thumbnail_image_clear']);

            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
