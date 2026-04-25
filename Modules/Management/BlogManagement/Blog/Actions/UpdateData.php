<?php

namespace Modules\Management\BlogManagement\Blog\Actions;

class UpdateData
{
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public static function execute($request, $slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }

            $requestData = $request->validated();

            // ── Thumbnail (single image) ───────────────────────────────────
            if ($request->hasFile('thumbnail_image')) {
                $requestData['thumbnail_image'] = uploader(
                    $request->file('thumbnail_image'),
                    'uploads/BlogManagement/Blog'
                );
            } elseif ($request->has('thumbnail_image_clear')) {
                $requestData['thumbnail_image'] = null;
            } else {
                // Field not touched — keep current value
                unset($requestData['thumbnail_image']);
            }

            // ── Images (multiple) ──────────────────────────────────────────
            // The frontend sends:
            //   images_present = "1"          → field was rendered (always)
            //   images_kept[]  = "path/..."   → existing paths the user kept
            //   images[]       → any new file uploads
            if ($request->has('images_present')) {
                $kept = array_values(array_filter((array) $request->input('images_kept', [])));

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $file) {
                        $kept[] = uploader($file, 'uploads/BlogManagement/Blog');
                    }
                }

                $requestData['images'] = $kept;   // PHP array → Eloquent cast encodes once
            } else {
                // Field was not in the form — don't overwrite existing images
                unset($requestData['images']);
            }

            // Remove helper fields that have no DB columns
            unset($requestData['images_present'], $requestData['images_kept'], $requestData['thumbnail_image_clear']);

            $data->update($requestData);
            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
