<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Domain;

use Dumplie\SharedKernel\Domain\Identity\UUID;
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
