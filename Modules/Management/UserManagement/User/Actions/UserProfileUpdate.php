<?php

namespace Modules\Management\UserManagement\User\Actions;

class UserProfileUpdate
{
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    static $UserAddressModel = \Modules\Management\UserManagement\User\Database\Models\UserAddressModel::class;
    static $UserSocialLinkModel = \Modules\Management\UserManagement\User\Database\Models\UserSocialLinkModel::class;

    public static function execute($request)
    {

        try {

            if (!$data = self::$model::query()->where('id', auth()->id())->first()) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }

            $requestData = $request->validated();

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $requestData['image'] = uploader($image, 'uploads/users');
            }

            self::$model::query()->where('id', auth()->id())->update([
                'user_name'    => $requestData['user_name'],
                'name'   => $requestData['name'],
                'name'    => $requestData['name'],
                'image'        => $requestData['image'] ?? $data->image ?? 'avatar.png',
                'email'        => $requestData['email'],
            ]);

            $address = "{$requestData['state']} , {$requestData['city']} , {$requestData['post']} , {$requestData['country']}";


            // Convert phone_numbers to JSON array if present
            $phoneNumber = null;
            if (isset($requestData['phone_numbers'])) {
                // If already a JSON string, decode it
                if (is_string($requestData['phone_numbers'])) {
                    $phones = json_decode($requestData['phone_numbers'], true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($phones)) {
                        $phoneNumber = json_encode($phones);
                    } else {
                        // fallback: treat as comma-separated string
                        $phones = array_map('trim', explode(',', $requestData['phone_numbers']));
                        $phoneNumber = json_encode($phones);
                    }
                } elseif (is_array($requestData['phone_numbers'])) {
                    $phoneNumber = json_encode($requestData['phone_numbers']);
                }
            }

            // Parse social_media JSON string to array
            $socialMediaData = [];
            if (isset($requestData['social_media'])) {
                if (is_string($requestData['social_media'])) {
                    $socialMediaData = json_decode($requestData['social_media'], true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $socialMediaData = [];
                    }
                } elseif (is_array($requestData['social_media'])) {
                    $socialMediaData = $requestData['social_media'];
                }
            }

            // Update or create address
            self::$UserAddressModel::query()->updateOrCreate(
                [
                    'user_id' => auth()->id(),
                ],
                [
                    'phone_number' => $phoneNumber,
                    'state'        => $requestData['state'],
                    'city'         => $requestData['city'],
                    'post'         => $requestData['post'],
                    'country'      => $requestData['country'],
                    'address'      => $address,
                ]
            );

            // Update social media links - delete existing and create new ones
            self::$UserSocialLinkModel::query()->where('user_id', $data->id)->forceDelete();
            if (!empty($socialMediaData)) {
                foreach ($socialMediaData as $item) {
                    // Expecting each $item to be an array with 'media_name' and 'media_link'
                    if (is_array($item) && !empty($item['media_name']) && !empty($item['media_link'])) {
                        self::$UserSocialLinkModel::query()->create([
                            'user_id'    => $data->id,
                            'media_name' => $item['media_name'],
                            'link'       => $item['media_link'],
                        ]);
                    }
                }
            }

            return messageResponse('Item updated successfully', $data, 201);
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}
