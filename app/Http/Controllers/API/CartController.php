<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class CartController extends Controller
{
    private $user;

    public function __construct() {
        $this->user = JWTAuth::parseToken()->toUser();
    }

    public function getProductInCart() {
        return response()->json([
            "cart" => Cart::with('products')->where('user_id', $this->user->id)->orderByDesc('created_at')->first(),
            "user" => $this->user
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function insertProductToCart(Request $request) {
        $productInCart = $this->user->cart->details()->where('product_id', $request->product_id)->first();

        if($productInCart !== null) {
            $productInCart->quantity = $request->quantity;
            $productInCart->update();
            $status = Config::get('messages.CART_UPDATED_MESSAGE');
        } else {
            $this->user->cart->details()->create([
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
