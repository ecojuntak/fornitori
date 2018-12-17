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

    public function updateProfileAdmin(Request $request){
        $profile = [];
        $imageName = $request->file('photo') !== null ?
            $this->storeSingleImage($request->file('photo'), 'profiles') : [];
        $profile['name'] = $request->name;  
        $profile['phone'] = $request->phone;
        $profile['photo'] = json_encode($imageName);
    
        $this->user->profile()->update($profile);
    
        return response()->json([
            'status' => Config::get('messages.PROFILE_UPDATED_ADMIN')
        ], Config::get('messages.SUCCESS_CODE'));
      }

    public function updatePassword(Request $request){
        JWTAuth::invalidate();
        
        if($this->user->role === 'admin'){
            if($request->password === $request->confirm_password){
            $this->user->password = bcrypt($request->password);
            $this->user->update();
        
            return response()->json([
                'status' => Config::get('messages.PASSWORD_UPDATE_ADMIN')
            ], Config::get('messages.SUCCESS_CODE'));} 
            else {
                return response()->json([
                    'status' => Config::get('messages..PASSWORD_NOTMATCHED_ADMIN')
                ], Config::get('messages.SUCCESS_CODE'));
            }
        }
        else if($this->user->role === 'merchant'){
            if($request->password === $request->confirm_password){
            $this->user->password = bcrypt($request->password);
            $this->user->update();
        
            return response()->json([
                'status' => Config::get('messages.PASSWORD_UPDATE_MERCHANT')
            ], Config::get('messages.SUCCESS_CODE'));} 
            else {
                return response()->json([
                    'status' => Config::get('messages..PASSWORD_NOTMATCHED_MERCHANT')
                ], Config::get('messages.SUCCESS_CODE'));
            }
        }
        else if($this->user->role === 'customer'){
            if($request->password === $request->confirm_password){
            $this->user->password = bcrypt($request->password);
            $this->user->update();
        
            return response()->json([
                'status' => Config::get('messages.PASSWORD_UPDATE_CUSTOMER')
            ], Config::get('messages.SUCCESS_CODE'));} 
            else {
                return response()->json([
                    'status' => Config::get('messages..PASSWORD_NOTMATCHED_CUSTOMER')
                ], Config::get('messages.SUCCESS_CODE'));
            }
        }
      }
}
