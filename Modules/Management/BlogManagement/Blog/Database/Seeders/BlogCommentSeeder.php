<?php

namespace Modules\Management\BlogManagement\Blog\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class BlogCommentSeeder extends SeederClass
{
    /**
     * php artisan db:seed --class="Modules\Management\BlogManagement\Blog\Database\Seeders\BlogCommentSeeder"
     */
    static $model    = \Modules\Management\BlogManagement\Blog\Database\Models\BlogCommentModel::class;
    static $blogModel = \Modules\Management\BlogManagement\Blog\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();

        $blogIds = self::$blogModel::pluck('id')->take(10)->toArray();

        if (empty($blogIds)) {
            $blogIds = range(1, 10);
        }

        foreach ($blogIds as $index => $blogId) {
            self::$model::create([
                'blog_id' => $blogId,
                'user_id' => null,
                'name'    => $faker->name(),
                'comment' => $faker->paragraph(2),
                'creator' => null,
                'slug'    => 'comment-' . ($index + 1) . '-' . $faker->randomNumber(4),
                'status'  => 'active',
            ]);
        }
    }
}
