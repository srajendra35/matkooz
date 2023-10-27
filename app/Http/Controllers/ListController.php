<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public static function getSubCategory(Request $request, Category $category)
    {
        $subcategories = Subcategory::where('category_id', '<>', $category->id)->with('childCategory')->get();
        return response()->json([
            'success' => true,
            'message' => 'Subcategory List',
            'data' => $subcategories
        ], 200);
    }

    public function ShowCategoryList()
    {
        $categories = Category::with(['subCtegories', 'subCtegories.childCategory'])->get();
        return response()->json($categories);
    }

    //get category list using of child category
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

    // public function UserListOrderd(Request $request)
    // {
    //     // $product = User::with(['product', 'product.offer'])->get();
    //     // return response()->json($product);

    //     $user = User::whereHas('product', function ($query) use ($request) {
    //         $query->whereHas('offer', function ($query) use ($request) {
    //             $query->get();
    //         });
    //     })->first();
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'User List They can Use Offer ',
    //         'data' => $user
    //     ], 200);
    // }
}
