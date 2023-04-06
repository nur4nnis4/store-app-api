<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->get();
        return ProductResource::collection($products);
    }

    public function showPopular()
    {
        $popularProducts = Product::where('is_popular', 1)->get();
        return ProductResource::collection($popularProducts);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        if ($request->image_url) {
            $filename = $request->image_url->store('products');
            $request['image'] = $filename;
        }
        $request['seller_id'] = auth()->user()->id;
        $product = Product::create($request->except('image_url'));
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
    }

    public function update(UpdateProductRequest $request, String $id)
    {
        $product = Product::findOrFail($id);

        if ($request->image_url) {
            $extension = $request->image_url->getClientOriginalExtension();
            $filename = $request->image_url->storeAs('products', $product->image_url . '.' . $extension);
            $request['image'] = $filename;
        }
        $product->update($request->except(['id', 'userId', 'image_url']));
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
    }


    public function destroy(String $id)
    {
        $product = Product::findOrFail($id);
        Storage::delete([$product->image_url]);
        $product->delete();
        return response()->json(['message' => 'success']);
    }
}
