<?php

namespace App\v1\PaymentMethods\Paypal\Exceptions;

use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PaypalRequestError extends InvalidArgumentException
{
}
