<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Actions;

class UpdateData
{
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {

            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }

            $requestData = $request->validated();
             // ── Thumbnail (single image) ───────────────────────────────────
            if ($request->hasFile('thumbnail_image')) {
                $requestData['thumbnail_image'] = uploader(
                    $request->file('thumbnail_image'),
                    'uploads/ProductManagement'
                );
            } elseif ($request->has('thumbnail_image_clear')) {
                $requestData['thumbnail_image'] = null;
            } else {
                // Field not touched — keep current value
                unset($requestData['thumbnail_image']);
            }

            // ── Images (multiple) ──────────────────────────────────────────
            // ImageComponent sends: gallery_images_present=1, gallery_images_kept[]=path, gallery_images[]=files
            if ($request->has('gallery_images_present')) {
                $kept = array_values(array_filter((array) $request->input('gallery_images_kept', [])));

                if ($request->hasFile('gallery_images')) {
                    foreach ($request->file('gallery_images') as $file) {
                        $kept[] = uploader($file, 'uploads/ProductManagement');
                    }
                }

                $requestData['gallery_images'] = $kept;
            } else {
                unset($requestData['gallery_images']);
            }

            // Remove helper fields that have no DB columns
            unset($requestData['gallery_images_present'], $requestData['gallery_images_kept'], $requestData['thumbnail_image_clear']);

            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);

        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}