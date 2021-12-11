<?php

namespace Database\Seeders;

use App\Models\Membership;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTypeSeeder::class,
            UserSeeder::class,
            MembershipSeeder::class,
            ManagerSeeder::class,
            OrganizationSeeder::class,
            EmployeeSeeder::class,
            StatusSeeder::class,
            TaskSeeder::class,
            RoleAndPermissionSeeder::class,
        ]);
    }
}
