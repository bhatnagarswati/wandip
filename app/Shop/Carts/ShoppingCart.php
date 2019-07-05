<?php

namespace App\Shop\Carts;

use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\CartItem;

class ShoppingCart extends Cart
{
    public static $defaultCurrency;

    protected $session;

    protected $event;

    public function __construct()
    {
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        parent::__construct($this->session, $this->event);

        self::$defaultCurrency = config('cart.currency');
    }

    public function getSession()
    {
        return app()->make('session');
    }

    public function getEvents()
    {
        return app()->make('events');
    }

    /**
     * Get the total price of the items in the cart.
     *
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @param float $shipping
     * @return string
     */
    public function total($decimals = null, $decimalPoint = null, $thousandSeparator = null, $shipping = 0.00)
    {
        $content = $this->getContent();
        
        $total = $content->reduce(function ($total, CartItem $cartItem) {
             return $total + ($cartItem->priceTax);
        }, 0);

        $grandTotal = $total + $shipping;

        return number_format($grandTotal, $decimals, $decimalPoint, $thousandSeparator);
    }

    /**
     * Get the sub total price of the items in the cart.
     * @author Ritesh
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @return string
     */
    public function subtotal($decimals = null,  $decimalPoint = null, $thousandSeparator = null)
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, CartItem $cartItem) {
            return $total + ($cartItem->price);
        }, 0);

        $grandTotal = $total;

        return number_format($grandTotal, $decimals, $decimalPoint, $thousandSeparator);
    }


    
    /**
     * Get the sub total price of the items in the cart.
     * @author Ritesh
     * @param int $decimals
     * @param string $decimalPoint
     * @param string $thousandSeparator
     * @return string
     */

    public function shippingCharges($decimals = null,  $decimalPoint = null, $thousandSeparator = null)
    {
        $content = $this->getContent();
        $product_shipping_charges = 0.00;
        $product_shipping = $content->reduce(function ($product_shipping, CartItem $cartItem) {

            // Add shipping fees
            $product_type =  @$cartItem->product->serviceOfferedType;
            if($product_type == 'home_delivery'){
                $delivery_charges = $cartItem->product->delivery_charges;
                @$product_shipping_charges = $delivery_charges;
            } 

            return $product_shipping + @$product_shipping_charges;
        }, 0);

        return number_format($product_shipping, $decimals, $decimalPoint, $thousandSeparator);
    }



}
