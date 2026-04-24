<?php

namespace Modules\Management\Contact\Controller;
use Modules\Management\Contact\Actions\GetAllData;
use Modules\Management\Contact\Actions\DestroyData;
use Modules\Management\Contact\Actions\GetSingleData;
use Modules\Management\Contact\Actions\StoreData;
use Modules\Management\Contact\Actions\UpdateData;
use Modules\Management\Contact\Actions\UpdateStatus;
use Modules\Management\Contact\Actions\SoftDelete;
use Modules\Management\Contact\Actions\RestoreData;
use Modules\Management\Contact\Actions\ImportData;
use Modules\Management\Contact\Validations\BulkActionsValidation;
use Modules\Management\Contact\Validations\DataStoreValidation;
use Modules\Management\Contact\Actions\BulkActions;
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