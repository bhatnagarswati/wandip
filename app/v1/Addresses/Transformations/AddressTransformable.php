<?php

namespace App\v1\Addresses\Transformations;

use App\v1\Addresses\Address;
use App\v1\Cities\Repositories\CityRepository;
use App\v1\Countries\Repositories\CountryRepository;
use App\v1\Customers\Customer;
use App\v1\Customers\Repositories\CustomerRepository;
use App\v1\Provinces\Province;
use App\v1\Provinces\Repositories\ProvinceRepository;
use App\v1\Cities\City;
use App\v1\Countries\Country;

trait AddressTransformable
{
    /**
     * Transform the address
     *
     * @param Address $address
     *
     * @return Address
     * @throws \App\v1\Cities\Exceptions\CityNotFoundException
     * @throws \App\v1\Countries\Exceptions\CountryNotFoundException
     * @throws \App\v1\Customers\Exceptions\CustomerNotFoundException
     */
    public function transformAddress(Address $address)
    {
        $obj = new Address;
        $obj->id = $address->id;
        $obj->alias = $address->alias;
        $obj->address_1 = $address->address_1;
        $obj->address_2 = $address->address_2;
        $obj->zip = $address->zip;
        $obj->city = $address->city;

        if (isset($address->province_id)) {
            $provinceRepo = new ProvinceRepository(new Province);
            $province = $provinceRepo->findProvinceById($address->province_id);
            $obj->province = $province->name;
        }

        $countryRepo = new CountryRepository(new Country);
        $country = $countryRepo->findCountryById($address->country_id);
        $obj->country = $country->name;

        $customerRepo = new CustomerRepository(new Customer);
        $customer = $customerRepo->findCustomerById($address->customer_id);
        $obj->customer = $customer->name;
        $obj->status = $address->status;

        return $obj;
    }
}
