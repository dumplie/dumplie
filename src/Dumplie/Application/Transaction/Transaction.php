<?php

declare (strict_types = 1);

namespace Dumplie\Application\Transaction;

interface Transaction
{
    public function commit();

    public function rollback();
}
