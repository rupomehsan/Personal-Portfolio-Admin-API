<?php


/*
|--------------------------------------------------------------------------
| BackendModule Routes
|--------------------------------------------------------------------------
|
*/

include_once(__DIR__ . '/BackendModule/Actions/BulkActions.php');
include_once(__DIR__ . '/BackendModule/Actions/DestroyData.php');
include_once(__DIR__ . '/BackendModule/Actions/GetAllData.php');
include_once(__DIR__ . '/BackendModule/Actions/GetSingleData.php');
include_once(__DIR__ . '/BackendModule/Actions/ImportData.php');
include_once(__DIR__ . '/BackendModule/Actions/RestoreData.php');
include_once(__DIR__ . '/BackendModule/Actions/SoftDelete.php');
include_once(__DIR__ . '/BackendModule/Actions/StoreData.php');
include_once(__DIR__ . '/BackendModule/Actions/UpdateData.php');
include_once(__DIR__ . '/BackendModule/Actions/UpdateStatus.php');

include_once(__DIR__ . '/BackendModule/Others/ApiDocumentation.php');
include_once(__DIR__ . '/BackendModule/Others/ImportJob.php');
include_once(__DIR__ . '/BackendModule/Others/CommandGuide.php');

include_once(__DIR__ . '/BackendModule/Controller/Controller.php');

include_once(__DIR__ . '/BackendModule/Database/Models/Model.php');

include_once(__DIR__ . '/BackendModule/Database/Migrations/Migration.php');

include_once(__DIR__ . '/BackendModule/Routes/Route.php');

include_once(__DIR__ . '/BackendModule/Database/Seeders/Seeder.php');

include_once(__DIR__ . '/BackendModule/Validations/BulkActionsValidation.php');
include_once(__DIR__ . '/BackendModule/Validations/DataStoreValidation.php');
include_once(__DIR__ . '/BackendModule/Validations/GetAllDataValidation.php');

/*
|--------------------------------------------------------------------------
| FrontendModule Routes
|--------------------------------------------------------------------------
|
| Dynamic Vue.js module generators for Management interface
| These helpers generate optimized, type-safe Vue components
|
*/

// Setup configuration generators
include_once(__DIR__ . '/FrontendModule/setup/index.php');
include_once(__DIR__ . '/FrontendModule/setup/form_fields.php');
include_once(__DIR__ . '/FrontendModule/setup/routes.php');

// Store generator (Pinia with factory pattern)
include_once(__DIR__ . '/FrontendModule/store/index.php');

