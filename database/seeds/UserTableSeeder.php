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
        $this->createAdmin();
        $this->createMerchant();
        $this->createCustomer();
    }

    private function createCustomer() {
        $customer = User::create([
            "username" => "customer",
            "email" => "ecojuntak@gmail.com",
            "password" => bcrypt("customer123"),
            "role" => "customer",
            "email_verified_at" => Carbon::now(),
            "status" => Config::get('messages.VERIFIED_STATUS')
        ]);

        $customer->cart()->create();

        for($i=1; $i<=2; $i++) {
            $customer->cart->products()->attach([
                $i * 3 => [
                    'quantity' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            ]);
        }

        $customer->profile()->create([
            'name' => 'Customer Uloszone',
            'address' => '["{\"name\":null,\"province_id\":34,\"city_id\":481,\"subdistrict_id\":\"6657\",\"province_name\":\"Sumatera Utara\",\"city_name\":\"Toba Samosir\",\"subdistrict_name\":\"Laguboti\",\"postal_code\":\"22316\",\"detail\":\"Simpang Empat Laguboti\"}"]',
            'phone' => '+628230448xxxx',
            'gender' => Config::get('messages.GENDER_MALE'),
            'birthday' => Carbon::now(),
        ]);
    }

    private function createMerchant() {
        $merchant = User::create([
            "username" => "merchant",
            "email" => "paltigcsinaga@gmail.com",
            "password" => bcrypt("merchant123"),
            "role" => "merchant",
            "email_verified_at" => Carbon::now(),
            "status" => Config::get('messages.VERIFIED_STATUS')
        ]);

        for ($i=0; $i<3; $i++) {
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

        $merchant->profile()->create([
            'name' => 'Merchant Uloszone',
            'address' => '["{\"name\":null,\"province_id\":34,\"city_id\":481,\"subdistrict_id\":\"6657\",\"province_name\":\"Sumatera Utara\",\"city_name\":\"Toba Samosir\",\"subdistrict_name\":\"Laguboti\",\"postal_code\":\"22316\",\"detail\":\"Simpang Empat Laguboti\"}"]',
            'phone' => '+628230448xxxx',
            'gender' => Config::get('messages.GENDER_MALE'),
            'birthday' => Carbon::now(),
        ]);

        $merchant = User::create([
            "username" => "merchantdua",
            "email" => "edwardsaragih97@gmail.com",
            "password" => bcrypt("merchantdua123"),
            "role" => "merchant",
            "email_verified_at" => Carbon::now(),
            "status" => Config::get('messages.VERIFIED_STATUS')
        ]);

        for ($i=0; $i<3; $i++) {
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

    private function createAdmin() {
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
