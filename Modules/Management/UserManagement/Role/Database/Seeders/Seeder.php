<?php

namespace Modules\Management\UserManagement\Role\Database\Seeders;

use Illuminate\Database\Seeder as SeederClass;
use Faker\Factory as Faker;

class Seeder extends SeederClass
{
    /**
     * Run the database seeds.
     php artisan db:seed --class="\Modules\Management\UserManagement\Role\Database\Seeders\Seeder"
     */
    static $model = \Modules\Management\UserManagement\Role\Database\Models\Model::class;

    public function run(): void
    {
        $faker = Faker::create();
        self::$model::truncate();

        self::$model::create([
            'name' => "super_admin",
            'serial_no' => 1,
        ]);
        self::$model::create([
            'name' => "admin",
            'serial_no' => 2,
        ]);

        // Generate 100 additional roles
        $roles = [
            'Editor', 'Viewer', 'Contributor', 'Manager', 'Moderator', 'Analyst', 'Specialist',
            'Coordinator', 'Supervisor', 'Officer', 'Developer', 'Designer', 'Consultant',
            'Analyst Junior', 'Senior Manager', 'Team Lead', 'Quality Assurance', 'Product Manager',
            'Marketing Manager', 'Sales Manager', 'Finance Manager', 'HR Manager', 'Operations Manager',
            'Support Agent', 'Support Manager', 'Content Writer', 'SEO Specialist', 'Graphic Designer',
            'UI Designer', 'UX Designer', 'Frontend Developer', 'Backend Developer', 'Full Stack Developer',
            'Database Administrator', 'System Administrator', 'Network Administrator', 'Security Officer',
            'Data Analyst', 'Business Analyst', 'QA Tester', 'QA Lead', 'Project Manager', 'Scrum Master',
            'DevOps Engineer', 'Cloud Architect', 'Solutions Architect', 'Technical Writer', 'Documentation Officer',
            'Community Manager', 'Social Media Manager', 'Brand Manager', 'Customer Success Manager', 'Account Manager',
            'Sales Executive', 'Sales Representative', 'Business Development', 'Strategic Planner', 'Compliance Officer',
            'Legal Advisor', 'Financial Analyst', 'Accountant', 'Audit Officer', 'Risk Manager',
            'Procurement Officer', 'Supply Chain Manager', 'Logistics Coordinator', 'Warehouse Manager', 'Inventory Manager',
            'Research Associate', 'Research Director', 'Lab Technician', 'Field Coordinator', 'Training Manager',
            'Recruiter', 'Talent Acquisition', 'Performance Manager', 'Compensation Analyst', 'Benefits Administrator',
            'Executive Assistant', 'Administrative Officer', 'Office Manager', 'Receptionist', 'Customer Representative',
            'Technical Support', 'User Support', 'Help Desk', 'IT Support', 'Service Desk', 'Trainer', 'Instructor'
        ];

       
    }
}
