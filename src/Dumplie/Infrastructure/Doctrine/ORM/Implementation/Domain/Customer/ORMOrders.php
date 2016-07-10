<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\Customer;

use Doctrine\ORM\EntityManager;
use Dumplie\Domain\Customer\Exception\OrderNotFoundException;
use Dumplie\Domain\Customer\Order;
use Dumplie\Domain\Customer\OrderId;
use Dumplie\Domain\Customer\Orders;

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