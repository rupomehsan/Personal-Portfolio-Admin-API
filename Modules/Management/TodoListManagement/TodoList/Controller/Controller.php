<?php

namespace Modules\Management\TodoListManagement\TodoList\Controller;
use Modules\Management\TodoListManagement\TodoList\Actions\GetAllData;
use Modules\Management\TodoListManagement\TodoList\Actions\DestroyData;
use Modules\Management\TodoListManagement\TodoList\Actions\GetSingleData;
use Modules\Management\TodoListManagement\TodoList\Actions\StoreData;
use Modules\Management\TodoListManagement\TodoList\Actions\UpdateData;
use Modules\Management\TodoListManagement\TodoList\Actions\UpdateStatus;
use Modules\Management\TodoListManagement\TodoList\Actions\SoftDelete;
use Modules\Management\TodoListManagement\TodoList\Actions\RestoreData;
use Modules\Management\TodoListManagement\TodoList\Actions\ImportData;
use Modules\Management\TodoListManagement\TodoList\Validations\BulkActionsValidation;
use Modules\Management\TodoListManagement\TodoList\Validations\DataStoreValidation;
use Modules\Management\TodoListManagement\TodoList\Actions\BulkActions;
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