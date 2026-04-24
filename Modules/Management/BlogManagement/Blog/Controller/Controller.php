<?php

namespace Modules\Management\BlogManagement\Blog\Controller;
use Modules\Management\BlogManagement\Blog\Actions\GetAllData;
use Modules\Management\BlogManagement\Blog\Actions\DestroyData;
use Modules\Management\BlogManagement\Blog\Actions\GetSingleData;
use Modules\Management\BlogManagement\Blog\Actions\StoreData;
use Modules\Management\BlogManagement\Blog\Actions\UpdateData;
use Modules\Management\BlogManagement\Blog\Actions\UpdateStatus;
use Modules\Management\BlogManagement\Blog\Actions\SoftDelete;
use Modules\Management\BlogManagement\Blog\Actions\RestoreData;
use Modules\Management\BlogManagement\Blog\Actions\ImportData;
use Modules\Management\BlogManagement\Blog\Validations\BulkActionsValidation;
use Modules\Management\BlogManagement\Blog\Validations\DataStoreValidation;
use Modules\Management\BlogManagement\Blog\Actions\BulkActions;
use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function index( ){

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

    public function update(DataStoreValidation $request, $slug)
    {
        $data = UpdateData::execute($request, $slug);
        return $data;
    }
         public function updateStatus()
    {
        $data = UpdateStatus::execute();
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