<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Carousel;

class CarouselController extends Controller
{
    public function getCarousels() {
        return response()->json(Carousel::all());
    }

    public function store(Request $request)
    {
        $image = $request->file('image');
        $imageName = time() . $image->getClientOriginalName();
        $destinationPath = public_path('/images/carousels');
        $image->move($destinationPath, $imageName);

        $carousel = new Carousel();
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->image = $imageName;
        $carousel->status = 'nonactive';
        $carousel->save();

        return redirect('/carousels');
    }

    public function update(Request $request, $id)
    {

        $carousel = Carousel::find($id);
        $imageNames = $request->file('images') !== null ?
            $this->storeImages($request->file('images')) : [];
        $carousel->link = $request->link;
        $carousel->description = $request->description;
        $carousel->save();

        return response()->json([
            'status' => Config::get('messages.BANNER_CREATED_STATUS')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function deleteCarousel($id)
    {
        Carousel::find($id)->delete();

        return response()->json([
            'status' => Config::get('messages.BANNER_DELETED_STATUS')
        ], Config::get('messages.SUCCESS_CODE'));
    }

}
