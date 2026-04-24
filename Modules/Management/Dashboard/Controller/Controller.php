<?php

namespace Modules\Management\Dashboard\Controller;

use Modules\Management\Dashboard\Actions\GetAllDashboardData;
use Modules\Management\Dashboard\Actions\GetEmployeeDashboardData;

use App\Http\Controllers\Controller as ControllersController;


class Controller extends ControllersController
{

    public function GetAllDashboardData( ){

        $data = GetAllDashboardData::execute();
        return $data;
    }

}