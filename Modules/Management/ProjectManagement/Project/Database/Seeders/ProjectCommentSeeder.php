<?php

namespace Modules\Management\ProjectManagement\Project\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class ProjectCommentSeeder extends SeederClass
{
    /**
     * php artisan db:seed --class="Modules\Management\ProjectManagement\Project\Database\Seeders\ProjectCommentSeeder"
     */
    static $model        = \Modules\Management\ProjectManagement\Project\Database\Models\ProjectCommentModel::class;
    static $projectModel = \Modules\Management\ProjectManagement\Project\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();

        $projectIds = self::$projectModel::pluck('id')->take(10)->toArray();

        if (empty($projectIds)) {
            $projectIds = range(1, 10);
        }

        foreach ($projectIds as $index => $projectId) {
            self::$model::create([
                'project_id' => $projectId,
                'parent_id'  => null,
                'user_id'    => null,
                'name'       => $faker->name(),
                'email'      => $faker->safeEmail(),
                'phone'      => $faker->phoneNumber(),
                'comment'    => $faker->paragraph(2),
                'creator'    => null,
                'slug'       => 'project-comment-' . ($index + 1) . '-' . $faker->randomNumber(4),
                'status'     => 'active',
            ]);
        }
    }
}
