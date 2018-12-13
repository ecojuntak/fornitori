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

        $merchant = User::create([
            "username" => "merchant",
            "email" => "merchant@uloszone.com",
            "password" => bcrypt("merchant123"),
            "role" => "merchant",
            "email_verified_at" => Carbon::now(),
            "status" => Config::get('messages.VERIFIED_STATUS')
        ]);

        for ($i=0; $i<5; $i++) {
            $merchant->products()->create([
                'name' => 'Ulos Ragi Hotang',
                'price' => '100000',
                'stock' => '5',
                'description' => 'Ulosnya bagus',
                'category' => 'ATBM',
                'specification' => '{"dimention":"2m x 90cm","weight":"1"}',
                'images' => '["no-image.png"]',
                'color' => 'black',
            ]);
        }
    }
}
