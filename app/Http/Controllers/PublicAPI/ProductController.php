<?php

namespace App\Http\Controllers\PublicAPI;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageUtility;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class ProductController extends Controller
{
    use ImageUtility;


    public function getProduct($id) {
        $product = Product::with('merchant', 'reviews.reviewer')->find($id);
        $product = $this->decodeSerializedData($product);

        return response()->json([
            'product' => $product
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function searchProduct(Request $request) {
        $products = Product::with('merchant.profile')
                           ->where('name', 'LIKE', '%'. $request->keyword .'%')
                           ->orderByDesc('created_at')
                           ->get();

        return response()->json($products);
    }

    public function getNewProducts() {
        $products = Product::with('merchant')->inRandomOrder()->limit(15)->get();
        $products = $this->decodeSerializedData($products);

        return response()->json([
            'products' => $products
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function getAllProducts() {
        $products = Product::with('merchant')->inRandomOrder()->get();
        $products = $this->decodeSerializedData($products);

        return response()->json([
            'products' => $products
        ], Config::get('messages.SUCCESS_CODE'));
    }

    private function decodeSerializedData($products) {
        if(is_iterable($products)) {
            foreach ($products as $product) {
                $product->decodeSerializedData();
            }
        } else {
            $products->decodeSerializedData();
        }

        return $products;
    }

}
