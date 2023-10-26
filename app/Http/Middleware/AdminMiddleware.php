<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $userCheck = User::where('id', Auth::user()->role_id)->first();
        if (isset($userCheck->role_name) && ($userCheck->role_name === 'Admin')) {
            return $next($request);
        } else {
            return response()->json([
                'success' => false,
                'message' =>  'Access denied',
            ], 401);
        }
    }
}
