<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public static function Product(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'string',
            'user_id' => 'integer',
            'category_id' => 'required|string',
            'price' => 'required|numeric',
            'weight' => 'integer',
            'weight_unit' => 'string',
            'offer_id' => 'string',
        ]);

        $category = Product::where('name', $request->name)->exists();
        if ($category) {
            return response()->json([
                'success' => false,
                'message' =>  'Product already exists',
            ], 409);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>  'Error',
                'data' => $validator->errors()
            ], 400);
        }
        Product::create([
            'name' => $request->name,
            'image' => $request->image,
            'description' => $request->description,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'weight' => $request->weight,
            'weight_unit' => $request->weight_unit,
            'offer_id' => $request->offer_id,
        ]);
        return response()->json(["success" => true, "message" => 'Product has been created successfully'], 201);
    }
}
