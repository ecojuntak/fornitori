<?php

namespace App\Http\Controllers\PrivateAPI;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ImageUtility;
use Illuminate\Support\Facades\Config;
use JWTAuth;

class ProductController extends Controller
{
    use ImageUtility;

    private $user;

    public function __construct() {
        if(JWTAuth::getToken()) {
            $this->user = JWTAuth::parseToken()->toUser();
        }
    }

    public function index() {
        return response()->json(Product::all()->orderByDesc('created_at'));
    }

    public function getProductsByMerchant() {
        return response()->json($this->user->products()->orderByDesc('created_at')->get());
    }

    public function storeProduct(Request $request) {
        $imageNames = $request->file('images') !== null ?
            $this->storeMultipleImages($request->file('images'), 'products') : [];

        $this->user->products()->create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category' => $request->category,
            'specification' => json_encode([
                                'dimension' => $request->dimention,
                                'weight' => $request->weight
                                ]),
            'description' => $request->description,
            'color' => $request->color,
            'images' => json_encode($imageNames)
        ]);

        return response()->json([
            'status' => Config::get('messages.PRODUCT_CREATED_MESSAGE')
        ], Config::get('messages.SUCCESS_CODE'));
    }

    public function updateProduct(Request $request, $id) {
        $product = Product::find($id);

        $imageNames = $request->file('images') !== null ?
            $this->storeMultipleImages($request->file('images'), 'products') : $product->images;

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

    public function deleteProduct($id) {
        Product::find($id)->delete();

        return response()->json([
            'status' => Config::get('messages.PRODUCT_DELETED_STATUS')
        ], Config::get('messages.SUCCESS_CODE'));
    }
}
