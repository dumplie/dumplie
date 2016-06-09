<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

final class SendOrderHandler
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
     * SendOrderHandler constructor.
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
     * @param SendOrder $command
     *
     * @throws \Exception
     */
    public function handle(SendOrder $command)
    {
        $this->orders->getById(new OrderId($command->orderId()))->send();

        $transaction = $this->factory->open();

        try {
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
