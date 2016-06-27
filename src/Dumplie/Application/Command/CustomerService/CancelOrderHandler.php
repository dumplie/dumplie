<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Domain\CustomerService\Exception\OrderNotFoundException;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

final class CancelOrderHandler
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
     * @param CancelOrder $command
     * @throws OrderNotFoundException
     */
    public function handle(CancelOrder $command)
    {
        $order = $this->orders->getById(new OrderId($command->orderId()));
        $order->cancel();
    }
}
