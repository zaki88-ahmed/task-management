<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@gmail.com';
        $user->gender = 'male';
        $user->password = Hash::make(123456);
        $user->user_type = 1;
        $user->phone_no = 201146104262;
        $user->save();

        $user = new User();
        $user->name = 'manager';
        $user->email = 'manager@gmail.com';
        $user->gender = 'male';
        $user->password = Hash::make(123456);
        $user->user_type = 2;
        $user->phone_no = 201146104262;
        $user->save();

        $user = new User();
        $user->name = 'employee';
        $user->email = 'employee@gmail.com';
        $user->gender = 'male';
        $user->password = Hash::make(123456);
        $user->user_type = 3;
        $user->phone_no = 201146104262;
        $user->save();
    }
}
