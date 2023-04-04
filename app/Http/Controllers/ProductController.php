<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest()->get();
        // return response()->json(['message' => 'success', 'data' => $products]);
        return ProductResource::collection($products);
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => "required|max:36|unique(product)",
            'name' => "required|max:100",
            'price' => "required|numeric",
            'brand' => "required|max:50",
            'category' => "required|max:50",
            'description' => "required",
            'image_url' => "required|url",
            'is_popular' => "required|boolean",
            'quantity' => "required|numeric",
            'sales' => "required|numeric",
            'user_id' => "required|max:36",
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Fail', 'data' => $validator->errors()]);
        } else {
            $products = Product::create($request->all());
            return response()->json(['message' => 'success', 'data' => new ProductResource($products)]);
        }
    }


    public function show($id)
    {
        $product = Product::find($id);
        return new ProductResource($product);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => "max:100",
            'price' => "numeric",
            'brand' => "max:50",
            'category' => "max:50",
            'image_url' => "url",
            'is_popular' => "boolean",
            'quantity' => "numeric",
            'sales' => "numeric",
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Fail', 'data' => $validator->errors()]);
        } else {
            $product->update($request->except(['id', 'user_id']));
            return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
        }
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'success']);
    }
}
