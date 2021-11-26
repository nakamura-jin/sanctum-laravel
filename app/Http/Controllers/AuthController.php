<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Dotenv\Exception\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8']
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        if(Auth::attempt($request->only('email', 'password'))) {
            return response()->json(Auth::user(), 200);
        }

        throw ValidationException::withMassages([
            'email' => ['The provided credentials are incorect.']
        ]);
    }

    public function logout()
    {
        Auth::logout();
    }
}
