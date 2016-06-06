<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\InMemory\Transaction;

use Dumplie\Application\Transaction\Transaction;

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
