<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            "username" => "admin",
            "email" => "uloszone@gmail.com",
            "password" => bcrypt("admin123"),
            "role" => "admin",
            "email_verified_at" => Carbon::now(),
            "status" => Config::get('messages.VERIFIED_STATUS')
        ]);

        $admin->profile()->create([
            'name' => 'Admin Uloszone',
            'address' => '["{\"name\":null,\"province_id\":34,\"city_id\":481,\"subdistrict_id\":\"6657\",\"province_name\":\"Sumatera Utara\",\"city_name\":\"Toba Samosir\",\"subdistrict_name\":\"Laguboti\",\"postal_code\":\"22316\",\"detail\":\"Simpang Empat Laguboti\"}"]',
            'phone' => '+628230448xxxx',
            'gender' => Config::get('messages.GENDER_MALE'),
            'birthday' => Carbon::now(),
        ]);
    }
}
