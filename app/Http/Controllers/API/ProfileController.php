<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Config;
use Auth;
use JWTAuth;
use App\Http\Controllers\ImageUtility;

class ProfileController extends Controller
{
    use ImageUtility;

    private $user;

    public function __construct() {
        $this->user = JWTAuth::parseToken()->toUser();
    }
    

    public function storeProfile(Request $request){
        $address = [];
   
        array_push($address, json_encode([
            'name' => $request->addressName,
            'province_id' => $request->provinceId,
            'city_id' => $request->cityId,
            'subdistrict_id' => $request->subdistrictId,
            'province_name' => $request->provinceName,
            'city_name' => $request->cityName,
            'subdistrict_name' => $request->subdistrictName,
            'postal_code' => $request->postalCode,
            'detail' => $request->addressDetail,
        ]));

        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'profile') : '';
        $profile = new Profile();
        $profile->name = $request->name;
        $profile->phone = $request->phone;
        $profile->photo = $imageName;
        $profile->address = json_encode($address);
        $profile->gender = $request->gender;
        $profile->birthday = $request->birthday;
        $this->user->profile()->save($profile);

        return response()->json([
            'status' => Config::get('messages.PROFILE_USER_CREATED')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateProfileUser(Request $request){
        $profile = [];
        $address = [];
           
        array_push($address, json_encode([
            'name' => $request->addressName,
            'province_id' => $request->provinceId,
            'city_id' => $request->cityId,
            'subdistrict_id' => $request->subdistrictId,
            'province_name' => $request->provinceName,
            'city_name' => $request->cityName,
            'subdistrict_name' => $request->subdistrictName,
            'postal_code' => $request->postalCode,
            'detail' => $request->addressDetail,
        ]));

        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'profile') : '';
        $profile['name'] = $request->name;  
        $profile['phone'] = $request->phone;
        $profile['photo'] = $imageName;  
        $profile['address'] = json_encode($address);
        $profile['gender'] = $request->gender;  
        $profile['birthday'] = $request->birthday;
        $this->user->profile()->update($profile);

        return response()->json([
            'status' => Config::get('messages.PROFILE_USER_UPDATED')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateProfileAdmin(Request $request){
        $profile = [];
        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'profile') : '';
        $profile['name'] = $request->name;  
        $profile['phone'] = $request->phone;
        $profile['photo'] = $imageName;
    
        $this->user->profile()->update($profile);
    
        return response()->json([
            'status' => Config::get('messages.PROFILE_UPDATED_ADMIN')
        ], Config::get('messages.SUCCESS_CODE'));
      }

    public function updatePassword(Request $request){
        if($request->password === $request->confirm_password){
            $this->user->password = bcrypt($request->password);
            $this->user->update();
            JWTAuth::invalidate();
            
            return response()->json([
                'status' => Config::get('messages.PASSWORD_UPDATE_STATUS')
            ], Config::get('messages.SUCCESS_CODE'));
        } else {
            return response()->json([
                'status' => Config::get('messages.PASSWORD_NOTMATCHED_STATUS')
            ], Config::get('messages.SUCCESS_CODE'));
        }
        
    }
}
