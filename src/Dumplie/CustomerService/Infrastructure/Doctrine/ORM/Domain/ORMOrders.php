<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Infrastructure\Doctrine\ORM\Domain;

use Doctrine\ORM\EntityManager;
use Dumplie\CustomerService\Domain\Exception\OrderNotFoundException;
use Dumplie\CustomerService\Domain\Order;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;

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
     * @param OrderId $orderId
     * @return Order
     *
     * @throws OrderNotFoundException
     */
    public function getById(OrderId $orderId) : Order
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneBy(['id.id' => $orderId]);

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
}