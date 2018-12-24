<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use JWTAuth;
use Config;

class AuthController extends Controller {

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => Config::get('messages.ERROR_STATUS'),
                'message' => Config::get('messages.INVALID_CREDENTIAL_MESSAGE')
            ], 400);
        }

        $user = User::where('email', $request->email)
                    ->first();

        return response([
            'status' => Config::get('messages.SUCCESS_STATUS'),
            'message' => Config::get('messages.LOGIN_SUCCESS_MESSAGE'),
            'token' => $token,
            'user' => $user,
        ])->header('Authorization', $token);
    }

    //TODO
    public function refresh() {
        return response([
            'status' => Config::get('messages.SUCCESS_STATUS')
        ]);
    }

    public function logout() {
        JWTAuth::invalidate();

        return response([
            'status' => Config::get('messages.SUCCESS_STATUS'),
            'message' => Config::get('messages.LOGOUT_MESSAGE')
        ], 200);
    }
}
