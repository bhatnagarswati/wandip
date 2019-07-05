<?php

namespace App\v1\Provinces\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\v1\Countries\Country;
use App\v1\Provinces\Province;
use Illuminate\Support\Collection;

interface ProvinceRepositoryInterface extends BaseRepositoryInterface
{
    public function listProvinces(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function findProvinceById(int $id) : Province;

    public function updateProvince(array $params) : bool;

    public function listCities(int $provinceId);

    public function findCountry() : Country;
}
