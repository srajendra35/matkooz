<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleController;
use App\Models\Contact;
use Illuminate\Support\Facades\Route;




//User APIs
Route::post('register-user', [AuthController::class, 'registerUser']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget-password', [AuthController::class, 'forgetPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

//Auth User APIs
Route::middleware('auth:api')->group(function () {
    Route::get('auth-user', [AuthController::class, 'AuthUser']);
    Route::post('update/user/{id}', [AuthController::class, 'UpdateUser']);
});


///Admin Api 
Route::post('admin/login', [AuthController::class, 'Adminlogin']);
Route::post('admin/register', [AdminController::class, 'AdminRegister']);
Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admin', 'jwt.auth']], function () {
    Route::post('create-category', [CategoryController::class, 'CreateCategory']);
    Route::post('create-subcategory', [CategoryController::class, 'CreateSubcategory']);
    Route::post('create-childcategory', [CategoryController::class, 'CreateChildcategory']);
    Route::patch('update-category/{id}', [AdminController::class, 'updateCategory']);

    Route::post('create-notification', [NotificationController::class, 'index']);
    Route::post('offer', [AdminController::class, 'Offer']);
    Route::get('get-offer/{id}', [AdminController::class, 'getOffer']);
    //filter user list by using offer name
    Route::get('user-filter-offer', [AdminController::class, 'UserListFilterByOfferName']);
    Route::post('product', [ProductController::class, 'Product']);
});

Route::get('show-subcategory', [ListController::class, 'getSubCategory']);
Route::get('show-category', [ListController::class, 'ShowCategoryList']);
Route::get('filter', [ListController::class, 'filter']);

Route::post('contact-form', [ContactController::class, 'contactForm']);
Route::get('role_list', [ListController::class, 'lists']);
