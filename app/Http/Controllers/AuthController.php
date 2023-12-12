<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vender;
use Carbon\Carbon;
use App\Models\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $name = Auth::user()->name;
            $id = Auth::user()->id;
            $email = Auth::user()->email;
            $user = auth()->user();
            $token = $user->createToken($name)->plainTextToken;
            Auth::login($user);
            return response()->json([
                'Data' => [
                'access_token' => $token,
                'name' => $name,
                'email' => $email
                ],
                'Status' => 200
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
