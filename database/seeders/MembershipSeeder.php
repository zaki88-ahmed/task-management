<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mem = new Membership();
        $mem->price = 100;
        $mem->description = Str::random(50);
        $mem->duration = Carbon::now();
        $mem->status = Arr::isAssoc([0,1]);
        $mem->save();
    }
}
