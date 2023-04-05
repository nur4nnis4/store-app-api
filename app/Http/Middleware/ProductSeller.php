<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;

class ProductSeller
{
    public function handle(Request $request, Closure $next)
    {
        $currentUser = auth()->user();
        $product = Product::findOrFail($request->id);

        if ($currentUser->id != $product->seller_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}
