<?php

namespace App\v1\PaymentMethods;

class Payment
{
    /**
     * @var $payment
     */
    protected $payment;

    /**
     * Payment constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->payment = $class;
    }

    /**
     * @return mixed
     */
    public function init()
    {
        return $this->payment;
    }
}
