<?php

namespace App\v1\OrderStatuses\Repositories\Interfaces;

use Jsdecena\Baserepo\BaseRepositoryInterface;
use App\v1\OrderStatuses\OrderStatus;
use Illuminate\Support\Collection;

interface OrderStatusRepositoryInterface extends BaseRepositoryInterface
{
    public function createOrderStatus(array $orderStatusData) : OrderStatus;

    public function updateOrderStatus(array $data) : bool;

    public function findOrderStatusById(int $id) : OrderStatus;

    public function listOrderStatuses();

    public function deleteOrderStatus() : bool;

    public function findOrders(): Collection;

    public function findByName(string $name);
}
