<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\InMemory;

use Dumplie\Customer\Domain\Exception\OrderNotFoundException;
use Dumplie\Customer\Domain\Order;
use Dumplie\Customer\Domain\OrderId;
use Dumplie\Customer\Domain\Orders;

final class InMemoryOrders implements Orders
{
    /**
     * @var Order[]
     */
    private $orders;

    public function __construct()
    {
        $this->orders = [];
    }

    /**
     * @param OrderId $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $id) : Order
    {
        if (!$this->exists($id)) {
            throw new OrderNotFoundException;
        }

        return $this->orders[(string) $id];
    }

    /**
     * @param Order $order
     */
    public function add(Order $order)
    {
        $this->orders[(string) $order->id()] = $order;
    }

    /**
     * @param OrderId $id
     * @return bool
     */
    public function exists(OrderId $id) : bool
    {
        return array_key_exists((string) $id, $this->orders);
    }
}