<?php

namespace App\v1\PaymentMethods\Paypal\Repositories;

use Illuminate\Http\Request;

interface PayPalExpressCheckoutRepositoryInterface
{
    public function getApiContext();

    public function process($shippingFee, Request $request);

    public function execute(Request $request);
}
