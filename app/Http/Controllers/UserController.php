<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserAccount;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

    public function store(SignupRequest $request)
    {
        if ($request->image_url) {
            $filename = $request->image_url->store('users');
            $request['photo_path'] = $filename;
        }
        $request['seller_id'] = auth()->user()->id;
        $user = User::create($request->except('image_url'));
        return response()->json(['message' => 'success', 'data' => new UserResource($user)]);
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
        Storage::delete([$user->image_url]);
        $user->delete();
        return response()->json(['message' => 'success']);
    }
}
