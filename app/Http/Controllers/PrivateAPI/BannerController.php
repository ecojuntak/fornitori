<?php

namespace App\Http\Controllers\PrivateAPI;

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

        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'banner') : '';
        $banner = new Banner();
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->link = $request->link;       
        $banner->image = $imageName;
        $banner->status = Config::get('messages.STATUS_BANNER_NONACTIVATED');
        $banner->save();

        return response()->json([
            'status' => Config::get('messages.BANNER_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateBanner(Request $request, $id) {
        $banner->validate($request);
        $banner = Banner::find($id);
        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'banner') : '';
        $banner->title = $request->title;
        $banner->description = $request->description;
        $banner->link = $request->link;      
        $banner->image = $imageName; 

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