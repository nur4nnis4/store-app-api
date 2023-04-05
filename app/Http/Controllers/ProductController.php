<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->get();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $request['seller_id'] = auth()->user()->id;
        $product = Product::create($request->all());
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
    }

    public function update(UpdateProductRequest $request, String $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->except(['id', 'userId']));
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
    }


    public function destroy(String $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'success']);
    }
}
