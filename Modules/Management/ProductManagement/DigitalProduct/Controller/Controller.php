<?php

namespace Modules\Management\ProductManagement\DigitalProduct\Controller;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetAllData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\DestroyData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\GetSingleData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\StoreData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\UpdateData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\UpdateStatus;
use Modules\Management\ProductManagement\DigitalProduct\Actions\SoftDelete;
use Modules\Management\ProductManagement\DigitalProduct\Actions\RestoreData;
use Modules\Management\ProductManagement\DigitalProduct\Actions\ImportData;
use Modules\Management\ProductManagement\DigitalProduct\Validations\BulkActionsValidation;
use Modules\Management\ProductManagement\DigitalProduct\Validations\DataStoreValidation;
use Modules\Management\ProductManagement\DigitalProduct\Actions\BulkActions;
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