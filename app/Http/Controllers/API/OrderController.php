<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;

class OrderController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->toUser();
    }

    public function createCustomerOrder() {
       $product = $this->user->cart->products()->get();

       return response()->json($product);
    }
}
