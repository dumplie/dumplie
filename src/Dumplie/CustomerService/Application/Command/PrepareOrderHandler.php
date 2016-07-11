<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;

final class PrepareOrderHandler
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
     * @param PrepareOrder $command
     * @throws InvalidTransitionException
     */
    public function handle(PrepareOrder $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        $order->prepare();
    }
}
