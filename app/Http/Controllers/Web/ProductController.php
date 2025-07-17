<?php

namespace App\Http\Controllers\Web;

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

    public function index(Request $request)
    {
        $products = $this->service->getAll($request->query());
        return response()->json($products);
    }

    public function show($id)
    {
        $product = $this->service->getById($id);
        return response()->json($product);
    }

    public function store(ProductRequest $request)
    {
        $product = $this->service->create($request->validated());
        return response()->json($product, 201);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->service->update($product, $request->validated());
        return response()->json($product);
    }

    public function destroy(Product $product)
    {
        $this->service->delete($product);
        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
