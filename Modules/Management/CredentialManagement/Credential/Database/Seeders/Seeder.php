<?php
namespace Modules\Management\CredentialManagement\Credential\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\CredentialManagement\Credential\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\CredentialManagement\Credential\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'name' => $faker->sentence,
                'password' => $faker->sentence,
                'description' => $faker->paragraph,
                'group_id' => $faker->randomNumber(8),
            ]);
        }
    }
}