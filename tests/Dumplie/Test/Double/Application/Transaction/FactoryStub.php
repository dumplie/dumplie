<?php

declare (strict_types = 1);

namespace Dumplie\Test\Double\Application\Transaction;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Application\Transaction\Transaction;

final class FactoryStub implements Factory
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * @return Transaction
     */
    public function open() : Transaction
    {
        return $this->transaction;
    }
}