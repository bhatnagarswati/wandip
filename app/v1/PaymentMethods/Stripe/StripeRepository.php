<?php

namespace App\v1\PaymentMethods\Stripe;

use App\v1\Checkout\CheckoutRepository;
use App\v1\Couriers\Courier;
use App\v1\Couriers\Repositories\CourierRepository;
use App\v1\Customers\Customer;
use App\v1\Customers\Repositories\CustomerRepository;
use App\v1\PaymentMethods\Stripe\Exceptions\StripeChargingErrorException;
use Gloudemans\Shoppingcart\Facades\Cart;
use Ramsey\Uuid\Uuid;
use Stripe\Charge;

class StripeRepository
{
    /**
     * @var Customer
     */
    private $customer;

    /**
     * StripeRepository constructor.
     * @param Customer $customer
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    /**
     * @param array $data Cart data
     * @param $total float Total items in the cart
     * @param $tax float The tax applied to the cart
     * @return Charge Stripe charge object
     * @throws StripeChargingErrorException
     */
    public function execute(array $data, $total, $tax) : Charge
    {
        try {

            $shipping = 0;
            $totalComputed = $total + $shipping;

            $customerRepo = new CustomerRepository($this->customer);
            $options['source'] = $data['stripeToken'];
            $options['currency'] = config('cart.currency');

            if ($charge = $customerRepo->charge($totalComputed, $options)) {
                $checkoutRepo = new CheckoutRepository;
                $checkoutRepo->buildCheckoutItems([
                    'reference' => Uuid::uuid4()->toString(),
                    'courier_id' => 1,
                    'customer_id' => $this->customer->id,
                    'address_id' => $data['billing_address'],
                    'order_status_id' => 1,
                    'payment' => strtolower(config('stripe.name')),
                    'discounts' => 0,
                    'total_products' => $total,
                    'total' => $totalComputed,
                    'total_paid' => $totalComputed,
                    'tax' => $tax
                ]);

                Cart::destroy();
            }

            return $charge;
        } catch (\Exception $e) {
            throw new StripeChargingErrorException($e);
        }
    }
}
