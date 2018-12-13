<?php

use Illuminate\Database\Seeder;
use App\Product;
use Carbon\Carbon;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::create([
            'name' => 'Ulos Ragi Hotang',
            'price' => '100000',
            'stock' => '5',
            'description' => 'blabla',
            'category' => 'ATBM',
            'spefication' => '{"dimention":"2m x 90cm","weight":"1"}',
            'image' => '["1544683148Screenshot from 2018-10-24 11-08-35.png"]',
            'color' => 'black',
        ]);

    }
}
