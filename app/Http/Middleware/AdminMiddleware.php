<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // $userCheck = Admin::first();
        // if (isset($userCheck->name) && ($userCheck->name === 'jitendra')) {
        //     return $next($request);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' =>  'Access denied',
        //     ], 401);
        // }
        if (Auth::guard('admin')->check()) {
            return $next($request);
        } else {
            $message = ["message" => "Permission Denied"];
            return response($message, 401);
        }
    }
}
