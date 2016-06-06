<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Transaction;

use Dumplie\Application\Transaction\Transaction;

final class Factory implements \Dumplie\Application\Transaction\Factory
{
    /**
     * @return Transaction
     */
    public function open() : Transaction
    {
        return new InMemoryTransaction();
    }
}
