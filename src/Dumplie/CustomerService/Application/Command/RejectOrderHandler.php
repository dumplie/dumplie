<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;

final class RejectOrderHandler
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
     * @param RejectOrder $command
     * @throws InvalidTransitionException
     */
    public function handle(RejectOrder $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        $order->reject();
    }
}
