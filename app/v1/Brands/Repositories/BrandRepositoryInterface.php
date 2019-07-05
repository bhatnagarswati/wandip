<?php

namespace App\v1\Brands\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\v1\Brands\Brand;
use App\v1\Products\Product;
use Illuminate\Support\Collection;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function createBrand(array $data): Brand;

    public function findBrandById(int $id) : Brand;

    public function updateBrand(array $data) : bool;

    public function deleteBrand() : bool;

    public function listBrands($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc') : Collection;

    public function saveProduct(Product $product);
}
