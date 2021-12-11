<?php

namespace Database\Seeders;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $org = new Organization();
        $org->name = Str::random(5);
        $org->description = Str::random(15);
//        $org->image = "organization_images/1.jpg";
        $org->status = 'Active';
        $org->manager_id = 1;
        $org->save();
    }
}
