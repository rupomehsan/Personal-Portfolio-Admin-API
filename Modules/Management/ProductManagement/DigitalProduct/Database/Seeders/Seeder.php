<?php
namespace Modules\Management\ProductManagement\DigitalProduct\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\ProductManagement\DigitalProduct\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\ProductManagement\DigitalProduct\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 100; $i++) {
            self::$model::create([                'category_id' => $faker->randomNumber(8),
                'title' => $faker->text(150),
                'short_description' => $faker->paragraph,
                'description' => $faker->paragraph,
                'regular_price' => $faker->randomFloat(2, 0, 1000),
                'sale_price' => $faker->randomFloat(2, 0, 1000),
                'demo_url' => $faker->text(150),
                'file_path' => $faker->paragraph,
                'file_type' => $faker->text(50),
                'version' => $faker->text(50),
                'thumbnail_image' => $faker->text(100),
                'gallery_images' => $faker->paragraph,
                'features' => $faker->paragraph,
                'tags' => $faker->paragraph,
                'is_active' => $faker->boolean,
                'is_featured' => $faker->boolean,
            ]);
        }
    }
}