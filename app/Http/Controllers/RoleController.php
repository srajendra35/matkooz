<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RoleController extends Controller
{
    public static function role(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 400);
        }

        $role = Role::where('role_name', $request->role_name)->first();
        if ($role) {
            return response()->json([
                'success' => false,
                'message' => 'role ' . $request->role_name . ' is already exists!!',
            ], 409);
        }

        $role = new Role();
        $role->role_name = $request->role_name;
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role Create successfully.',
            'role' => $role,
        ], 201);
    }
}
