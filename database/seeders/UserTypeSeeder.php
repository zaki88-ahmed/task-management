<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $types = ['Admin', 'Manager', 'Employee'];
        foreach($types as $type) {
            UserType::create([
                'type' => $type,
            ]);
        }
    }
}
