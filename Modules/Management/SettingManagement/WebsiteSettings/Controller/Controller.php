<?php

namespace Modules\Management\SettingManagement\WebsiteSettings\Controller;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\GetAllData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\DestroyData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\GetSingleData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\StoreData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\UpdateData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\SoftDelete;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\RestoreData;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\ImportData;
use Modules\Management\SettingManagement\WebsiteSettings\Validations\BulkActionsValidation;
use Modules\Management\SettingManagement\WebsiteSettings\Validations\DataStoreValidation;
use Modules\Management\SettingManagement\WebsiteSettings\Actions\BulkActions;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function index( ){

        $data = GetAllData::execute();
        return $data;
    }

    public function store(  )
    {
        $data = StoreData::execute();
        return $data;
    }

    public function show($slug)
    {
        $data = GetSingleData::execute($slug);
        return $data;
    }

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
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

}
