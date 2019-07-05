<?php

namespace App\v1\States\Repositories;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use Illuminate\Support\Collection;

interface StateRepositoryInterface extends BaseRepositoryInterface
{
    public function listCities() : Collection;
}