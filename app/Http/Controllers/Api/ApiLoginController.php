<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LoginResoruce;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request['email'])->first();
        if ($user && Hash::check($request->password, $user->password)) {

            $token = $user->createToken('Atlas token')->plainTextToken;
            return new LoginResoruce([
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
            ]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    // public function deleteLoginToken(Request $request)
    // {
    //     $request->user()->tokens()->delete();
    //     return response()->json(['message' => 'Token deleted successfully'], 200);
    // }
}
