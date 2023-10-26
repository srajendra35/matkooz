<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{


    public static function Adminlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]);

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }

            $admin = Auth::guard('admin')->user();
            return response()->json([
                'status' => 'success',
                'admin' => $admin,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => "Admin Not Found"
            ]);
        }
    }

    public static function AdminRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 400);
        }

        $userEmailCheck = Admin::where('email', $request->email)->first();
        if ($userEmailCheck) {
            return response()->json([
                'success' => false,
                'message' => 'Email ' . $request->email . ' is already exists!!',
            ], 409);
        }
        $userPhoneCheck = Admin::where('phone', $request->phone)->first();
        if ($userPhoneCheck) {
            return response()->json([
                'success' => false,
                'message' => 'phone' . $request->phone . ' is already exists!!',
            ], 409);
        }

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = bcrypt($request->password);

        $admin->save();

        $token = JWTAuth::attempt([
            'email' => $request->email,
            'password' => $request->input('password'),
        ]);
        echo 'admin' . $token;
        return response()->json([
            'success' => true,
            'message' => 'Admin Registered successfully.',
            'user' => $admin,
            'access_token' => $token,
        ], 201);
    }

    public static function admin(Request $request)
    {
        $admin = Role::select(
            'id',
            'role_name',
        )->get();

        return response()->json([
            "success" => true,
            "data" => $admin
        ], 200);
    }
}
