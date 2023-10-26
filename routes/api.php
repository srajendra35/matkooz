<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('create-user', [AuthController::class, 'createUser']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);





///Admin Api 
Route::post('admin/login', [AdminController::class, 'Adminlogin']);
Route::post('admin/register', [AdminController::class, 'AdminRegister']);


Route::get('productListing', [AuthController::class, 'ProductListing']);

Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admin', 'jwt.auth']], function () {
    Route::get('admin', [AdminController::class, 'me']);
    Route::post('create-category', [CategoryController::class, 'CreateCategory']);
    Route::post('create-subcategory', [CategoryController::class, 'CreateSubcategory']);
    Route::post('create-childcategory', [CategoryController::class, 'CreateChildcategory']);

    Route::get('show-subcategory', [CategoryController::class, 'getSubCategory']);
    Route::get('show-category', [CategoryController::class, 'ShowCategoryList']);

    //Category List By Using of childCategory list
    Route::get('filter', [CategoryController::class, 'filter']);
});


// Route::middleware('auth:api')->group(function () {
//     Route::patch('update-user', [AuthController::class, 'update']);
// });
