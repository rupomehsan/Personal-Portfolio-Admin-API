<?php

namespace Modules\Management\ProjectManagement\Project\Actions;

class UpdateData
{
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\Model::class;

    public static function execute($request,$slug)
    {
        try {
            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...',$data, 404, 'error');
            }
            $requestData = $request->validated();
             // ── Thumbnail (single image) ───────────────────────────────────
            if ($request->hasFile('thumb_image')) {
                $requestData['thumb_image'] = uploader(
                    $request->file('thumb_image'),
                    'uploads/BlogManagement/Blog'
                );
            } elseif ($request->has('thumb_image_clear')) {
                $requestData['thumb_image'] = null;
            } else {
                // Field not touched — keep current value
                unset($requestData['thumb_image']);
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
            unset($requestData['images_present'], $requestData['images_kept'], $requestData['thumb_image_clear']);

            $data->update($requestData);
            return messageResponse('Item updated successfully',$data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(),[], 500, 'server_error');
        }
    }
}