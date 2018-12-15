<?php

use Illuminate\Database\Seeder;
use App\Banner;

class BannerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banner::create([
            'title' => 'Blabla',
            'description' => 'blablabla',
            'link' => 'blabla.com',
            'image' => 'no-image.png',
        ]);
    }
}
