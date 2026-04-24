<?php
namespace Modules\Management\ProjectManagement\Project\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="Modules\Management\ProjectManagement\Project\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\ProjectManagement\Project\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        $projects = [
            [
                "title" => "Real Estate Website",
                "desc" => "A full-featured real estate platform.",
                "link" => "https://stcdhaka.com/",
                "tags" => ["Web App", "Real Estate"],
            ],
            [
                "title" => "Ecommerce with Laravel & Vue",
                "desc" => "Modern ecommerce solution utilizing Laravel, Vue.js, and Inertia.",
                "link" => "https://etek.com.bd",
                "tags" => ["Laravel", "Vue.js", "Inertia", "Ecommerce"],
            ],
            [
                "title" => "MLM Ecommerce System",
                "desc" => "Multi Level Marketing based ecommerce architecture.",
                "link" => "https://yninetwork.com/",
                "tags" => ["MLM", "Ecommerce", "System"],
            ],
            [
                "title" => "School Management System",
                "desc" => "Comprehensive management system for educational institutions.",
                "link" => "https://miamjuraindhaka.edu.bd",
                "tags" => ["Management", "Education"],
            ],
            [
                "title" => "Cooking Website",
                "desc" => "Interactive cooking platform.",
                "link" => "https://mca-edu.com",
                "tags" => ["Web Platform"],
            ],
            [
                "title" => "Radio App API & Admin",
                "desc" => "Backend API and admin panel built for CodeCanyon.",
                "link" => "",
                "tags" => ["API", "Admin Panel", "CodeCanyon"],
            ],
            [
                "title" => "Newspaper App API & Admin",
                "desc" => "Backend API and admin panel built for CodeCanyon.",
                "link" => "",
                "tags" => ["API", "Admin Panel", "CodeCanyon"],
            ],
            [
                "title" => "Weather App API & Admin",
                "desc" => "Backend API and admin panel built for CodeCanyon.",
                "link" => "",
                "tags" => ["API", "Admin Panel", "CodeCanyon"],
            ],
            [
                "title" => "Motivational App API & Admin",
                "desc" => "Backend API and admin panel built for CodeCanyon.",
                "link" => "",
                "tags" => ["API", "Admin Panel", "CodeCanyon"],
            ],
            [
                "title" => "Live Streaming Sports Site",
                "desc" => "Platform for streaming live sports events, developed for CodeCanyon.",
                "link" => "",
                "tags" => ["Streaming", "Sports", "CodeCanyon"],
            ],
            [
                "title" => "Online Insurance Application",
                "desc" => "Digital platform for processing insurance applications.",
                "link" => "",
                "tags" => ["Insurance", "Web App"],
            ],
            [
                "title" => "Blood Management System",
                "desc" => "System for tracking and managing blood donations and inventory.",
                "link" => "",
                "tags" => ["Healthcare", "Management"],
            ],
            [
                "title" => "Ecommerce Website",
                "desc" => "Custom ecommerce platform for retail businesses.",
                "link" => "",
                "tags" => ["Ecommerce", "Web App"],
            ],
            [
                "title" => "Blog Website",
                "desc" => "Dynamic and responsive blogging platform.",
                "link" => "",
                "tags" => ["Blog", "CMS"],
            ],
            [
                "title" => "Account Software",
                "desc" => "Financial accounting and tracking software.",
                "link" => "",
                "tags" => ["Finance", "Software"],
            ],
            [
                "title" => "Planning Software",
                "desc" => "Strategic planning and task management application.",
                "link" => "",
                "tags" => ["Management", "Software"],
            ],
        ];

        foreach ($projects as $project) {
            self::$model::create([
                'name' => $project['title'],
                'link' => $project['link'],
                'description' => $project['desc'],
                'thumb_image' => $faker->imageUrl(), // Placeholder image since we don't have one in the array
                'images' => json_encode([$faker->imageUrl(), $faker->imageUrl()]),
            ]);
        }
    }
}