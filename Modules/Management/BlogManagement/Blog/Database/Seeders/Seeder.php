<?php
namespace Modules\Management\BlogManagement\Blog\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\BlogManagement\Blog\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        for ($i = 1; $i <= 10; $i++) {
            self::$model::create([                
                'blog_category_id' => $faker->randomNumber(8),
                'title' => $faker->text(150),
                'description' => $faker->paragraph,
                'content' => $faker->paragraph,
                'reading_time' => $faker->randomNumber,
                'tags' => $faker->paragraph,
                'publish_date' => $faker->dateTime,
                'writer' => $faker->randomNumber(8),
                'thumbnail_image' => $faker->word,
                'images' => $faker->word,
                'blog_type' => $faker->randomElement(array (
                                        0 => 'news',
                                        1 => 'tutorial',
                                        2 => 'opinion',
                                        )),
                'url' => $faker->word,
                'show_top' => 1,
                'contributors' => json_encode([$faker->word, $faker->word]),
                'video_link' => $faker->text(50),
                'is_featured' => $faker->boolean,
                'is_published' => $faker->boolean,
            ]);
        }
    }
}