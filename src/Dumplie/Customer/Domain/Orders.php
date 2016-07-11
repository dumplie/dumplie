<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain;

use Dumplie\Customer\Domain\Exception\OrderNotFoundException;

interface Orders
{
    /**
     * @param OrderId $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $id) : Order;

    /**
     * @param Order $order
     */
    public function add(Order $order);

    /**
     * @param OrderId $id
     * @return bool
     */
    public function exists(OrderId $id) : bool;
}