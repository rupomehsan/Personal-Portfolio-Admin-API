<?php

namespace Modules\Management\UserManagement\Role\Actions;

use Modules\Management\UserManagement\Role\Database\Models\Model as Role;

class UpdateStatus
{
    public static function execute()
    {
        try {

            
            $data = Role::query()->where('slug', request('slug'))->first();
          
 
            if (!$data) {
                return messageResponse('Data not found...', $data, 404, 'error');
            }
            if ($data->status == 'active') {
                $data->status = 'inactive';
            } else {
                $data->status = 'active';
            }

            $data->update();

            return messageResponse('Role status updated successfully', $data, 201, 'success');
        } catch (\Exception $e) {
            return messageResponse($e->getMessage(), [], 500, 'server_error');
        }
    }
}