<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Tests\Double\Application\Transaction;

use Dumplie\SharedKernel\Application\Transaction\Factory;
use Dumplie\SharedKernel\Application\Transaction\Transaction;

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