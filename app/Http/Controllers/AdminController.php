<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        $existingName = Category::where('name', $request->name)->where('id', '!=', $category->id)->exists();


        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        } else if ($existingName) {
            return response()->json([
                'error' => 'Category already exixsts.'
            ], 409);
        } else {
            $category->name = $request->input('name', $category->name);
            $category->save();
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category,
            ], 200);
        }
        return response()->json([
            'message' => 'Only Admin can Change Category',
        ], 200);
    }

    public static function Offer(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'string',
            'offer' => 'integer|required',
        ]);
        $category = Offer::where('name', $request->name)->exists();
        if ($category) {
            return response()->json([
                'success' => false,
                'message' =>  'Offer already exists',
            ], 409);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>  'Error',
                'data' => $validator->errors()
            ], 400);
        }
        Offer::create([
            'name' => $request->name,
            'offer' => $request->offer,
            'description' => $request->description,
        ]);
        return response()->json(["success" => false, "message" => 'Offer has been created successfully'], 201);
    }

    public function getoffer($id)
    {
        $offer = Offer::get()
            ->where('id', $id)->first();
        if (isset($offer->id)) {
            return response()->json(['success' => true, 'data' => $offer], 200);
        } else {
            return response()->json(['success' => false, "message" => "offer not found"], 404);
        }
    }

    public function UserListFilterByOfferName(Request $request)
    {
        $user = User::with(['product', 'product.offer'])->whereHas('product', function ($query) use ($request) {
            $query->whereHas('offer', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');;
            });
        })->get();
        return response()->json([
            'success' => true,
            'message' => 'User List by Using Offer name',
            'data' => $user
        ], 200);
    }
}
