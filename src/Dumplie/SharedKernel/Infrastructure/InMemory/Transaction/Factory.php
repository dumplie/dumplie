<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory\Transaction;

use Dumplie\SharedKernel\Application\Transaction\Transaction;

final class Factory implements \Dumplie\SharedKernel\Application\Transaction\Factory
{
    /**
     * @return Transaction
     */
    public function open() : Transaction
    {
        return new InMemoryTransaction();
    }
}
