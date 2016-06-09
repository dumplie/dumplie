<?php

declare (strict_types = 1);

namespace Dumplie\Application\Command\CustomerService;

use Dumplie\Application\Transaction\Factory;
use Dumplie\Domain\CustomerService\OrderId;
use Dumplie\Domain\CustomerService\Orders;

final class PayOrderHandler
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
     * PayOrderHandler constructor.
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
     * @param PayOrder $command
     *
     * @throws \Exception
     */
    public function handle(PayOrder $command)
    {
        $this->orders->getById(new OrderId($command->orderId()))->pay();

        $transaction = $this->factory->open();

        try {
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
