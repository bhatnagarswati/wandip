<?php

namespace App\Providers;
 
  
/***** Api /********/
use App\v1\Addresses\Repositories\AddressRepository;
use App\v1\Addresses\Repositories\Interfaces\AddressRepositoryInterface;
use App\v1\Attributes\Repositories\AttributeRepository;
use App\v1\Attributes\Repositories\AttributeRepositoryInterface;
use App\v1\AttributeValues\Repositories\AttributeValueRepository;
use App\v1\AttributeValues\Repositories\AttributeValueRepositoryInterface;
use App\v1\Brands\Repositories\BrandRepository;
use App\v1\Brands\Repositories\BrandRepositoryInterface;
use App\v1\Carts\Repositories\CartRepository;
use App\v1\Carts\Repositories\Interfaces\CartRepositoryInterface;
use App\v1\Categories\Repositories\CategoryRepository;
use App\v1\Categories\Repositories\Interfaces\CategoryRepositoryInterface;
use App\v1\Cities\Repositories\CityRepository;
use App\v1\Cities\Repositories\Interfaces\CityRepositoryInterface;
use App\v1\Countries\Repositories\CountryRepository;
use App\v1\Countries\Repositories\Interfaces\CountryRepositoryInterface;
use App\v1\Couriers\Repositories\CourierRepository;
use App\v1\Couriers\Repositories\Interfaces\CourierRepositoryInterface;
use App\v1\Customers\Repositories\CustomerRepository;
use App\v1\Customers\Repositories\Interfaces\CustomerRepositoryInterface;
use App\v1\Employees\Repositories\EmployeeRepository;
use App\v1\Employees\Repositories\Interfaces\EmployeeRepositoryInterface;
use App\v1\Orders\Repositories\Interfaces\OrderRepositoryInterface;
use App\v1\Orders\Repositories\OrderRepository;
use App\v1\OrderStatuses\Repositories\Interfaces\OrderStatusRepositoryInterface;
use App\v1\OrderStatuses\Repositories\OrderStatusRepository;
use App\v1\ProductAttributes\Repositories\ProductAttributeRepositoryInterface;
use App\v1\Permissions\Repositories\PermissionRepository;
use App\v1\Permissions\Repositories\Interfaces\PermissionRepositoryInterface;
use App\v1\ProductAttributes\Repositories\ProductAttributeRepository;
use App\v1\Products\Repositories\Interfaces\ProductRepositoryInterface;
use App\v1\Products\Repositories\ProductRepository;
use App\v1\Provinces\Repositories\Interfaces\ProvinceRepositoryInterface;
use App\v1\Provinces\Repositories\ProvinceRepository;
use App\v1\Roles\Repositories\RoleRepository;
use App\v1\Roles\Repositories\RoleRepositoryInterface;
use App\v1\Shipping\ShippingInterface;
use App\v1\Shipping\Shippo\ShippoShipmentRepository;
use App\v1\States\Repositories\StateRepository;
use App\v1\States\Repositories\StateRepositoryInterface;

/***************************/


use Illuminate\Support\ServiceProvider;

class RepositoryApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            StateRepositoryInterface::class,
            StateRepository::class
        );

        $this->app->bind(
            ShippingInterface::class,
            ShippoShipmentRepository::class
        );

        $this->app->bind(
            BrandRepositoryInterface::class,
            BrandRepository::class
        );

        $this->app->bind(
            ProductAttributeRepositoryInterface::class,
            ProductAttributeRepository::class
        );

        $this->app->bind(
            AttributeValueRepositoryInterface::class,
            AttributeValueRepository::class
        );

        $this->app->bind(
            AttributeRepositoryInterface::class,
            AttributeRepository::class
        );

        $this->app->bind(
            EmployeeRepositoryInterface::class,
            EmployeeRepository::class
        );

        $this->app->bind(
            CustomerRepositoryInterface::class,
            CustomerRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            AddressRepositoryInterface::class,
            AddressRepository::class
        );

        $this->app->bind(
            CountryRepositoryInterface::class,
            CountryRepository::class
        );

        $this->app->bind(
            ProvinceRepositoryInterface::class,
            ProvinceRepository::class
        );

        $this->app->bind(
            CityRepositoryInterface::class,
            CityRepository::class
        );

        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        $this->app->bind(
            OrderStatusRepositoryInterface::class,
            OrderStatusRepository::class
        );

        $this->app->bind(
            CourierRepositoryInterface::class,
            CourierRepository::class
        );

        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );

        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );

        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepository::class
        );
    }
}
