<?php

namespace Modules\Management\PersonalNoteManagement\PersonalNote\Controller;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\GetAllData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\DestroyData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\GetSingleData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\StoreData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\UpdateData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\UpdateStatus;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\SoftDelete;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\RestoreData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\ImportData;
use Modules\Management\PersonalNoteManagement\PersonalNote\Validations\BulkActionsValidation;
use Modules\Management\PersonalNoteManagement\PersonalNote\Validations\DataStoreValidation;
use Modules\Management\PersonalNoteManagement\PersonalNote\Actions\BulkActions;
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