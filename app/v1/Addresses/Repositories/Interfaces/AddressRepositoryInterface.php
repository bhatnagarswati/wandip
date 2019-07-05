<?php

namespace App\v1\Addresses\Repositories\Interfaces;

use App\v1\Addresses\Address;
use App\v1\Cities\City;
use App\v1\Countries\Country;
use App\v1\Customers\Customer;
use App\v1\Provinces\Province;
use Illuminate\Support\Collection;
use Jsdecena\Baserepo\BaseRepositoryInterface;

interface AddressRepositoryInterface extends BaseRepositoryInterface
{
    public function createAddress(array $params) : Address;

    public function attachToCustomer(Address $address, Customer $customer);

    public function updateAddress(array $update): bool;

    public function deleteAddress();

    public function listAddress(string $order = 'id', string $sort = 'desc', array $columns = ['*']) : Collection;

    public function findAddressById(int $id) : Address;

    public function findCustomer() : Customer;

    public function searchAddress(string $text) : Collection;

    public function findCountry() : Country;

    public function findProvince() : Province;

    public function findCity() : City;
}
