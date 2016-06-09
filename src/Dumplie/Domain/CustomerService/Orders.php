<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;

interface Orders
{
    /**
     * @param OrderId $cartId
     *
     * @throws OrderNotFoundException
     * @return Order
     */
    public function getById(OrderId $cartId) : Order;

    /**
     * @param Order $order
     */
    public function add(Order $order);
}
