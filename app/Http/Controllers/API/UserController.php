<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Support\Facades\Config;

class UserController extends Controller {
    public function user() {
        $user = User::find(Auth::user()->id);

        return response([
            'status' => Config::get("messages.SUCCESS_STATUS"),
            'user' => $user
        ]);
    }
}
