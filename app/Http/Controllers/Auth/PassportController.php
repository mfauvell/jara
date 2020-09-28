<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!Auth::attempt($loginData)){
            return response(['message' => 'Invalid Credentials'], 401);
        }
        /** @var /App/Models/User */
        $user = Auth::user();
        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response(['message' => 'You have been successfully logged out'], 200);
    }
}
