<?php

namespace Modules\Management\UserManagement\User\Actions;

use Illuminate\Support\Facades\DB;
use App\Events\UserActivityEvent;
use App\Traits\LogsUserActivity;

class UpdateData
{
    use LogsUserActivity;

    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;
    static $UserAddressModel = \Modules\Management\UserManagement\User\Database\Models\UserAddressModel::class;
    static $UserSocialLinkModel = \Modules\Management\UserManagement\User\Database\Models\UserSocialLinkModel::class;
    static $UserLogModel = \Modules\Management\UserManagement\User\Database\Models\UserLogModel::class;
    public static function execute($request, $slug)
    {
        try {
            // throw new \Exception('Forced exception for testing catch block.');

            if (!$data = self::$model::query()->where('slug', $slug)->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }

            $requestData = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/users');
            }

            // Convert phone_number to JSON if present
            if (isset($requestData['phone_number'])) {
                if (is_array($requestData['phone_number'])) {
                    $requestData['phone_number'] = json_encode($requestData['phone_number']);
                } elseif (is_string($requestData['phone_number'])) {
                    // Split by comma, trim spaces, and encode as JSON
                    $phones = array_map('trim', explode(',', $requestData['phone_number']));
                    $requestData['phone_number'] = json_encode($phones);
                }
            }

            // Handle social_media: save to another model/table if present
            $socialMediaData = [];
            if (isset($requestData['social_media']) && is_array($requestData['social_media'])) {
                $socialMediaData = $requestData['social_media'];
                unset($requestData['social_media']);
            }

            // Hash password only if provided
            if (isset($requestData['password']) && !empty($requestData['password'])) {
                $requestData['password'] = bcrypt($requestData['password']);
            } else {
                unset($requestData['password']);
            }

            $address = "{$requestData['state']} , {$requestData['city']} , {$requestData['post']} , {$requestData['country']}";

            DB::beginTransaction();

            // Update user
            $data->update([
                'role_id' => $requestData['role_id'],
                'user_name'    => $requestData['user_name'],
                'name'   => $requestData['name'],
                'name'    => $requestData['name'],
                'image'        => $requestData['image'] ?? $data->image ?? 'avatar.png',
                'email'        => $requestData['email'],
                'password'     => $requestData['password'] ?? $data->password
            ]);

            // Update or create address
            self::$UserAddressModel::query()->updateOrCreate(
                [
                    'user_id' => $data->id,
                ],
                [
                    'phone_number' => $requestData['phone_number'],
                    'state'        => $requestData['state'],
                    'city'         => $requestData['city'],
                    'post'         => $requestData['post'],
                    'country'      => $requestData['country'],
                    'address'      => $address,
                ]
            );

            // Update social media links - delete existing and create new ones
            self::$UserSocialLinkModel::query()->where('user_id', $data->id)->delete();
            if (!empty($socialMediaData)) {
                foreach ($socialMediaData as $item) {
                    if (!empty($item['media_name']) || !empty($item['media_link'])) {
                        self::$UserSocialLinkModel::query()->create([
                            'user_id'    => $data->id,
                            'media_name' => $item['media_name'] ?? null,
                            'link'       => $item['media_link'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Method 1: Using Trait (Static) - Error logging
            // self::logErrorStatic(
            //     "Failed to update user: " . $e->getMessage(),
            //     'user_update',
            //     500,
            //     ['error' => $e->getMessage(), 'slug' => $slug],
            //     $request
            // );

            // // // Method 2: Manual Event (Direct) - Detailed error info
            // event(new UserActivityEvent(
            //     auth()->check() ? auth()->id() : null,
            //     [
            //         'title' => 'User Update Failed',
            //         'status' => 'error',
            //         'status_code' => 500,
            //         'message' => "Critical error during user update: " . $e->getMessage(),
            //         'action_type' => 'user_update_error',
            //     ],
            //     $request,
            //     [
            //         'error_type' => get_class($e),
            //         'slug' => $slug,
            //         'error_file' => $e->getFile(),
            //         'error_line' => $e->getLine()
            //     ]
            // ));

            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
