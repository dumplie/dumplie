<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Customer;

use Dumplie\Domain\Customer\Exception\OrderNotFoundException;
use Dumplie\Domain\Customer\Order;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Domain\Customer\Orders;

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