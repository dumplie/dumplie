<?php

declare (strict_types = 1);

namespace Dumplie\CustomerService\Application\Command;

use Dumplie\CustomerService\Domain\Exception\InvalidTransitionException;
use Dumplie\CustomerService\Domain\Exception\OrderNotFoundException;
use Dumplie\CustomerService\Domain\OrderId;
use Dumplie\CustomerService\Domain\Orders;

final class RefundOrderHandler
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
     * @param RefundOrder $command
     * @throws InvalidTransitionException
     * @throws OrderNotFoundException
     */
    public function handle(RefundOrder $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        $order->refund();
    }
}
