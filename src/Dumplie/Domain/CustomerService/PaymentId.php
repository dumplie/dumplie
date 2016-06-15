<?php

declare (strict_types = 1);

namespace Dumplie\Domain\CustomerService;

use Dumplie\Domain\SharedKernel\Identity\UUID;
use Ramsey\Uuid\Uuid as BaseUUID;

final class PaymentId extends UUID
{
    /**
     * @return PaymentId
     */
    public static function generate() : PaymentId
    {
        return new self((string) BaseUUID::uuid4());
    }
}
