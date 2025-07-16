<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getAll($params)
    {
        return $this->repo->all($params);
    }

    public function getById($id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->repo->create($data);
    }

    public function update(Product $product, array $data)
    {
        return $this->repo->update($product, $data);
    }

    public function delete(Product $product)
    {
        return $this->repo->delete($product);
    }
}
