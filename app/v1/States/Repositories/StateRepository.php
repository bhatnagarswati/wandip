<?php

namespace App\v1\States\Repositories;

use Jsdecena\Baserepo\BaseRepository;
use App\v1\Cities\City;
use App\v1\Cities\Repositories\CityRepository;
use App\v1\States\State;
use Illuminate\Support\Collection;

class StateRepository extends BaseRepository implements StateRepositoryInterface
{
    /**
     * StateRepository constructor.
     *
     * @param State $state
     */
    public function __construct(State $state)
    {
        parent::__construct($state);
        $this->model = $state;
    }

    /**
     * @return Collection
     */
    public function listCities(): Collection
    {
        $cityRepo = new CityRepository(new City);
        return $cityRepo->listCitiesByStateCode($this->model->state_code);
    }
}