<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public static function CreateCategory(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::where('name', $request->name)->exists();
        if ($category) {
            return response()->json([
                'success' => false,
                'message' =>  'Category already exists',
            ], 409);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>  'Error',
                'data' => $validator->errors()
            ], 400);
        }
        Category::create([
            'name' => $request->name,
            'image' => $request->image,
        ]);
        return response()->json(["success" => false, "message" => 'Category has been created successfully'], 201);
    }

    public static function CreateSubCategory(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'nullable|string',
        ]);

        $category = Subcategory::where('name', $request->name)->exists();
        if ($category) {
            return response()->json([
                'success' => false,
                'message' =>  'subCategory already exists',
            ], 409);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>  'Error',
                'data' => $validator->errors()
            ], 400);
        }
        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id
        ]);
        return response()->json(["success" => false, "message" => 'SubCategory has been created successfully'], 201);
    }

    public static function CreateChildcategory(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'nullable|string',
            'subcategory_id' => 'nullable|string'
        ]);

        $category = Childcategory::where('name', $request->name)->exists();
        if ($category) {
            return response()->json([
                'success' => false,
                'message' =>  'ChildCategory already exists',
            ], 409);
        }
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' =>  'Error',
                'data' => $validator->errors()
            ], 400);
        }
        Childcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id
        ]);
        return response()->json(["success" => false, "message" => 'ChilCategory has been created successfully'], 201);
    }

    public static function getSubCategory(Request $request, Category $category)
    {
        $subcategories = Subcategory::where('category_id', '<>', $category->id)->with('childCategory')->get();
        return response()->json([
            'success' => true,
            'message' => 'Subcategory List',
            'data' => $subcategories
        ], 400);
    }

    public static function filter(Request $request, Category $category, Subcategory $subcategory)
    {
        $mark = Category::whereHas('subCtegories', function ($query) use ($request) {
            $query->whereHas('childCategory', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            });
        })->first();
        return response()->json([
            'success' => true,
            'message' => 'Category List By using of ChildCategory List',
            'data' => $mark
        ], 200);
    }

    public function ShowCategoryList()
    {
        $categories = Category::with(['subCtegories', 'subCtegories.childCategory'])->get();
        return response()->json($categories);
    }
}
