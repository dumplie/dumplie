<?php

declare (strict_types = 1);

namespace Dumplie\Domain\Customer\Exception;

use Dumplie\Domain\Customer\OrderId;

class OrderAlreadyExistsException extends Exception
{
    public static function withId(OrderId $orderId)
    {
        return new self(sprintf("Order with id \"%s\" already exists.", (string) $orderId));
    }
}