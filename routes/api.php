<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;




//User APIs
Route::post('create-user', [AuthController::class, 'createUser']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

//Auth User APIs
Route::middleware('auth:api')->group(function () {
    Route::get('auth-user', [AuthController::class, 'AuthUser']);
});

///Admin Api 
Route::post('admin/login', [AdminController::class, 'Adminlogin']);
Route::post('admin/register', [AdminController::class, 'AdminRegister']);
Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admin', 'jwt.auth']], function () {
    Route::post('create-category', [CategoryController::class, 'CreateCategory']);
    Route::post('create-subcategory', [CategoryController::class, 'CreateSubcategory']);
    Route::post('create-childcategory', [CategoryController::class, 'CreateChildcategory']);

    Route::get('admin', [AdminController::class, 'me']);
    Route::get('show-subcategory', [CategoryController::class, 'getSubCategory']);
    Route::get('show-category', [CategoryController::class, 'ShowCategoryList']);

    //Category List By Using of childCategory list <---> Filter APIs
    Route::get('filter', [CategoryController::class, 'filter']);
});
