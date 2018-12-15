<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class OrderController extends Controller
{
    private $user;

    public function __construct() {
        $this->user = JWTAuth::parseToken()->toUser();
    }

    public function createCustomerOrder() {
        if($this->isCartEmpty()) {
            return response()->json([
                'status' => Config::get('messages.CART_EMPTY')
                ], Config::get('messages.SUCCESS_CODE')
            );
        }

        $merchantProducts = $this->user->cart->products()->pluck('products.user_id', 'products.id')->toArray();

        $groupedProducts = $this->groupProductByMerchant($merchantProducts);

        foreach ($groupedProducts as $merchantId => $merchantProductIds) {
            $order = $this->user->orders()->create();

            $orderDetails = CartDetail::whereIn('product_id', $merchantProductIds)->get();

            foreach ($orderDetails as $detail) {
                $order->details()->create([
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                ]);
            }
        }

        $this->clearCustomerCart();

        return response()->json([
            'status' => Config::get('messages.ORDER_CREATED')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function getCustomerOrder() {
        return response()->json([
            'orders' => $this->user->orders()->with('details.product')->get(),
            'user' => $this->user
        ], Config::get('messages.SUCCESS_CODE'));
    }

    private function isCartEmpty() {
        return $this->user->cart->details()->get()->count() === 0 ? true : false;
    }

    private function clearCustomerCart() {
        $details = $this->user->cart->details()->get();
        foreach ($details as $detail) {
            $detail->delete();
        }
    }

    private function groupProductByMerchant($merchantProducts) {
        $groupedProducts = [];

        foreach ($merchantProducts as $productId => $merchantId) {
            if(array_key_exists($merchantId, $groupedProducts)) {
                array_push($groupedProducts[$merchantId], $productId);
            } else {
                $groupedProducts[$merchantId] = [];
                array_push($groupedProducts[$merchantId], $productId);
            }
        }

        return $groupedProducts;
    }
}
