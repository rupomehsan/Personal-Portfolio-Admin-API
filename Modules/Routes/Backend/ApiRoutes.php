<?php

/*
|--------------------------------------------------------------------------
| Auth Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("Modules/Management/Auth/Routes/Route.php");
/*
|--------------------------------------------------------------------------
| Dashboard data
|--------------------------------------------------------------------------
*/
include_once base_path("Modules/Management/Dashboard/Routes/Route.php");
/*
|--------------------------------------------------------------------------
| Setting Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("Modules/Management/SettingManagement/WebsiteSettings/Routes/Route.php");

/*
|--------------------------------------------------------------------------
| User Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("Modules/Management/UserManagement/User/Routes/Route.php");
include_once base_path("Modules/Management/UserManagement/Role/Routes/Route.php");
/*
|--------------------------------------------------------------------------
| Others Management Module
|--------------------------------------------------------------------------
*/

include_once base_path("Modules/Management/Contact/Routes/Route.php");
include_once base_path("Modules/Management/BlogManagement/BlogCategory/Routes/Route.php");
include_once base_path("Modules/Management/BlogManagement/Blog/Routes/Route.php");
include_once base_path("Modules/Management/BlogManagement/BlogWriter/Routes/Route.php");
include_once base_path("Modules/Management/BlogManagement/BlogTag/Routes/Route.php");
include_once base_path("Modules/Management/ProjectManagement/Project/Routes/Route.php");
include_once base_path("Modules/Management/CredentialManagement/Credential/Routes/Route.php");
include_once base_path("Modules/Management/PersonalNoteManagement/PersonalNote/Routes/Route.php");
include_once base_path("Modules/Management/TodoListManagement/TodoList/Routes/Route.php");
include_once base_path("Modules/Management/ProductManagement/DigitalProduct/Routes/Route.php");
