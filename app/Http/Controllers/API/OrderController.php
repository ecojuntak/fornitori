<?php

namespace App\Http\Controllers\API;

use App\CartDetail;
use App\Http\Controllers\ImageUtility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class OrderController extends Controller
{
    use ImageUtility;

    private $user;

    public function __construct() {
        $this->user = JWTAuth::parseToken()->toUser();
    }

    public function createCustomerOrder(Request $request) {
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
                $order->detail()->create([
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                ]);
            }
        }

        $this->createOrderShipping($request->address);
        $this->createOrderPayment($request->shippingCost);

        $this->clearCustomerCart();

        return response()->json([
            'status' => Config::get('messages.ORDER_CREATED')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function getCustomerOrder() {
        return response()->json([
            'orders' => $this->user->orders()->with('products')->get(),
            'user' => $this->user
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function getSingleOrder($id) {
        return response()->json([
            'order' => $this->user->orders()->with('products')->find($id)
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function uploadProofOfPayment(Request $request, $id) {
        $imageName = $this->storeSingleImage($request->file('image'), 'proof-of-payments');

        $payment = $this->user->orders()->find($id)->payment()->first();

        $payment->proof_of_payment = json_encode([
            'image' => $imageName,
            'bank' => $request->bank,
            'sender_name' => $request->sender_name
        ]);
        $payment->update();

        return response()->json([
            'status' => Config::get('messages.PROOF_OF_PAYMENT_UPLOADED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    private function createOrderPayment($shippingCost) {
        $orders = $this->user->orders()->get();

        foreach ($orders as $order) {
            $products = $order->products()->get();
            $totalProductCost = $this->countTotalProductstoreImageCost($products);

            $order->payment()->create([
                'product_cost' => $totalProductCost,
                'shipping_cost' => $shippingCost,
            ]);
        }
    }

    private function createOrderShipping($address) {
        $orders = $this->user->orders()->get();

        foreach ($orders as $order) {
           $order->shipping()->create([
                'address' => $address
           ]);
        }
    }

    private function countTotalProductCost($products) {
        $totalCost = 0;

        foreach ($products as $product) {
            $totalCost += $product->price;
        }

        return $totalCost;
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
