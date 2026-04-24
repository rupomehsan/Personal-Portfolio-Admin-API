<?php

namespace Modules\Management\CredentialManagement\Credential\Controller;
use Modules\Management\CredentialManagement\Credential\Actions\GetAllData;
use Modules\Management\CredentialManagement\Credential\Actions\DestroyData;
use Modules\Management\CredentialManagement\Credential\Actions\GetSingleData;
use Modules\Management\CredentialManagement\Credential\Actions\StoreData;
use Modules\Management\CredentialManagement\Credential\Actions\UpdateData;
use Modules\Management\CredentialManagement\Credential\Actions\UpdateStatus;
use Modules\Management\CredentialManagement\Credential\Actions\SoftDelete;
use Modules\Management\CredentialManagement\Credential\Actions\RestoreData;
use Modules\Management\CredentialManagement\Credential\Actions\ImportData;
use Modules\Management\CredentialManagement\Credential\Validations\BulkActionsValidation;
use Modules\Management\CredentialManagement\Credential\Validations\DataStoreValidation;
use Modules\Management\CredentialManagement\Credential\Actions\BulkActions;
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