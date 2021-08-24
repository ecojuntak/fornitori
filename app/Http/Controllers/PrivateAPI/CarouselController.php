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

    public function storeCarousel(Request $request)
    {
        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'carousel') : '';
        $carousel = new Carousel();
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->image = $imageName;
        $carousel->status = 'nonactive';
        $carousel->save();

        return response()->json([
            'status' => Config::get('messages.CAROUSEL_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateCarousel(Request $request, $id)
    {
        $carousel = Carousel::find($id);
        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'carousel') : '';
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->image = $imageName;
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
