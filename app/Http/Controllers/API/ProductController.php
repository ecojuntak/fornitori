<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Product::all();
    }

    public function store(Request $request)
    {
        $uploadedImages = $request->file('images');
        $imageNames = [];

        foreach ($uploadedImages as $image) {
            $imageName = time() . $image->getClientOriginalName();

            array_push($imageNames, $imageName);

            $destinationPath = public_path('/images');
            $image->move($destinationPath, $imageName);
        }

        $product = new Product();
        $product->user_id = Auth::user()->id;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->sold = 0;
        $product->category = $request->category;
        $product->specification = json_encode([
            'dimention' => $request->dimention,
            'weight' => $request->weight
        ]);
        $product->description = $request->description;
        $product->color = $request->color;
        $product->images = json_encode($imageNames);
        $success=$product->save();

        if(!$success){
            return Response::json("error solving", 500);
        }
            return Respnse::json("success", 201);
    }
    public function getProducts($id) {
        return response()->json(Product::where('user_id', $id)->get());
    }

    public function searchProduct(Request $request) {
        $products = Product::with('merchant.profile')->where('name', 'LIKE', '%'. $request->keyword .'%')->get();
        return response()->json($products);
    }

}
