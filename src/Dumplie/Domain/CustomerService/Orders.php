<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;

interface Orders
{
    /**
     * @param OrderId $orderId
     *
     * @throws OrderNotFoundException
     * @return Order
     */
    public function getById(OrderId $orderId) : Order;

    /**
     * @param Order $order
     */
    public function add(Order $order);
}
