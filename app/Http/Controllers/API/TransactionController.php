<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Profile;
use App\Order;
use App\Payment;
use App\Cart;
use Auth;
use GuzzleHttp\Client;

class TransactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $profile = Profile::where('user_id', $request->all()['customerId'])->first();

        $customerAddress = $request->all()['customerAddress'];
        $shippingAddress = $profile->name . ", " .
                           $profile->phone . "\n" .
                           $customerAddress['detail'] . ", " .
                           $customerAddress['subdistrict_name'] . ", " .
                           $customerAddress['city_name'] . ", " .
                           $customerAddress['province_name'] . " (" .
                           $customerAddress['postal_code'] . ")";
 
        $merchants = $request->all()['merchants'];
        
        foreach($merchants as $merchant) {
            $transaction = Transaction::create([
                'customer_id' => $request->all()['customerId'],
                'merchant_id' => $merchant['id'],
                'address' => $shippingAddress,
                'additional_info' => "",
                'status' => "pending"
            ]);

            $orders = $merchant['products'];
            
            foreach($orders as $order) {
                $o = Order::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $order['productId'],
                    'quantity' => $order['quantity']
                ]);

                if($o) {
                    Cart::find($order['cartId'])->delete();
                }
            }

            Payment::create([
                'transaction_id' => $transaction->id,
                'product_cost' => $merchant['totalProductCost'],
                'shipping_cost' => $merchant['totalShippingCost'],
            ]);
        }

        return response()->json($transaction);
    }

    public function updateTransactionStatus(Request $request, $id) {
        $transaction = Transaction::find($id);
        $transaction->status = $request->status;
        $transaction->update();
    }

    public function getCustomerTransaction($id) {
        $transaction = Transaction::with(['orders', 'orders.product', 'payment'])
                                  ->where('customer_id', $id)
                                  ->get();

        return response()->json($transaction);
    }

    public function getTransaction($userId, $tranId ) {
        $transaction = Transaction::with(['customer', 'customer.profile', 'payment'])
                                  ->where('customer_id', $userId)
                                  ->where('id', $tranId)
                                  ->first();
        return response()->json($transaction);
    }

    public function updateProofOfPayment(Request $request, $id) {
        $image = $request->file('image');
        $imageName = time() . $image->getClientOriginalName();
        $destinationPath = public_path('/images/proof-of-payment');
        $image->move($destinationPath, $imageName);

        $transaction = Transaction::find($id);
        $payment = $transaction->payment;
        $payment->proof = json_encode([
            "image" => $imageName,
            "bank" => $request->bank,
            "senderName" => $request->name,
        ]);
        $payment->status = 'paid';
        $transaction->status = 'acceptedBySystem';

        $payment->update();
        $transaction->update();
    }

    public function getTrackingStatus($id) {
        $transaction = Transaction::with(['orders', 'orders.product.merchant.profile', 'payment'])
                                  ->where('id', $id)->first();

        $tracking = $this->getTracking($transaction->shipping_number);

        return response()->json([
            "transaction" => $transaction,
            "tracking" => json_decode($tracking)
        ]);
    }

    private function getTracking($shippingNumber) {
        $client = new Client([
            'base_uri' => 'https://pro.rajaongkir.com/api/',
            'headers' => [
                "key" => env('RAJAONGKIR_API_KEY'),
                "Content-Type" => "application/x-www-form-urlencoded"
            ]
        ]);

        $payload = [
            "waybill" => $shippingNumber,
            "courier" => "jne"
        ];

        $result = $client->request('POST', 'waybill', ['form_params' => $payload]);
        return $result->getBody()->getContents();
    }

    public function getOrders($status) {
        if($status === 'pending'){
            $status = Transaction::with(['orders', 'merchant', 'customer', 'customer.profile'])->get();
            
            return response()->json([
                'status' => Config::get('messages.NEW_ORDER_MESSAGE')
            ], Config::get('messages.SUCCESS_CODE'));
        } else if($status === 'paid' && $status ==='acceptedBySystem'){
            $status = Transaction::with('orders')->get();

            return response()-json([
                'status' => Config::get('.messages.PAID_ORDER_MESSAGE')
            ], config::get('messages.SUCCESS_CODE'));
        } else if($status === 'acceptedByMerchant'&& $status === 'acceptedByAdmin'){
            $status = Transaction::with('orders')->get();

            return response()-json([
                'status' => Config::get('.messages.UNPAID_ORDER_MESSAGE')
            ], config::get('messages.SUCCESS_CODE'));
        } else if($status === 'orderSuccessed'){
            $status = Transaction::with('orders')->get();

            return response()-json([
                'status' => Config::get('.messages.SUCCESED_ORDER_MESSAGE')
            ], config::get('messages.SUCCESS_CODE'));
        } else if($status === 'invalidProofOfPayment'){
            $status = Transaction::with('orders')->get();

            return response()-json([
                'status' => Config::get('.messages.INVALID_ORDER_MESSAGE')
            ], config::get('messages.SUCCESS_CODE'));
        } else if($status === 'readyForProcess'){
            $status = Transaction::with('orders')->get();

            return response()-json([
                'status' => Config::get('.messages.ON_PROCESS_ORDER_MESSAGE')
            ], config::get('messages.SUCCESS_CODE'));
        }
    }

}
