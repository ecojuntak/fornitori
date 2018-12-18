<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Carousel;
use Illuminate\Support\Facades\Config;

class CarouselController extends Controller
{
    public function getCarousels() {
        return response()->json([
            "carousels" => Carousel::all()
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function storeCarousel(Request $request)
    {
        $imageNames = $request->file('images') !== null ?
            $this->storeImages($request->file('images')) : [];
        $carousel = new Carousel();
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->image = json_encode($imageNames);
        $carousel->status = 'nonactive';
        $carousel->save();

        return response()->json([
            'status' => Config::get('messages.CAROUSEL_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateCarousel(Request $request, $id)
    {
        $carousel = Carousel::find($id);
        $imageNames = $request->file('images') !== null ?
            $this->storeImages($request->file('images')) : [];
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->image = json_encode($imageNames);
        $carousel->save();

        return response()->json([
            'status' => Config::get('messages.CAROUSEL_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function deleteCarousel($id)
    {
        Carousel::find($id)->delete();

        return response()->json([
            'status' => Config::get('messages.CAROUSEL_DELETED_STATUS')
        ], Config::get('messages.SUCCESS_CODE'));
    }

}
