<?php

namespace App\Http\Controllers\Admin;

use App\Events\ProductUpdated;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProductController
{
    public function index()
    {
        Gate::authorize('view', 'products');
        $products = Product::paginate();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        Gate::authorize('view', 'products');
        $product = Product::find($id);
        return new ProductResource($product);
    }

    public function store(ProductCreateRequest $request)
    {
        Gate::authorize('edit', 'products');
        $product = Product::create($request->only('title', 'description', 'image', 'price'));

        event(new ProductUpdated());

        return response(new ProductResource($product), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('edit', 'products');
        $product = Product::find($id);
        $product->update($request->only('title', 'description', 'image', 'price'));

        event(new ProductUpdated());

        return response(new ProductResource($product), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        Gate::authorize('edit', 'products');
        Product::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
