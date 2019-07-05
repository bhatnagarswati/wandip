<?php

namespace App\v1\Orders\Transformers;

use App\v1\Addresses\Address;
use App\v1\Addresses\Repositories\AddressRepository;
use App\v1\Couriers\Courier;
use App\v1\Couriers\Repositories\CourierRepository;
use App\v1\Customers\Customer;
use App\v1\Customers\Repositories\CustomerRepository;
use App\v1\Orders\Order;
use App\v1\OrderStatuses\OrderStatus;
use App\v1\OrderStatuses\Repositories\OrderStatusRepository;

trait OrderTransformable
{
    /**
     * Transform the order
     *
     * @param Order $order
     * @return Order
     */
    protected function transformOrder(Order $order) : Order
    {

        $courierRepo = new CourierRepository(new Courier());
        $order->courier = $courierRepo->findCourierById($order->courier_id);

        $customerRepo = new CustomerRepository(new Customer());
        $order->customer = $customerRepo->findCustomerById($order->customer_id);

        $addressRepo = new AddressRepository(new Address());
        $order->address = $addressRepo->findAddressById($order->address_id);

        $orderStatusRepo = new OrderStatusRepository(new OrderStatus());
        $order->status = $orderStatusRepo->findOrderStatusById($order->order_status_id);

        return $order;
    }
}
