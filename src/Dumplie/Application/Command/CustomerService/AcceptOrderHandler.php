<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

final class AcceptOrderHandler
{
    /**
     * @var Orders
     */
    private $orders;

    /**
     * @param Orders  $orders
     */
    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * @param AcceptOrder $command
     * @throws OrderNotFoundException
     * @throws InvalidTransitionException
     */
    public function handle(AcceptOrder $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        $order->accept();
    }
}
