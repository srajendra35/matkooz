<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Validators;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'c_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 400);
        }

        $userEmailCheck = User::where('email', $request->email)->first();
        if ($userEmailCheck) {
            return response()->json([
                'success' => false,
                'message' => 'Email ' . $request->email . ' is already exists!!',
            ], 409);
        }
        $userPhoneCheck = User::where('phone', $request->phone)->first();
        if ($userPhoneCheck) {
            return response()->json([
                'success' => false,
                'message' => 'phone' . $request->phone . ' is already exists!!',
            ], 409);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = $request->password ? Hash::make($request->password) : null;;
        $user->c_password = bcrypt($request->c_password) ? Hash::make($request->c_password) : null;;
        $user->save();

        if ($request->password != $request->c_password) {
            return response()->json([
                'success' => false,
                'message' => 'Password & C_Password must be same ?',
            ], 201);
        } else {
            $accessToken = $user->createToken('authToken')->accessToken;
            return response()->json([
                'success' => true,
                'message' => 'User Registered successfully.',
                'user' => $user,
                'access_token' => $accessToken,
            ], 201);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "user not found",
            ], 401);
        }

        if ($request->email && isset($user->id)) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('authToken');
                $access_token = $token->accessToken;
                $expire_at = Carbon::parse($token->token->expires_at)->toDateTimeString();

                return response()->json([
                    'success' => true,
                    'data' => array(
                        'accessToken' => $access_token,
                        'expiresAt' => $expire_at,
                    )
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' =>  'Invalid Credentials!',
                ], 401);
            }
        }
    }
}
