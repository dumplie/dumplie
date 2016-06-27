<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Domain\CustomerService\Exception\InvalidTransitionException;
use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

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
