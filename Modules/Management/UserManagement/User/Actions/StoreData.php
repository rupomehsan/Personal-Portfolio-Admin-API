<?php

namespace Modules\Management\UserManagement\User\Actions;

use Illuminate\Support\Facades\DB;
use App\Events\UserActivityEvent;
use App\Traits\LogsUserActivity;

class StoreData
{
    use LogsUserActivity;

    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;
    static $UserAddressModel = \Modules\Management\UserManagement\User\Database\Models\UserAddressModel::class;
    static $UserSocialLinkModel = \Modules\Management\UserManagement\User\Database\Models\UserSocialLinkModel::class;

    public static function execute($request)
    {
        try {
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

            $requestData['password'] = bcrypt($requestData['password']);
            $address = "{$requestData['state']} , {$requestData['city']} , {$requestData['post']} , {$requestData['country']}";

            DB::beginTransaction();

            // Create user
            $data = self::$model::query()->create([
                'role_id' => $requestData['role_id'],
                'user_name'    => $requestData['user_name'],
                'name'   => $requestData['name'],
                'name'    => $requestData['name'],
                'image'        => $requestData['image'] ?? 'avatar.png',
                'email'        => $requestData['email'],
                'password'     => $requestData['password']
            ]);

            //create address
            self::$UserAddressModel::query()->create([
                'user_id' => $data->id,
                'phone_number' => $requestData['phone_number'],
                'state'        => $requestData['state'],
                'city'         => $requestData['city'],
                'post'         => $requestData['post'],
                'country'      => $requestData['country'],
                'address'      => $address,
            ]);

            //create social media links
            if (!empty($socialMediaData)) {
                foreach ($socialMediaData as $item) {
                    // Expecting each $item to be an array with 'media_name' and 'media_link'
                    self::$UserSocialLinkModel::query()->create([
                        'user_id'    => $data->id,
                        'media_name' => $item['media_name'] ?? null,
                        'link'       => $item['media_link'] ?? null,
                    ]);
                }
            }

            DB::commit();

            // Method 1: Using Trait (Static)
            // self::logCrudStatic('create', 'User', $data->id, [
            //     'user_name' => $data->user_name,
            //     'email' => $data->email
            // ], $request);

            // // Method 2: Manual Event (Direct)
            // event(new UserActivityEvent(
            //     auth()->id(),
            //     [
            //         'title' => 'User Creation Successful',
            //         'status' => 'success',
            //         'status_code' => 201,
            //         'message' => "User '{$data->user_name}' created successfully with all related data",
            //         'action_type' => 'user_creation_complete',
            //     ],
            //     $request,
            //     [
            //         'created_user_id' => $data->id,
            //         'user_name' => $data->user_name,
            //         'has_social_links' => !empty($socialMediaData),
            //         'social_links_count' => count($socialMediaData)
            //     ]
            // ));

            // Return success response
            return messageResponse('Item added successfully', $data, 201);
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
