<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;

class CartController extends Controller
{
    public function getProductInCartByCustomer($id) {
        return response()->json(Cart::where('user_id', $id)->orderByDesc('created_at')->get());
    }
}
