<?php

declare (strict_types = 1);

namespace Dumplie\Application\Transaction;

interface Factory
{
    /**
     * @return Transaction
     */
    public function open() : Transaction;
}
