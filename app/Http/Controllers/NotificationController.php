<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Notifications\BirthdayWish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;

class NotificationController extends Controller
{
    public static function index(Request $request)
    {
        $request->validate([
            'messages' => 'required|string',
        ]);
        $user = User::find(Auth::user()->id);
        $message = $request->messages;
        $name = User::select('first_name')->get();
        $user->notify(new BirthdayWish($message, $name));

        return response()->json([
            'success' => true,
            'message' => $message,
            // 'user' => $user
        ], 200);
    }
}
