<?php

namespace App\Http\Controllers\PrivateAPI;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Carousel;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\ImageUtility;

class CarouselController extends Controller
{
    use ImageUtility;

    public function getCarousels() {
        return response()->json([
            "carousels" => Carousel::all()
        ], Config::get('messages.SUCCESS_CODE'));
    }
}
