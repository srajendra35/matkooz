<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignGuard
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        } else {
            $message = ["message" => "Access denied!! 👽"];
            return response($message, 401);
        }
    }
}
