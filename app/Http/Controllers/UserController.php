<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserAccount;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserDetailResource($user->loadMissing('products'));
    }

    public function getUserAccount($id)
    {
        $user = User::findOrFail($id);
        return new UserAccount($user);
    }

    public function update(UpdateUserRequest $request, String $id)
    {
        $user = User::findOrFail($id);
        if ($request->photo_url) {
            if ($user->photo_path)
                Storage::delete($user->photo_path);
            $filename = $request->photo_url->store('users');
            $request['photo_path'] = $filename;
        }
        $user->update($request->except(['id', 'email', 'password']));
        return response()->json(['message' => 'success', 'data' => new UserAccount($user)]);
    }


    public function destroy(String $id)
    {
        $user = User::findOrFail($id);
        if ($user->photo_path && Storage::exists($user->photo_path)) Storage::delete([$user->photo_path]);
        $user->delete();
        return response()->json(['message' => 'success']);
    }
}
