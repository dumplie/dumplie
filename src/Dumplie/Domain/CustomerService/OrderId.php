<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\SharedKernel\Identity\UUID;
use Ramsey\Uuid\Uuid as BaseUUID;

final class OrderId extends UUID
{
    /**
     * @return OrderId
     */
    public static function generate() : OrderId
    {
        return new self((string) BaseUUID::uuid4());
    }
}
