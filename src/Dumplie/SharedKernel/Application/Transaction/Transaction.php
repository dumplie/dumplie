<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Transaction;

interface Transaction
{
    public function commit();

    public function rollback();
}
