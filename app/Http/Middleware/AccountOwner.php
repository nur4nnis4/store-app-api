<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountOwner
{

    public function handle(Request $request, Closure $next)
    {
        $user = User::findOrFail($request->id);
        if (Auth::id() != $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
