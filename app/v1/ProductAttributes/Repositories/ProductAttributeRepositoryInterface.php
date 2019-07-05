<?php

namespace App\v1\ProductAttributes\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;

interface ProductAttributeRepositoryInterface extends BaseRepositoryInterface
{
    public function findProductAttributeById(int $id);
}
