<?php

declare (strict_types = 1);

namespace Dumplie\Customer\Infrastructure\Doctrine\ORM\Domain;

use Doctrine\ORM\EntityManager;
use Dumplie\Customer\Domain\Exception\OrderNotFoundException;
use Dumplie\Customer\Domain\Order;
use Dumplie\Customer\Domain\OrderId;
use Dumplie\Customer\Domain\Orders;

final class ORMOrders implements Orders
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
     * @param OrderId $id
     * @return Order
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $id) : Order
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id.id' => $id]);

        if (is_null($order)) {
            throw new OrderNotFoundException();
        }

        return $order;
    }

    /**
     * @param Order $order
     */
    public function add(Order $order)
    {
        $this->entityManager->persist($order);
    }

    /**
     * @param OrderId $id
     * @return bool
     */
    public function exists(OrderId $id) : bool
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id.id' => $id]);

        return !is_null($order);
    }
}