<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\InMemory\Transaction;

use Dumplie\SharedKernel\Application\Transaction\Transaction;

final class InMemoryTransaction implements Transaction
{
    public function commit()
    {
        // do nothing, everything happens in memory 
    }

    public function rollback()
    {
        throw new \RuntimeException('InMemoryTransaction does not supports rollbacks');
    }
}
