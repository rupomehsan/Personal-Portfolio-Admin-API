<?php

namespace Modules\Management\UserManagement\User\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\Modules\Management\UserManagement\User\Seeder\Seeder"
     */
    static $model = \Modules\Management\UserManagement\User\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'name' => "super",
            'name' => "super",
            'email' => "superadmin@gmail.com",
            'password' => Hash::make('@12345678'),
            'image' => 'avatar.png',
            'role_id' => 1,
        ]);
        self::$model::create([
            'name' => "admin",
            'name' => "admin",
            'email' => "admin@gmail.com",
            'password' => Hash::make('@12345678'),
            'image' => 'avatar.png',
            'role_id' => 2,
        ]);
        self::$model::create([
            'name' => "student",
            'name' => "student",
            'email' => "student@gmail.com",
            'password' => Hash::make('@12345678'),
            'image' => 'avatar.png',
            'role_id' => 3,
        ]);
    }
}
