<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\userCredential;
use App\Notifications\LoginAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public static function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'role_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 400);
        }
        $user = User::exists();


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
        $user->role_id = $request->role_id;

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
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        // $user = User::find(Auth::user()->id);
        // $message = "We have noticed a login from a new device and want to make sure it`s you ";
        // $device = 'iPhone 14 Pro Max';
        // $time = User::select('created_at')->get();
        // $user->notify(new LoginAccount($message, $device, $time));

        $user = Auth::user();
        return response()->json([
            'status' => true,
            'user' => $user,
            'token' => $token,
        ], 200);
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

    public static function AuthUser(Request $request)
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

    public static function Adminlogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = Admin::where('email', $request->email)->first();

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $token = Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password]);

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $admin = Auth::guard('admin')->user();
            return response()->json([
                'status' => true,
                'admin' => $admin->name,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => "Admin Not Found"
            ]);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find(Auth::user()->id);
        $existingName = User::where('first_name', $request->first_name)->where('id', '!=', $user->id)->exists();
        $existingEmail = User::where('email', $request->email)->where('id', '!=', $user->id)->exists();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        } else if ($existingName) {
            return response()->json([
                'error' => 'User already exists.'
            ], 409);
        } else if ($existingEmail) {
            return response()->json([
                "status" => false,
                'error' => 'Email already exists.'
            ], 409);
        } else {
            $user->first_name = $request->input('first_name', $user->first_name);
            $user->email = $request->input('email', $user->email);

            $user->save();
            return response()->json([
                'message' => 'user updated successfully',
                'data' => $user,
            ], 200);
        }
        return response()->json([
            'message' => 'Only Admin can Change user',
        ], 200);
    }
}
