<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

final class CancelOrderHandler
{
    /**
     * @var Orders
     */
    private $orders;
    /**
     * @var Factory
     */
    private $factory;

    /**
     * CancelOrderHandler constructor.
     *
     * @param Orders  $orders
     * @param Factory $factory
     */
    public function __construct(Orders $orders, Factory $factory)
    {
        $this->orders = $orders;
        $this->factory = $factory;
    }

    /**
     * @param CancelOrder $command
     *
     * @throws \Exception
     */
    public function handle(CancelOrder $command)
    {
        $transaction = $this->factory->open();

        try {
            $order = $this->orders->getById(new OrderId($command->orderId()));
            $order->cancel();
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
