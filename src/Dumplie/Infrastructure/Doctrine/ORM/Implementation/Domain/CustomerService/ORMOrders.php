<?php

declare (strict_types = 1);

namespace Dumplie\Infrastructure\Doctrine\ORM\Implementation\Domain\CustomerService;

use Doctrine\ORM\EntityManager;
use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\Order;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

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