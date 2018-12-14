<?php

use Illuminate\Database\Seeder;
use App\Carousel;

class CarouselTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Carousel::create([
            'description' => 'blablabla',
            'link' => 'blabla.com',
            'image' => 'no-image.png',
            'status' => 'blabla',
        ]);
    }
}
