<?php

namespace App\v1\Shipping;

use App\v1\Addresses\Address;
use Illuminate\Support\Collection;

interface ShippingInterface
{
    public function setPickupAddress();

    public function setDeliveryAddress(Address $address);

    public function readyParcel(Collection $collection);

    public function getRates(string $shipmentObjId, string $currency = 'USD');

    public function readyShipment();
}