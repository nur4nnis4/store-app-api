<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => 'The provided credentials are incorrect.']);
        }
        return $user->createToken('user login')->plainTextToken;
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function signup(SignupRequest $request)
    {
        if ($request->image_url) {
            $filename = $request->image_url->store('users');
            $request['photo_path'] = $filename;
        }
        $request['seller_id'] = auth()->user()->id;
        $user = User::create($request->except('image_url'));
    }
}
