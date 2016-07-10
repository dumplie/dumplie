<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction;

use Doctrine\ORM\EntityManager;
use Dumplie\Application\Transaction\Factory;
use Dumplie\Application\Transaction\Transaction;

final class ORMFactory implements Factory
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return Transaction
     */
    public function open() : Transaction
    {
        $this->entityManager->beginTransaction();

        return new ORMTransaction($this->entityManager);
    }
}