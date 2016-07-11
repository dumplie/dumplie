<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Infrastructure\InMemory;

use Dumplie\CustomerService\Domain\Exception\OrderNotFoundException;
use Dumplie\CustomerService\Domain\Order;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;

final class InMemoryOrders implements Orders
{
    /**
     * @var array
     */
    private $orders;

    /**
     * @param array|Order[] $orders
     */
    public function __construct(array $orders = [])
    {
        $this->orders = [];

        foreach ($orders as $cart) {
            $this->add($cart);
        }
    }

    /**
     * @param OrderId $orderId
     *
*@return Order
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $orderId) : Order
    {
        if (!array_key_exists((string)$orderId, $this->orders)) {
            throw OrderNotFoundException::byId($orderId);
        }

        return $this->orders[(string)$orderId];
    }

    /**
     * @param Order $order
     */
    public function add(Order $order)
    {
        $this->orders[(string) $order->id()] = $order;
    }
}
