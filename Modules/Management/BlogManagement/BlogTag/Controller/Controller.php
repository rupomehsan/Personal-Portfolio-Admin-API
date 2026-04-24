<?php

namespace Modules\Management\BlogManagement\BlogTag\Controller;
use Modules\Management\BlogManagement\BlogTag\Actions\GetAllData;
use Modules\Management\BlogManagement\BlogTag\Actions\DestroyData;
use Modules\Management\BlogManagement\BlogTag\Actions\GetSingleData;
use Modules\Management\BlogManagement\BlogTag\Actions\StoreData;
use Modules\Management\BlogManagement\BlogTag\Actions\UpdateData;
use Modules\Management\BlogManagement\BlogTag\Actions\UpdateStatus;
use Modules\Management\BlogManagement\BlogTag\Actions\SoftDelete;
use Modules\Management\BlogManagement\BlogTag\Actions\RestoreData;
use Modules\Management\BlogManagement\BlogTag\Actions\ImportData;
use Modules\Management\BlogManagement\BlogTag\Validations\BulkActionsValidation;
use Modules\Management\BlogManagement\BlogTag\Validations\DataStoreValidation;
use Modules\Management\BlogManagement\BlogTag\Actions\BulkActions;
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