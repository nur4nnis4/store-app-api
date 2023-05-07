<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('API Token')->plainTextToken;

            // Return a response with the user data and API token
            return response()->json([
                'user' => new UserAccount($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } else {
            // Return an error response
            return response()->json(['message' => 'The email or password you entered did not match our records. Please double check and try again'], 401);
        }
    }

    public function googleAuth(Request $request)
    {
        $request->validate([
            'access_token' => 'required',
        ]);

        $user = Socialite::driver('google')->stateless()->userFromToken($request->access_token);


        // Getting or creating user from db
        $userFromDb = User::firstOrCreate(
            ['email' => $user->getEmail()],
            [
                'id' => Str::uuid(),
                'email_verified_at' => Carbon::now(),
                'name' => $user->getName() ?? 'unknown',
                'photo_path' => $user->getAvatar(),
                'password' => Str::random(8),
            ]
        );

        $token = $userFromDb->createToken('API Token')->plainTextToken;

        return response()->json([
            'user' => new UserAccount($userFromDb),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function register(RegisterRequest $request)
    {
        // NOTE: Password hashing is done in User Model
        $request['id'] = Str::uuid();
        if ($request->photo_url) {
            $filename = $request->photo_url->store('users');
            $request['photo_path'] = $filename;
        }
        $user = User::create($request->except('photo_url'));
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json(['user' => new UserAccount($user), 'access_token' => $token, 'token_type' => 'Bearer'], 201);
    }
}
