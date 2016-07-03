<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Application\Transaction;

use Doctrine\ORM\EntityManager;

final class Factory implements \Dumplie\Application\Transaction\Factory
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
     * @return \Dumplie\Application\Transaction\Transaction
     */
    public function open() : \Dumplie\Application\Transaction\Transaction
    {
        $this->entityManager->beginTransaction();

        return new Transaction($this->entityManager);
    }
}