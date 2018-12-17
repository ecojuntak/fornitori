<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Config;
use Auth;

class ProfileController extends Controller
{
    private function getAuthincatedUser() {
        $user = User::with('profile')->find(Auth::user()->id);
        $address = json_decode(json_decode($user->profile->address)[0]);
        $user->profile->address = $address;
    
        return $user;
    }

    private function updatePassword(){
        $user = $this->getAuthincatedUser();
    
        if($request->password === $request->confirm_password){
          $user->password = bcrypt($request->password);
          $user->update();
    
          return response()->json([
            'status' => Config::get('messages.PASSWORD_UPDATE_MATCHED')
        ], Config::get('messages.SUCCESS_CODE'));} 
        else {
            return response()->json([
                'status' => Config::get('messages.PASSWORD_UPDATE_NONMATCHED')
            ], Config::get('messages.SUCCESS_CODE'));
        }
    
      }

    public function updateProfileAdmin(Request $request){
        $user = $this->getAuthincatedUser(); 
        $profile = [];
        $imageName = $request->file('photo') !== null ?
        $this->storeImages($request->file('photo')) : [];
        $profile['name'] = $request->name;  
        $profile['phone'] = $request->phone;
        $profile['photo'] = json_encode($imageName);
    
        $user->profile()->update($profile);
    
        return response()->json([
            'status' => Config::get('messages.PROFILE_UPDATED_ADMIN')
        ], Config::get('messages.SUCCESS_CODE'));
      }

      public function updatePasswordAdmin(Request $request){
        $user = $this->getAuthincatedUser();
    
        if($request->password === $request->confirm_password){
          $user->password = bcrypt($request->password);
          $user->update();
    
          return response()->json([
            'status' => Config::get('messages.PASSWORD_UPDATE_ADMIN')
        ], Config::get('messages.SUCCESS_CODE'));} 
        else {
            return response()->json([
                'status' => Config::get('messages.PASSWORD_NOTMATCHED_ADMIN')
            ], Config::get('messages.SUCCESS_CODE'));
        }
    
      }

      public function updateProfileMerchant(Request $request){
        $user = $this->getAuthincatedUser(); 

        $profile = [];
        $imageName = $request->file('photo') !== null ?
        $this->storeImages($request->file('photo')) : [];
        $profile['name'] = $request->name;  
        $profile['phone'] = $request->phone;
        $profile['photo'] = json_encode($imageName);

        if($request->password === $request->confirm_password){
            $user->password = bcrypt($request->password);
            $user->update();
      
            return response()->json([
              'status' => Config::get('messages.PASSWORD_UPDATE_ADMIN')
          ], Config::get('messages.SUCCESS_CODE'));} 
          else {
              return response()->json([
                  'status' => Config::get('messages.PASSWORD_NOTMATCHED_ADMIN')
              ], Config::get('messages.SUCCESS_CODE'));
          }
        
        $user->profile()->update($profile);
    
        return response()->json([
            'status' => Config::get('messages.PROFILE_UPDATED_ADMIN')
        ], Config::get('messages.SUCCESS_CODE'));
      }
}
