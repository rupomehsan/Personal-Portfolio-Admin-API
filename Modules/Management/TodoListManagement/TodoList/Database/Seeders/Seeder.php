<?php
namespace Modules\Management\TodoListManagement\TodoList\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\TodoListManagement\TodoList\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\TodoListManagement\TodoList\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'category_id' => $faker->randomNumber(8),
                'priority' => $faker->randomNumber(8),
                'is_complete' => $faker->boolean,
                'progress' => $faker->sentence,
            ]);
        }
    }
}