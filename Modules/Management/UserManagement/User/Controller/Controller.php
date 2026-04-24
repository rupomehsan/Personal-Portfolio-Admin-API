<?php

namespace Modules\Management\UserManagement\User\Controller;

use App\Http\Controllers\Controller as ControllersController;
use Modules\Management\UserManagement\User\Actions\StoreData;
use Modules\Management\UserManagement\User\Actions\GetAllData;
use Modules\Management\UserManagement\User\Actions\ImportData;
use Modules\Management\UserManagement\User\Actions\SoftDelete;
use Modules\Management\UserManagement\User\Actions\UpdateData;
use Modules\Management\UserManagement\User\Actions\BulkActions;
use Modules\Management\UserManagement\User\Actions\DestroyData;
use Modules\Management\UserManagement\User\Actions\RestoreData;
use Modules\Management\UserManagement\User\Actions\GetSingleData;

use Modules\Management\UserManagement\User\Actions\DestroyImageData;
use Modules\Management\UserManagement\User\Actions\UserProfileUpdate;
use Modules\Management\UserManagement\User\Actions\UserChangePassword;
use Modules\Management\UserManagement\User\Validations\DataStoreValidation;
use Modules\Management\UserManagement\User\Validations\DataUpdateValidation;
use Modules\Management\UserManagement\User\Validations\BulkActionsValidation;

use Modules\Management\UserManagement\User\Validations\UserProfileUpdateValidation;
use Modules\Management\UserManagement\User\Validations\UserChangePasswordValidation;


class Controller extends ControllersController
{

    public function index()
    {

        $data = GetAllData::execute();
        return $data;
    }

    public function store(DataStoreValidation $request)
    {

        $data = StoreData::execute($request);
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataUpdateValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }

    public function UserProfileUpdate(UserProfileUpdateValidation $request,)
    {
        $data = UserProfileUpdate::execute($request);
        return $data;
    }
    public function UserChangePassword(UserChangePasswordValidation $request,)
    {
        $data = UserChangePassword::execute($request);
        return $data;
    }

    public function softDelete()
    {
        $data = SoftDelete::execute();
        return $data;
    }
    public function destroy($slug)
    {
        $data = DestroyData::execute($slug);
        return $data;
    }
    public function restore()
    {
        $data = RestoreData::execute();
        return $data;
    }
    public function import()
    {
        $data = ImportData::execute();
        return $data;
    }
    public function bulkAction(BulkActionsValidation $request)
    {
        $data = BulkActions::execute($request);
        return $data;
    }

    public function imageDelete($dbName, $slug)
    {
        $data = DestroyImageData::execute($dbName, $slug);
        return $data;
    }
}
