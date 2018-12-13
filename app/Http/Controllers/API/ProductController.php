<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageUtility;
use Illuminate\Support\Facades\Config;

class ProductController extends Controller
{
    use ImageUtility;

    public function index() {
        return response()->json(Product::all());
    }

    public function getProducts($id) {
        return response()->json(Product::where('user_id', $id)->get());
    }

    public function storeProduct(Request $request, $merchantId) {
        $imageNames = $request->file('images') !== null ?
            $this->storeImages($request->file('images')) : [];

        $product = new Product();
        $product->user_id = $merchantId;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->category = $request->category;
        $product->specification = json_encode([
            'dimention' => $request->dimention,
            'weight' => $request->weight
        ]);
        $product->description = $request->description;
        $product->color = $request->color;
        $product->images = json_encode($imageNames);
        $product->save();

        return response()->json([
            'status' => Config::get('messages.PRODUCT_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateProduct(Request $request, $id) {
        $product = Product::find($id);
        $imageNames = $request->file('images') !== null ?
            json_encode($this->storeImages($request->file('images'))) : $product->images;

        $product->name = $request->name;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->specification = json_encode([
            'dimention' => $request->dimention,
            'weight' => $request->weight
        ]);
        $product->description = $request->description;
        $product->color = $request->color;
        $product->images = $imageNames;
        $product->save();

        return response()->json([
            'status' => Config::get('messages.PRODUCT_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function searchProduct(Request $request) {
        $products = Product::with('merchant.profile')
                           ->where('name', 'LIKE', '%'. $request->keyword .'%')
                           ->get();

        return response()->json($products);
    }

}
