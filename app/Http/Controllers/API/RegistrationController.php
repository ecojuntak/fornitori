<?php

namespace App\Http\Controllers\API;

use App\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\VerifyUser;
use App\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Config;

class RegistrationController extends Controller
{
    public function register(Request $request) {
        $user = new User();

        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->status = $request->role === Config::get('messages.ROLE_CUSTOMER') ?
            Config::get('messages.VERIFIED_STATUS') : "-";
        $user->save();

        $this->createCart($user);
        $this->createVerificationToken($user);

        event(new UserRegisteredEvent($user));

        return response([
            'status' => Config::get('messages.SUCCESS_STATUS'),
            'user' => $user
        ], 200);
    }

    private function createVerificationToken($user) {
        VerifyUser::create([
            'user_id' => $user->id,
            'token' => str_random(40)
        ]);
    }

    private function createCart($user) {
        Cart::create([
            "user_id" => $user->id,
        ]);
    }
}
