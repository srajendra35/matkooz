<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contactForm(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required',
            'message' => 'required|string',
        ]);

        // Mail::to($request->email)->send(new ContactFormMail($request->all()));

        return response()->json(['message' => 'Form submitted successfully']);
    }
}
