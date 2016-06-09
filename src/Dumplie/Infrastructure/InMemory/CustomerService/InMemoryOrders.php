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
     * @param OrderId $cartId
     *
     * @return Order
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $cartId) : Order
    {
        if (!array_key_exists((string) $cartId, $this->orders)) {
            throw OrderNotFoundException::byId($cartId);
        }

        return $this->orders[(string) $cartId];
    }

    /**
     * @param Order $order
     */
    public function add(Order $order)
    {
        $this->orders[(string) $order->id()] = $order;
    }
}
