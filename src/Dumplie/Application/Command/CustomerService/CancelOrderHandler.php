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

    public function __construct(Orders $orders, Factory $factory)
    {
        $this->orders = $orders;
        $this->factory = $factory;
    }

    public function handle(CancelOrder $command)
    {
        $this->orders->getById(new OrderId($command->orderId()))->cancel();

        $transaction = $this->factory->open();

        try {
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }
}
