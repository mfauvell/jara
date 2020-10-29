<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PassportController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($loginData)){
            Log::info('A failed login attempt has occurred', ['email' => $loginData['email']]);
            return response(['message' => 'Invalid Credentials'], 401);
        }
        /** @var /App/Models/User */
        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;
        Log::info('Successful Login', ['email' => $loginData['email']]);

        return response(['user' => $user, 'access_token' => $accessToken], 200);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->token()->revoke();

        Log::info('Successful Logout', ['email' => $user->email]);

        return response(['message' => 'You have been successfully logged out'], 200);
    }
}
