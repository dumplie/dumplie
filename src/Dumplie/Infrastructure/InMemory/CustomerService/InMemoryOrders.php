<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\CustomerService;

use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\Order;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

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
