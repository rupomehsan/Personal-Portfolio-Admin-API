<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;

/**
 * User seeder management.
 */

use Modules\Management\UserManagement\Role\Database\Seeders\Seeder as RoleSeeder;
use Modules\Management\UserManagement\User\Database\Seeders\Seeder as UserSeeder;
use Modules\Management\SettingManagement\WebsiteSettings\Database\Seeders\Seeder as WebsiteSettingsSeeder;
use Modules\Management\Contact\Database\Seeders\Seeder as ContactSeeder;
use Modules\Management\BlogManagement\BlogCategory\Database\Seeders\Seeder as BlogCategorySeeder;
use Modules\Management\BlogManagement\Blog\Database\Seeders\Seeder as BlogSeeder;
use Modules\Management\BlogManagement\BlogWriter\Database\Seeders\Seeder as BlogWriterSeeder;
use Modules\Management\BlogManagement\BlogTag\Database\Seeders\Seeder as BlogTagSeeder;
use Modules\Management\ProjectManagement\Project\Database\Seeders\Seeder as ProjectSeeder;
use Modules\Management\CredentialManagement\Credential\Database\Seeders\Seeder as CredentialSeeder;
use Modules\Management\PersonalNoteManagement\PersonalNote\Database\Seeders\Seeder as PersonalNoteSeeder;
use Modules\Management\TodoListManagement\TodoList\Database\Seeders\Seeder as TodoListSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            /**
             * User seeder management.
             */
            RoleSeeder::class,
            UserSeeder::class,
            WebsiteSettingsSeeder::class,
            ContactSeeder::class,

            /**
             * Blog seeder management.
             */
            BlogCategorySeeder::class,
            BlogWriterSeeder::class,
            BlogTagSeeder::class,
            BlogSeeder::class,

            /**
             * Project seeder management.
             */
            ProjectSeeder::class,

            /**
             * Credential seeder management.
             */
            CredentialSeeder::class,

            /**
             * Personal note seeder management.
             */
            PersonalNoteSeeder::class,

            /**
             * Todo list seeder management.
             */
            TodoListSeeder::class,
        ]);
    }
}
