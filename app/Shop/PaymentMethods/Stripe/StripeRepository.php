<?php

namespace App\Shop\PaymentMethods\Stripe;

use App\Shop\Carts\Repositories\CartRepository;
use App\Shop\Carts\ShoppingCart;
use App\Shop\Checkout\CheckoutRepository;
use App\Shop\Customers\Customer;
use App\Shop\Customers\Repositories\CustomerRepository;
use App\Shop\PaymentMethods\Stripe\Exceptions\StripeChargingErrorException;
use App\Shop\Servicers\Servicer;
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
    public function execute(array $data, $total, $shipping = 0, $tax): Charge
    {
        try {

            $final_total = floatval(preg_replace("/[^-0-9\.]/","",$total));
            $totalComputed =  $final_total + $shipping;
            $totalComputed = number_format((float)$totalComputed, 2, '.', '');

            $customerRepo = new CustomerRepository($this->customer);

            $cartRepo = new CartRepository(new ShoppingCart);
            $cartItems = $cartRepo->getCartItems();

            $servicerid = 0;
            // Check service provider in cart items
            if (!empty($cartItems)) {
                foreach ($cartItems as $item) {
                    $servicerid = $item->servicer_id;
                }
            }
            // If Servicer provider id not exist
            if ($servicerid == 0) {
                Cart::destroy();
                return redirect()->route('cart.index')->with('error', 'Oops!! Something went wrong.');
            }

            // Fetch Service provider data
            $service_provider = Servicer::findOrFail($servicerid);

            $referenceid = Uuid::uuid4()->toString();

            $options['source'] = $data['payment_card'];
            //$options['card_id'] = $data['payment_card'];
            $options['customer'] = $this->customer->stripe_id;
            $options['servicer'] = $service_provider->name;
            $options['connect_id'] = $service_provider->stripeConnectId;
            $options['order_reference'] = $referenceid;
            $options['description'] = $this->customer->name . " (" . $this->customer->email . " ) placed an order. Orderd item of Service provider " . $service_provider->name . ". Order reference : " . $referenceid;
            $options['currency'] = config('cart.currency');

            if ($charge = $customerRepo->charge($totalComputed, $options)) {
                $checkoutRepo = new CheckoutRepository;
                $checkoutRepo->buildCheckoutItems([
                    'reference' => $referenceid,
                    'txn_id' => $charge->id,
                    'courier_id' => 1,
                    'customer_id' => $this->customer->id,
                    'servicerId' => $servicerid,
                    'address_id' => $data['billing_address'],
                    'order_status_id' => 1,
                    'payment' => strtolower(config('stripe.name')),
                    'discounts' => 0,
                    'total_products' => $total,
                    'total' => $totalComputed,
                    'total_paid' => $totalComputed,
                    'total_shipping' => $shipping,
                    'tax' => $tax,
                ]);

                Cart::destroy();
            }

            return $charge;
        } catch (\Exception $e) {

            // dd($e->getMessage());
            throw new StripeChargingErrorException($e);
        }
    }
}
