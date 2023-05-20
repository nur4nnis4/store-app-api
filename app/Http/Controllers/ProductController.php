<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    private function extractOptionsFromRequest(Request $request)
    {
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);
        $orderBy = $request->query('orderBy', 'created_at');
        $sort = $request->query('sort', 'desc');

        return [$limit, $page, $orderBy, $sort];
    }

    public function index(Request $request)
    {

        [$limit, $page, $orderBy, $sort] = $this->extractOptionsFromRequest($request);

        $products = Product::orderBy($orderBy, $sort)
            ->paginate($limit, ['*'], 'page', $page);
        return ProductResource::collection($products->loadMissing('seller:id,name,photo_path,address'));
    }

    public function showPopular(Request $request)
    {
        [$limit, $page, $orderBy, $sort] = $this->extractOptionsFromRequest($request);

        $popularProducts = Product::where('is_popular', 1)->orderBy($orderBy, $sort)
            ->paginate($limit, ['*'], 'page', $page);;
        return ProductResource::collection($popularProducts->loadMissing('seller:id,name,photo_path,address'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product->loadMissing('seller:id,name,photo_path,address'));
    }

    public function search(Request $request, $keyword)
    {
        [$limit, $page, $orderBy, $sort] = $this->extractOptionsFromRequest($request);

        $products = Product::where('name', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%")
            ->orderBy($orderBy, $sort)->paginate($limit, ['*'], 'page', $page);
        if ($products) {
            return ProductResource::collection($products->loadMissing('seller:id,name,photo_path,address'));
        }
    }

    public function getByCategory(Request $request, $category)
    {
        [$limit, $page, $orderBy, $sort] = $this->extractOptionsFromRequest($request);

        $products = Product::where('category', 'like', "%$category%")->orderBy($orderBy, $sort)
            ->paginate($limit, ['*'], 'page', $page);
        if ($products) {
            return ProductResource::collection($products->loadMissing('seller:id,name,photo_path,address'));
        }
    }

    public function store(StoreProductRequest $request)
    {
        $request['id'] = Str::uuid();
        if ($request->image_url) {
            $filename = $request->image_url->store('products');
            $request['image'] = $filename;
        }
        $request['seller_id'] = auth()->user()->id;
        $product = Product::create($request->except('image_url'));
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)], 201);
    }

    public function update(UpdateProductRequest $request, String $id)
    {
        $product = Product::findOrFail($id);

        if ($request->image_url) {
            if ($product->image)
                Storage::delete($product->image);
            $filename = $request->image_url->store('products');
            $request['image'] = $filename;
        }
        $product->update($request->except(['id', 'userId', 'image_url']));
        return response()->json(['message' => 'success', 'data' => new ProductResource($product)]);
    }


    public function destroy(String $id)
    {
        $product = Product::findOrFail($id);
        Storage::delete([$product->image]);
        $product->delete();
        return response()->json(['message' => 'success']);
    }
}
