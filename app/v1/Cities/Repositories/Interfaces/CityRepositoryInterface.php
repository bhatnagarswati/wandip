<?php

namespace App\v1\Cities\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\v1\Cities\City;

interface CityRepositoryInterface extends BaseRepositoryInterface
{
    public function listCities();

    public function findCityById(int $id) : City;

    public function updateCity(array $params) : bool;

    public function findCityByName(string $name) : City;
}
