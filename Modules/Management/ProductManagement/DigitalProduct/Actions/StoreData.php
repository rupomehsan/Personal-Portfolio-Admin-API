<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

class StoreData
{
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\Model::class;

    public static function execute($request)
    {
        try {
            $requestData = $request->validated();

            if ($request->hasFile('thumbnail_image')) {
                $requestData['thumbnail_image'] = uploader(
                    $request->file('thumbnail_image'),
                    'uploads/ProductManagement'
                );
            }

            if ($request->hasFile('gallery_images')) {
                $paths = [];
                foreach ($request->file('gallery_images') as $file) {
                    $paths[] = uploader($file, 'uploads/ProductManagement');
                }
                $requestData['gallery_images'] = $paths;
            }

            unset($requestData['images_present'], $requestData['images_kept'], $requestData['thumbnail_image_clear']);


            if ($data = self::$model::query()->create($requestData)) {
                return messageResponse('Item added successfully', $data, 201);
            }
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}