<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;
use Illuminate\Support\Facades\Config;

class CartController extends Controller
{
    public function getProductInCartByCustomer($id) {
        return response()->json([
            "cart" => Cart::with('products')->where('user_id', $id)->orderByDesc('created_at')->first(),
            "user" => User::find($id)
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function insertProductToCart(Request $request, $id) {
        $cart = Cart::where('product_id', $request->product_id)->where('user_id', $id)->first();

        if($cart !== null) {
            $cart->quantity = $request->quantity;
            $cart->update();
            $status = Config::get('messages.CART_UPDATED_MESSAGE');
        } else {
            Cart::create([
                'user_id' => $id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
            $status = Config::get('messages.CART_CREATED_MESSAGE');
        }

        return response()->json([
            'status' => $status
        ], Config::get('messages.SUCCESS_CODE'));
    }
}
