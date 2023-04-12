<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('API Token')->plainTextToken;

            // Return a response with the user data and API token
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } else {
            // Return an error response
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function register(RegisterRequest $request)
    {
        // NOTE: Password hashing is done in User Model

        if ($request->photo_url) {
            $filename = $request->photo_url->store('users');
            $request['photo_path'] = $filename;
        }
        $user = User::create($request->except('photo_url'));
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json(['user' => new UserAccount($user), 'token' => $token, 'token_type' => 'Bearer'], 201);
    }
}
