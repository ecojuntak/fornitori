<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageUtility;
use App\Banner;
use Illuminate\Support\Facades\Config;

class BannerController extends Controller
{
    use ImageUtility;
    
    private function validate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'link' => 'required',
        ]);
    }

    public function getBanners() {
        return response()->json(Banner::all());
    }

    public function storeBanner(Request $request) {
        $banner->validate($request);

        $imageNames = $request->file('image') !== null ?
            $this->storeImages($request->file('image')) : [];
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->link = $request->link;       
        $banner->image = json_encode($imageNames);
        $banner->save();

        return response()->json([
            'status' => Config::get('messages.BANNER_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateBanner(Request $request, $id) {
        $banner->validate($request);
        $banner = Banner::find($id);
        $imageNames = $request->file('images') !== null ?
            $this->storeImages($request->file('images')) : [];
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->link = $request->link;      
        $banner->image = json_encode($imageNames); 

        $banner->save();

        return response()->json([
            'status' => Config::get('messages.BANNER_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Banner  $banner
     * @return \Illuminate\Http\Response
     */
    public function deleteBanner($id) {
        Banner::find($id)->delete();

        return response()->json([
            'status' => Config::get('messages.BANNER_DELETED_STATUS')
        ], Config::get('messages.SUCCESS_CODE'));
    }
}