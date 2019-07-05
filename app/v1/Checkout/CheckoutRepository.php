<?php

namespace App\v1\Checkout;

use App\Events\OrderCreateEvent;
use App\v1\Carts\Repositories\CartRepository;
use App\v1\Carts\ShoppingCart;
use App\v1\Orders\Order;
use App\v1\Orders\Repositories\OrderRepository;

class CheckoutRepository
{
    /**
     * @param array $data
     *
     * @return Order
     */
    public function buildCheckoutItems(array $data) : Order
    {
        $orderRepo = new OrderRepository(new Order);

        $order = $orderRepo->createOrder([
            'reference' => $data['reference'],
            'courier_id' => $data['courier_id'],
            'customer_id' => $data['customer_id'],
            'address_id' => $data['address_id'],
            'order_status_id' => $data['order_status_id'],
            'payment' => $data['payment'],
            'discounts' => $data['discounts'],
            'total_products' => $data['total_products'],
            'total' => $data['total'],
            'total_paid' => $data['total_paid'],
            'total_shipping' => isset($data['total_shipping']) ? $data['total_shipping'] : 0,
            'tax' => $data['tax']
        ]);

        return $order;
    }
}
