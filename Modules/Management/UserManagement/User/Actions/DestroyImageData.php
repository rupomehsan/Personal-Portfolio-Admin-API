<?php

namespace Modules\Management\UserManagement\User\Actions;

use Illuminate\Support\Facades\DB;

class DestroyImageData
{
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    public static function execute($dbName, $slug)
    {
        try {

            // Accept `data` coming as a query string (encoded JSON) or as an array in the request body
            $raw = request()->input('data', null);

            $parsed = null;
            if (is_string($raw) && $raw !== '') {
                // Try to decode JSON string. Laravel will typically URL-decode query params for us,
                // but be defensive in case it's still percent-encoded.
                $try = json_decode(urldecode($raw), true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                    $parsed = $try;
                } else {
                    $try2 = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($try2)) {
                        $parsed = $try2;
                    }
                }
            } elseif (is_array($raw)) {
                $parsed = $raw;
            }

            // Fallback: sometimes callers send `field` directly
            if (!$parsed) {
                $parsed = [];
                if (request()->has('field')) {
                    $parsed['field'] = request()->input('field');
                }
                if (request()->has('index')) {
                    $parsed['index'] = request()->input('index');
                }
            }

            $imageField = $parsed['field'] ?? null;

            if (!$imageField) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image field specified',
                    'received' => $raw,
                    'parsed' => $parsed,
                ], 400);
            }


            if ($imageField) {
                // Get the previous image path from the database
                $record = DB::table($dbName)->where('slug', $slug)->first();
                $imagePath = $record->{$imageField} ?? null;

                if ($imagePath && file_exists(public_path($imagePath))) {
                    @unlink(public_path($imagePath));
                }
            }

            // Update the record to clear the image field
            DB::table($dbName)->where('slug', $slug)
                ->update([$imageField => null]);

            return messageResponse('Item Successfully deleted', [], 200, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
