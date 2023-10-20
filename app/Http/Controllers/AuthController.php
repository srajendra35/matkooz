<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\userCredential;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public static function createUser(Request $request)
    {
<<<<<<< HEAD

=======
>>>>>>> ea5b8755f48d1c2fc3da46d831978d057682e692
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required'
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
        $user->password = bcrypt($request->password);
<<<<<<< HEAD
        $user->c_password = bcrypt($request->c_password);
        $user->save();

        if ($request->password != $request->c_password) {
            return response()->json([
                'success' => false,
                'message' => 'Password & C_Password must be same ?',
            ], 201);
        } else {

            $token = JWTAuth::attempt([
                'email' => $user->email,
                'password' => $request->input('password'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'User Registered successfully.',
                'user' => $user,
                'access_token' => $token,
            ], 201);
        }
=======
        $user->save();

        $token = JWTAuth::attempt([
            'email' => $user->email,
            'password' => $request->input('password'),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'User Registered successfully.',
            'user' => $user,
            'access_token' => $token,
        ], 201);
>>>>>>> ea5b8755f48d1c2fc3da46d831978d057682e692
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // Authentication successful, return the token and other data
        return response()->json([
            'status' => 'success',
            'user' => JWTAuth::user(),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    public static function forgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $otp = rand(1000, 9999);
        $user = User::where('email', $request->email)->first();
        userCredential::updateOrCreate(
            ['user_id' => $user->id],
            ['otp' => $otp]
        );
        return response()->json(['message' => 'OTP sent to your email address.']);
    }

    public static function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required',
            'password' => 'required|min:6',
        ]);

        $userCredential = UserCredential::where('otp', $request->otp)
            ->whereHas('user', function ($query) use ($request) {
                $query->where('email', $request->email);
            })->first();

        if (!$userCredential) {
            return response()->json(['error' => 'Invalid OTP. Please try again.'], 422);
        }
        $user = $userCredential->user;
        $user->update(['password' => Hash::make($request->password)]);
        $userCredential->delete();
        return response()->json(['message' => 'Password reset successfully.'], 200);
    }

    public static function userList(Request $request)
    {
        $userIdentity = User::where('id', Auth::user()->id)->first();
        if (isset($userIdentity->id)) {
            return response()->json([
                "success" => true,
                "data" => $userIdentity
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "No user exists with this token"
            ], 404);
        }
    }
}
