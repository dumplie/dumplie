<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Infrastructure\Doctrine\ORM\Application\Transaction;

use Doctrine\ORM\EntityManager;
use Dumplie\SharedKernel\Application\Transaction\Transaction;

final class ORMTransaction implements Transaction
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

    public function commit()
    {
        $this->entityManager->flush();
        $this->entityManager->commit();
    }

    public function rollback()
    {
        $this->entityManager->rollback();
    }
}