<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Domain\Exception;

use Dumplie\Customer\Domain\OrderId;

class OrderAlreadyExistsException extends Exception
{
    public static function withId(OrderId $orderId)
    {
        return new self(sprintf("Order with id \"%s\" already exists.", (string) $orderId));
    }
}