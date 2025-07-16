<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    // GET /api/v1/products
    public function index(Request $request)
    {
        $products = $this->service->getAll($request->query());
        return response()->json($products);
    }

    // GET /api/v1/products/{id}
    public function show($id)
    {
        $product = $this->service->getById($id);
        return response()->json($product);
    }

    // POST /api/v1/products
    public function store(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());
        return response()->json($product, 201);
    }

    // PUT /api/v1/products/{id}
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());
        return response()->json($product);
    }

    // DELETE /api/v1/products/{id}
    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
